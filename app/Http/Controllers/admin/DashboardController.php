<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\OrderModel;
use App\Models\ShopModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $titlePage = 'Trang chủ';
        $page_menu = 'dashboard';
        $page_sub = null;
        return view('admin.index', compact('titlePage','page_menu','page_sub'));
    }

    //Hiệu suất tổng thể
    public function overallPerformance()
    {
        $titlePage = 'Hiểu suất tổng thể';
        $page_menu = 'overall_performance';
        $page_sub = null;

        $order_all = DB::table('orders')->count('id');
        $waiting_payment = DB::table('orders')->where('status_id',1)->count('id');
        $order_paid = DB::table('orders')->where('status_id',2)->count('id');
        $order_canceled = DB::table('orders')->where('status_id',3)->count('id');

        $order_all_money = DB::table('orders')->sum('total_price');
        $waiting_payment_money = DB::table('orders')->where('status_id',1)->sum('total_price');
        $order_paid_money = DB::table('orders')->where('status_id',2)->sum('total_price');
        $order_canceled_money = DB::table('orders')->where('status_id',3)->sum('total_price');

        // Lấy danh sách các năm
        $years = DB::table('orders')
            ->select(DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->get();

        // Lấy danh sách các quý
        $quarters = DB::table('orders')
            ->select(DB::raw('QUARTER(created_at) as quarter'))
            ->distinct()
            ->get();

        // Lấy danh sách các tháng
        $months = DB::table('orders')
            ->select(DB::raw('MONTH(created_at) as month'))
            ->distinct()
            ->orderBy('month', 'desc')
            ->get();

        $currentYear = now()->year;
        $currentMonth = now()->month;

        // Lấy doanh thu của từng ngày trong tháng hiện tại của năm hiện tại
        $dailyRevenues = DB::table('orders')
            ->select(DB::raw('DATE(created_at) as day'), DB::raw('SUM(total_price) as total_revenue'))
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->get();

        $dailyRevenueData = [
            'revenues' => $dailyRevenues->pluck('total_revenue')->toArray(),
            'days' => $dailyRevenues->pluck('day')->toArray(),
        ];

        $currentRevenue = DB::table('orders')
            ->sum('total_price');

        //Tăng trưởng doanh thu
        $currentRevenues = DB::table('orders')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->sum('total_price');

        $previousRevenue = DB::table('orders')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth - 1)
            ->sum('total_price');

        $growthRate = 0;
        if ($previousRevenue > 0) {
            $growthRate = (($currentRevenues - $previousRevenue) / $previousRevenue) * 100;
        }

        return view('admin.statistical.overall-performance', [
            'titlePage'=>$titlePage,
            'page_menu'=>$page_menu,
            'page_sub'=>$page_sub,
            'order_all'=>$order_all,
            'waiting_payment'=>$waiting_payment,
            'order_paid'=>$order_paid,
            'order_canceled'=>$order_canceled,
            'order_all_money'=>$order_all_money,
            'waiting_payment_money'=>$waiting_payment_money,
            'order_paid_money'=>$order_paid_money,
            'order_canceled_money'=>$order_canceled_money,
            'revenues' => $dailyRevenueData,
            'months' => $months,
            'quarters' => $quarters,
            'years' => $years,
            'currentRevenue'=>$currentRevenue,
            'currentRevenues'=>$currentRevenues,
            'previousRevenue'=>$previousRevenue,
            'growthRate'=>$growthRate
        ]);
    }

    //Bộ lọc doanh thu toàn sàn
    public function getRevenueData(Request $request)
    {
        // Lấy các tham số tháng, quý và năm từ yêu cầu
        $month = $request->get('month');
        $quarter = $request->get('quarter');
        $year = $request->get('year');

        // Lấy năm hiện tại nếu không có năm được chọn
        $currentYear = now()->year;

        // Truy vấn doanh thu theo tháng/quý/năm
        $query = DB::table('orders')
            ->select(DB::raw('SUM(total_price) as total_revenue'), DB::raw('MONTH(created_at) as month'), DB::raw('YEAR(created_at) as year'), DB::raw('DAY(created_at) as day'))
            ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at), DAY(created_at)')); // Nhóm theo ngày trong tháng

        // Nếu có bộ lọc tháng, lọc theo tháng
        if ($month) {
            $query->whereMonth('created_at', $month);
        }

        // Nếu có bộ lọc quý, lọc theo quý (cần nhóm theo quý)
        if ($quarter) {
            $query->where(DB::raw('QUARTER(created_at)'), $quarter);
        }

        // Nếu không có năm được chọn, mặc định lấy theo năm hiện tại
        if ($year) {
            $query->whereYear('created_at', $year);
        } else {
            // Nếu không có bộ lọc năm, lấy dữ liệu của năm hiện tại
            $query->whereYear('created_at', $currentYear);
        }

        // Lấy kết quả doanh thu
        $revenues = $query->get();
        $totalRevenue = $revenues->sum('total_revenue');

        // Tạo dữ liệu trả về cho biểu đồ
        $revenuesData = [
            'revenues' => $revenues->pluck('total_revenue')->toArray(), // Danh sách doanh thu
            'dates' => $revenues->map(function($item) {
                // Trả về định dạng ngày-tháng/năm
                return $item->day . '/' . $item->month . '/' . $item->year;
            })->toArray(),
            'totalRevenue' => $totalRevenue
        ];

        return response()->json(['data' => $revenuesData]);
    }

    //Bộ lọc thống kê đơn hàng
    public function statistics(Request $request)
    {
        // Lấy các giá trị từ các bộ lọc
        $selectedMonth = $request->input('month');
        $selectedQuarter = $request->input('quarter');
        $selectedYear = $request->input('year');
        $currentYear = now()->year;

        // Xây dựng query cơ bản
        $query = OrderModel::query();

        // Lọc theo năm
        if ($selectedYear) {
            $query->whereYear('created_at', $selectedYear);
        }else{
            $query->whereYear('created_at', $currentYear);
        }

        // Lọc theo tháng
        if ($selectedMonth) {
            $query->whereMonth('created_at', $selectedMonth);
        }

        // Lọc theo quý
        if ($selectedQuarter) {
            $query->where(DB::raw('QUARTER(created_at)'), $selectedQuarter);
        }
        $order_paid = clone $query;
        $waiting_payment = clone $query;
        $order_canceled = clone $query;
        $order_all_money = clone $query;
        $waiting_payment_money = clone $query;
        $order_paid_money = clone $query;
        $order_canceled_money = clone $query;

        $revenuesData = [
            'order_all'=> $query->count('id'),
            'waiting_payment'=>$waiting_payment->where('status_id',1)->count('id'),
            'order_paid'=>$order_paid->where('status_id',2)->count('id'),
            'order_canceled'=>$order_canceled->where('status_id',3)->count('id'),
            'order_all_money'=>$order_all_money->sum('total_price'),
            'waiting_payment_money'=>$waiting_payment_money->where('status_id',1)->sum('total_price'),
            'order_paid_money'=>$order_paid_money->where('status_id',2)->sum('total_price'),
            'order_canceled_money'=>$order_canceled_money->where('status_id',3)->sum('total_price'),
        ];

        return response()->json(['data' => $revenuesData]);
    }

    //Bộ lọc tăng trưởng doanh thu
    public function revenueGrowth(Request $request)
    {
        // Lấy giá trị bộ lọc từ request
        $selectedMonth = $request->input('month');
        $selectedQuarter = $request->input('quarter');
        $selectedYear = $request->input('year')??now()->year;

        // Truy vấn doanh thu cho thời kỳ hiện tại
        if ($selectedYear) {
            $currentRevenue = DB::table('orders')
                ->whereYear('created_at', $selectedYear)
                ->sum('total_price');
        } elseif ($selectedMonth) {
            $currentRevenue = DB::table('orders')
                ->whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $selectedMonth)
                ->sum('total_price');
        } elseif ($selectedQuarter) {
            $currentRevenue = DB::table('orders')
                ->whereYear('created_at', $selectedYear)
                ->whereRaw('QUARTER(created_at) = ?', [$selectedQuarter])
                ->sum('total_price');
        }

        // Tính doanh thu của thời kỳ trước (tháng trước, quý trước, hoặc năm trước)
        $previousRevenue = 0;
        if ($selectedYear) {
            $previousRevenue = DB::table('orders')
                ->whereYear('created_at', $selectedYear - 1)
                ->sum('total_price');
        }elseif ($selectedMonth) {
            $previousRevenue = DB::table('orders')
                ->whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $selectedMonth - 1)
                ->sum('total_price');
        } elseif ($selectedQuarter) {
            $previousRevenue = DB::table('orders')
                ->whereYear('created_at', $selectedYear)
                ->whereRaw('QUARTER(created_at) = ?', [$selectedQuarter - 1])
                ->sum('total_price');
        }

        // Tính phần trăm tăng trưởng
        $growthPercentage = 0;
        if ($previousRevenue > 0) {
            $growthPercentage = (($currentRevenue - $previousRevenue) / $previousRevenue) * 100;
        }

        // Gửi dữ liệu về view
        $revenuesData = [
            'currentRevenue' => $currentRevenue,
            'previousRevenue' => $previousRevenue,
            'growthPercentage' => number_format($growthPercentage, 2),
        ];
        return response()->json(['data' => $revenuesData]);
    }

    //Hiệu suất từng gian hàng
    public function performanceShop($id,Request $request)
    {
        $titlePage = 'Hiểu suất từng shop';
        $page_menu = 'performance_shop';
        $page_sub = null;

        $shop = ShopModel::where('display',1)->get();
        $startDate = $request->get('date_start');
        $endDate = $request->get('date_end');
        $endDate = Carbon::parse($endDate)->endOfDay();

        $query = DB::table('orders');
        $queryOrder = DB::table('order_products');
        $queryOrderShop = DB::table('order_products');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
            $queryOrder->whereBetween('order_products.created_at', [$startDate, $endDate]);
            $queryOrderShop->whereBetween('order_products.created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('created_at', '>=', $startDate);
            $queryOrder->where('order_products.created_at', '>=', $startDate);
            $queryOrderShop->where('order_products.created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('created_at', '<=', $endDate);
            $queryOrder->where('order_products.created_at', '<=', $endDate);
            $queryOrderShop->where('order_products.created_at', '<=', $endDate);
        }else{
            $query->whereNotNull('created_at');
            $queryOrder->whereNotNull('order_products.created_at');
            $queryOrderShop->whereNotNull('order_products.created_at');
        }

        $highestRevenueShops = clone $queryOrderShop;
        $lowestRevenueShops = clone $queryOrderShop;

        if ($id == 'all'){
            $order_all = clone $query;
            $waiting_payment = clone $query;
            $order_paid = clone $query;
            $order_canceled = clone $query;
            $order_all_money = clone $query;
            $waiting_payment_money = clone $query;
            $order_paid_money = clone $query;
            $order_canceled_money = clone $query;
            $dailyRevenues = clone $query;
            $currentRevenue = clone $query;

            $order_all = $order_all->count('id');
            $waiting_payment = $waiting_payment->where('status_id', 1)->count('id');
            $order_paid = $order_paid->where('status_id', 2)->count('id');
            $order_canceled = $order_canceled->where('status_id', 3)->count('id');

            $order_all_money = $order_all_money->sum('total_price');
            $waiting_payment_money = $waiting_payment_money->where('status_id', 1)->sum('total_price');
            $order_paid_money = $order_paid_money->where('status_id', 2)->sum('total_price');
            $order_canceled_money = $order_canceled_money->where('status_id', 3)->sum('total_price');

            $dailyRevenues = $dailyRevenues->select(DB::raw('DATE(created_at) as day'), DB::raw('SUM(total_price) as total_revenue'))
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy(DB::raw('DATE(created_at)'))
                ->get();

            $revenues = [
                'revenues' => $dailyRevenues->pluck('total_revenue')->toArray(),
                'days' => $dailyRevenues->pluck('day')->toArray(),
            ];

            $currentRevenue = $currentRevenue->sum('total_price');
        }else{
            $order_all = clone $queryOrder;
            $waiting_payment = clone $queryOrder;
            $order_paid = clone $queryOrder;
            $order_canceled = clone $queryOrder;
            $order_all_money = clone $queryOrder;
            $waiting_payment_money = clone $queryOrder;
            $order_paid_money = clone $queryOrder;
            $order_canceled_money = clone $queryOrder;
            $dailyRevenues = clone $queryOrder;
            $currentRevenue = clone $queryOrder;

            $order_all = $order_all->join('orders', 'order_products.order_id', '=', 'orders.id')->where('order_products.shop_id',$id)->count('order_products.id');
            $waiting_payment = $waiting_payment->join('orders', 'order_products.order_id', '=', 'orders.id')->where('order_products.shop_id',$id)->where('orders.status_id',1)->count('order_products.id');
            $order_paid =  $order_paid->join('orders', 'order_products.order_id', '=', 'orders.id')->where('order_products.shop_id',$id)->where('orders.status_id',2)->count('order_products.id');
            $order_canceled =  $order_canceled->join('orders', 'order_products.order_id', '=', 'orders.id')->where('order_products.shop_id',$id)->where('orders.status_id',3)->count('order_products.id');

            $order_all_money = $order_all_money->join('orders', 'order_products.order_id', '=', 'orders.id')->where('order_products.shop_id',$id)->sum(DB::raw('order_products.quantity * order_products.unit_price'));
            $waiting_payment_money = $waiting_payment_money->join('orders', 'order_products.order_id', '=', 'orders.id')->where('order_products.shop_id',$id)->where('orders.status_id',1)->sum(DB::raw('order_products.quantity * order_products.unit_price'));
            $order_paid_money = $order_paid_money->join('orders', 'order_products.order_id', '=', 'orders.id')->where('order_products.shop_id',$id)->where('orders.status_id',2)->sum(DB::raw('order_products.quantity * order_products.unit_price'));
            $order_canceled_money = $order_canceled_money->join('orders', 'order_products.order_id', '=', 'orders.id')->where('order_products.shop_id',$id)->where('orders.status_id',3)->sum(DB::raw('order_products.quantity * order_products.unit_price'));

            $dailyRevenues = $dailyRevenues->join('orders', 'order_products.order_id', '=', 'orders.id')
                ->select(DB::raw('DATE(order_products.created_at) as day'), DB::raw('SUM(order_products.quantity * order_products.unit_price) as total_revenue'))
                ->where('order_products.shop_id',$id)
                ->groupBy(DB::raw('DATE(order_products.created_at)'))
                ->orderBy(DB::raw('DATE(order_products.created_at)'))
                ->get();

            $revenues = [
                'revenues' => $dailyRevenues->pluck('total_revenue')->toArray(),
                'days' => $dailyRevenues->pluck('day')->toArray(),
            ];

            $currentRevenue =$currentRevenue->join('orders', 'order_products.order_id', '=', 'orders.id')->where('order_products.shop_id',$id)
                ->sum(DB::raw('order_products.quantity * order_products.unit_price'));
        }

        // Tìm gian hàng có doanh thu cao nhất trong tháng và năm hiện tại
        $highestRevenueShop = $highestRevenueShops->join('orders as o1', 'order_products.order_id', '=', 'o1.id')
        ->join('shops as s1', 'order_products.shop_id', '=', 's1.id')
        ->select(
            's1.name as shop_name',
            DB::raw('SUM(order_products.quantity * order_products.unit_price) as total_revenue')
        )
            ->groupBy('s1.name')
            ->orderByDesc('total_revenue')
            ->limit(1)
            ->first();

        // Tìm gian hàng có doanh thu thấp nhất
        $lowestRevenueShop  = $lowestRevenueShops->join('orders as o2', 'order_products.order_id', '=', 'o2.id')
        ->join('shops as s2', 'order_products.shop_id', '=', 's2.id')
        ->select(
            's2.name as shop_name',
            DB::raw('SUM(order_products.quantity * order_products.unit_price) as total_revenue')
        )
            ->groupBy('s2.name')
            ->orderBy('total_revenue')
            ->limit(1)
            ->first();

        return view('admin.statistical.performance-shop', compact('titlePage','page_menu','page_sub','order_all','waiting_payment','order_paid','order_canceled',
        'order_all_money','waiting_payment_money','order_paid_money','order_canceled_money','shop','id','revenues','currentRevenue','highestRevenueShop','lowestRevenueShop'));
    }

    public function upload(Request $request)
    {
        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;
            $request->file('upload')->move(public_path('userfiles'), $fileName);
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('userfiles/'.$fileName);
            $msg = 'Image successfully uploaded';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }
}
