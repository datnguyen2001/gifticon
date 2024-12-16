<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\OrderProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommissionController extends Controller
{
    public function commission()
    {
        $titlePage = 'Thống kê hoa hồng';
        $page_menu = 'commission';
        $page_sub = null;

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

        $countCommissionByShop = OrderProductModel::select('shop_id', DB::raw('SUM(commission_money) as total_commission'))
            ->groupBy('shop_id', 'shops.name')
            ->join('shops', 'shops.id', '=', 'order_products.shop_id')
            ->select('shops.name as shop_name', DB::raw('SUM(order_products.commission_money) as total_commission'))
            ->orderBy('total_commission', 'desc')
            ->get();

        $totalCommissionByMonth = OrderProductModel::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(commission_money) as total_commission')
        )
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        return view('admin.statistical.commission', compact('titlePage', 'page_menu', 'page_sub'
        ,'years', 'quarters', 'months', 'countCommissionByShop', 'totalCommissionByMonth'));
    }

    public function commissionRange(Request $request)
    {
        $selectedMonth = $request->input('month');
        $selectedQuarter = $request->input('quarter');
        $selectedYear = $request->input('year');
        $shopName = $request->input('shop_name');
        $currentYear = now()->year;

        $query = OrderProductModel::query();

        if ($selectedYear) {
            $query->whereYear('order_products.created_at', $selectedYear);
        } else {
            $query->whereYear('order_products.created_at', $currentYear);
        }

        if ($selectedMonth) {
            $query->whereMonth('order_products.created_at', $selectedMonth);
        }

        if ($selectedQuarter) {
            $query->where(DB::raw('QUARTER(order_products.created_at)'), $selectedQuarter);
        }

        $query->join('shops', 'shops.id', '=', 'order_products.shop_id');

        if ($shopName) {
            $query->where('shops.name', 'like', '%' . $shopName . '%');
        }

        $countCommissionByShop = $query->select('shops.name as shop_name', DB::raw('SUM(order_products.commission_money) as total_commission'))
            ->groupBy('shop_id', 'shops.name')
            ->orderBy('total_commission', 'desc')
            ->get();

        return response()->json(['data' => $countCommissionByShop]);
    }
}
