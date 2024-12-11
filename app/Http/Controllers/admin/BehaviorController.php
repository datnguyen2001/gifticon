<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\OrderModel;
use App\Models\UserBehavior;
use DateTime;
use Google\Client as GoogleClient;
use Google\Service\AnalyticsData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BehaviorController extends Controller
{
    public function userBehavior()
    {
        $titlePage = 'Hành vi người dùng';
        $page_menu = 'user_behavior';
        $page_sub = null;

        $credentialsPath = storage_path('app/public/gifticon-ga4.json');

        $client = new GoogleClient();
        $client->setAuthConfig($credentialsPath);
        $client->addScope(AnalyticsData::ANALYTICS_READONLY);

        // Initialize Google Analytics Data API client
        $analyticsData = new AnalyticsData($client);
        $propertyId = 'properties/469790856';

        // First request to get page views for the root page "/"
        $requestRoot = new AnalyticsData\RunReportRequest([
            'dateRanges' => [['startDate' => '30daysAgo', 'endDate' => 'today']],
            'dimensions' => [['name' => 'date'], ['name' => 'pagePath']],
            'metrics' => [['name' => 'screenPageViews']],
            'dimensionFilter' => [
                'filter' => [
                    'fieldName' => 'pagePath',
                    'stringFilter' => [
                        'matchType' => 'EXACT',
                        'value' => '/',
                    ]
                ]
            ]
        ]);

        // Second request to get page views for pages starting with "/thuong-hieu/"
        $requestBrand = new AnalyticsData\RunReportRequest([
            'dateRanges' => [['startDate' => '30daysAgo', 'endDate' => 'today']],
            'dimensions' => [['name' => 'date'], ['name' => 'pagePath']],
            'metrics' => [['name' => 'screenPageViews']],
            'dimensionFilter' => [
                'filter' => [
                    'fieldName' => 'pagePath',
                    'stringFilter' => [
                        'matchType' => 'BEGINS_WITH',
                        'value' => '/thuong-hieu/',
                    ]
                ]
            ]
        ]);

        try {
            $responseRoot = $analyticsData->properties->runReport($propertyId, $requestRoot);
            $responseBrand = $analyticsData->properties->runReport($propertyId, $requestBrand);

            $ga4Data = [];
            foreach (array_merge($responseRoot->getRows(), $responseBrand->getRows()) as $row) {
                $ga4Data[] = [
                    'date' => $row->getDimensionValues()[0]->getValue(),
                    'pagePath' => $row->getDimensionValues()[1]->getValue(),
                    'screenPageViews' => $row->getMetricValues()[0]->getValue()
                ];
            }

            foreach ($ga4Data as $data) {
                $date = DateTime::createFromFormat('Ymd', $data['date']);
                $formattedDate = $date->format('Y-m-d 00:00:00');

                UserBehavior::updateOrCreate(
                    [
                        'date' => $formattedDate,
                        'path' => $data['pagePath'],
                    ],
                    [
                        'page_view' => $data['screenPageViews'],
                    ]
                );
            }

            $dataPageView = UserBehavior::get();

            $orderCountByDate = OrderModel::select(DB::raw('DATE(created_at) as date, count(*) as buy_count'))
                ->groupBy(DB::raw('DATE(created_at)'))
                ->get();

            $orderResultCalculate = $dataPageView->map(function ($pageView) use ($orderCountByDate) {
                $order = $orderCountByDate->firstWhere(function ($orderItem) use ($pageView) {
                    return $orderItem->date == $pageView->date->format('Y-m-d');
                });

                $buyCount = $order ? $order->buy_count : 0;

                $percentage = $buyCount > 0 && $pageView->page_view > 0 ? ($buyCount / $pageView->page_view) * 100 : 0;

                return [
                    'date' => $pageView->date->format('Y-m-d'),
                    'buy_count' => $buyCount,
                    'page_view' => $pageView->page_view,
                    'percentage' => $percentage,
                ];
            });

            return view('admin.statistical.user_behavior', compact('dataPageView', 'orderResultCalculate', 'titlePage', 'page_menu', 'page_sub'));
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function userBehaviorRange($range, Request $request)
    {
        $startDate = Carbon::now();
        $endDate = Carbon::now();

        switch ($range) {
            case 'yesterday':
                $startDate = Carbon::yesterday();
                $endDate = Carbon::yesterday();
                break;
            case '7_days_before':
                $startDate = Carbon::now()->subDays(7);
                break;
            case '30_days_before':
                $startDate = Carbon::now()->subDays(30);
                break;
            case 'this_month':
                $startDate = Carbon::now()->startOfMonth();
                break;
            case 'last_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;
        }

        $dataPageView = UserBehavior::whereBetween('date', [$startDate, $endDate])
            ->get();
        if ($request->ajax()) {
            return response()->json($dataPageView);
        }

        return view('admin.statistical.user_behavior', compact('dataPageView'));
    }

    public function userBehaviorPercentageRange($range, Request $request)
    {
        $startDate = Carbon::now();
        $endDate = Carbon::now();

        switch ($range) {
            case 'c_yesterday':
                $startDate = Carbon::yesterday();
                $endDate = Carbon::yesterday();
                break;
            case 'c_7_days_before':
                $startDate = Carbon::now()->subDays(7);
                break;
            case 'c_30_days_before':
                $startDate = Carbon::now()->subDays(30);
                break;
            case 'c_this_month':
                $startDate = Carbon::now()->startOfMonth();
                break;
            case 'c_last_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;
        }

        $dataPageView = UserBehavior::whereBetween('date', [$startDate, $endDate])->get();

        $orderCountByDate = OrderModel::select(DB::raw('DATE(created_at) as date, count(*) as buy_count'))
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate->toDateString(), $endDate->toDateString()])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();

        $orderResultCalculate = $dataPageView->map(function ($pageView) use ($orderCountByDate) {
            $order = $orderCountByDate->firstWhere(function ($orderItem) use ($pageView) {
                return $orderItem->date == $pageView->date->format('Y-m-d');
            });

            $buyCount = $order ? $order->buy_count : 0;

            $percentage = $buyCount > 0 && $pageView->page_view > 0 ? ($buyCount / $pageView->page_view) * 100 : 0;

            return [
                'date' => $pageView->date->format('Y-m-d'),
                'buy_count' => $buyCount,
                'page_view' => $pageView->page_view,
                'percentage' => $percentage,
            ];
        });

        if ($request->ajax()) {
            return response()->json($orderResultCalculate);
        }

        return view('admin.statistical.user_behavior', compact('dataPageView', 'orderResultCalculate'));
    }
}




