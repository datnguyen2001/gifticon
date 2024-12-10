<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Google\Client as GoogleClient;
use Google\Service\AnalyticsData;
use Carbon\Carbon;

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

        // Prepare request
        $request = new AnalyticsData\RunReportRequest([
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

        try {
            $response = $analyticsData->properties->runReport($propertyId, $request);
            $ga4Data = [];
            foreach ($response->getRows() as $row) {
                $ga4Data[] = [
                    'date' => $row->getDimensionValues()[0]->getValue(),
                    'pagePath' => $row->getDimensionValues()[1]->getValue(),
                    'screenPageViews' => $row->getMetricValues()[0]->getValue()
                ];
            }
            return view('admin.statistical.user_behavior', compact('ga4Data', 'titlePage', 'page_menu', 'page_sub'));
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}




