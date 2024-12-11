<?php

namespace App\Http\Controllers\shop;

use App\Http\Controllers\Controller;
use App\Models\CategoryModel;
use App\Models\OrderProductModel;
use App\Models\ShopModel;
use App\Models\ShopProductModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $titlePage = 'Trang chủ';
        $page_menu = 'dashboard';
        $page_sub = null;
        return view('shop.index', compact('titlePage','page_menu','page_sub'));
    }

    public function profile()
    {
        $titlePage = 'Thông tin cá nhân';
        $page_menu = 'profile';
        $page_sub = null;
        $data = Auth::guard('shop')->user();
        $categories = CategoryModel::where('display',1)->get();

        return view('shop.profile.index', compact('titlePage','page_menu','page_sub','data','categories'));
    }

    public function updateProfile(Request $request)
    {
        try {
            $shop = Auth::guard('shop')->user();
            if ($request->hasFile('file')){
                $file = $request->file('file');
                $imagePath = Storage::url($file->store('shop', 'public'));
                if (isset($shop->src) && Storage::exists(str_replace('/storage', 'public', $shop->src))) {
                    Storage::delete(str_replace('/storage', 'public', $shop->src));
                }
                $shop->src = $imagePath;
            }
            $display = $request->get('display') == 'on' ? 1 : 0;
            if (trim($request->input('password')) !== '') {
                $shop->password = Hash::make($request->get('password'));
            }

            $shop->name = $request->get('title');
            $shop->slug = Str::slug($request->get('title'));
            $shop->phone = $request->get('phone');
            $shop->content = $request->get('content');
            $shop->display = $display;
            $shop->save();

            if ($request->has('categories')) {
                $shop->categories()->sync($request->get('categories'));
            } else {
                $shop->categories()->detach();
            }

            return redirect()->route('shop.profile')->with(['success' => 'Cập nhật dữ liệu thành công']);
        } catch (\Exception $e) {
            return back()->with(['error' => $e->getMessage()]);
        }
    }

    public function revenueOrders(Request $request)
    {
        $titlePage = 'Doanh thu và đơn hàng';
        $page_menu = 'revenue_orders';
        $page_sub = null;

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $startDate = Carbon::create($currentYear, $currentMonth, 1)->startOfDay();
        $endDate = $startDate->copy()->endOfMonth();
        $product_id = $request->input('product_id');

        if ($request->has('date_start') && $request->date_start) {
            $startDate = Carbon::parse($request->date_start)->startOfDay();
        }

        if ($request->has('date_end') && $request->date_end) {
            $endDate = Carbon::parse($request->date_end)->endOfDay();
        }

        $queryOrder = DB::table('order_products');
        $order_all = clone $queryOrder;
        $waiting_payment = clone $queryOrder;
        $order_paid = clone $queryOrder;
        $order_canceled = clone $queryOrder;
        $order_all_money = clone $queryOrder;
        $waiting_payment_money = clone $queryOrder;
        $order_paid_money = clone $queryOrder;
        $order_canceled_money = clone $queryOrder;
        $order_product_money = clone $queryOrder;

        $order_all = $order_all->join('orders', 'order_products.order_id', '=', 'orders.id')->whereBetween('orders.created_at', [$startDate, $endDate])->where('order_products.shop_id',Auth::guard('shop')->id())->count('order_products.id');
        $waiting_payment = $waiting_payment->join('orders', 'order_products.order_id', '=', 'orders.id')->whereBetween('orders.created_at', [$startDate, $endDate])->where('order_products.shop_id',Auth::guard('shop')->id())->where('orders.status_id',1)->count('order_products.id');
        $order_paid =  $order_paid->join('orders', 'order_products.order_id', '=', 'orders.id')->whereBetween('orders.created_at', [$startDate, $endDate])->where('order_products.shop_id',Auth::guard('shop')->id())->where('orders.status_id',2)->count('order_products.id');
        $order_canceled =  $order_canceled->join('orders', 'order_products.order_id', '=', 'orders.id')->whereBetween('orders.created_at', [$startDate, $endDate])->where('order_products.shop_id',Auth::guard('shop')->id())->where('orders.status_id',3)->count('order_products.id');

        $order_all_money = $order_all_money->join('orders', 'order_products.order_id', '=', 'orders.id')->whereBetween('orders.created_at', [$startDate, $endDate])->where('order_products.shop_id',Auth::guard('shop')->id())->sum(DB::raw('order_products.quantity * order_products.unit_price'));
        $waiting_payment_money = $waiting_payment_money->join('orders', 'order_products.order_id', '=', 'orders.id')->whereBetween('orders.created_at', [$startDate, $endDate])->where('order_products.shop_id',Auth::guard('shop')->id())->where('orders.status_id',1)->sum(DB::raw('order_products.quantity * order_products.unit_price'));
        $order_paid_money = $order_paid_money->join('orders', 'order_products.order_id', '=', 'orders.id')->whereBetween('orders.created_at', [$startDate, $endDate])->where('order_products.shop_id',Auth::guard('shop')->id())->where('orders.status_id',2)->sum(DB::raw('order_products.quantity * order_products.unit_price'));
        $order_canceled_money = $order_canceled_money->join('orders', 'order_products.order_id', '=', 'orders.id')->whereBetween('orders.created_at', [$startDate, $endDate])->where('order_products.shop_id',Auth::guard('shop')->id())->where('orders.status_id',3)->sum(DB::raw('order_products.quantity * order_products.unit_price'));

        // Truy vấn dữ liệu doanh thu
        $revenues = OrderProductModel::whereBetween('created_at', [$startDate, $endDate])->where('shop_id',Auth::guard('shop')->id())
            ->selectRaw('DATE(created_at) as date, SUM(quantity * unit_price) as revenue')
            ->groupBy('date')
            ->get();

        // Lấy các ngày trong khoảng thời gian
        $dates = $revenues->pluck('date');
        $revenuesData = $revenues->pluck('revenue');

        // Tính tổng doanh thu
        $totalRevenue = $revenuesData->sum();

        $listProduct = ShopProductModel::where('shop_id',Auth::guard('shop')->id())->get();

        if ($product_id) {
            $order_product_money->where('product_id', $product_id);
            if ($startDate && $endDate) {
                $order_product_money->whereBetween('created_at', [$startDate, $endDate]);
            }
            $totalRevenueProduct = $order_product_money->sum(DB::raw('quantity * unit_price'));
        }else{
            $totalRevenueProduct = 0;
        }

        return view('shop.statistical.revenue-orders', compact(
            'dates', 'totalRevenue', 'revenuesData','titlePage','page_menu','page_sub','order_all',
            'waiting_payment','order_paid','order_canceled','order_all_money','waiting_payment_money','order_paid_money',
            'order_canceled_money','listProduct','totalRevenueProduct'
        ));
    }

    public function productStatistics(Request $request)
    {
        $titlePage = 'Thống kê sản phẩm';
        $page_menu = 'product_statistics';
        $page_sub = null;

        $shopId = Auth::guard('shop')->id();
        $startDate=null;
        $endDate = null;
        if ($request->input('start_date')){
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
        }
        if ($request->input('end_date')){
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
        }

        $topSellingProductsQuery = OrderProductModel::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->whereHas('product', function ($query) use ($shopId) {
                $query->where('shop_id', $shopId);
            });

        $productsInStockQuery = ShopProductModel::select('id', 'name', 'quantity')
            ->where('quantity', '>', 0);

        $productsNotSoldQuery = ShopProductModel::whereNotIn('id', function($query) {
            $query->select('product_id')
                ->from('order_products')
                ->distinct();
        });

        $lowStockProductsQuery = ShopProductModel::where('quantity', '<=', 20);

        if ($startDate && $endDate) {
            $topSellingProductsQuery->whereBetween('created_at', [$startDate, $endDate]);
            $productsInStockQuery->whereBetween('created_at', [$startDate, $endDate]);
            $lowStockProductsQuery->whereBetween('created_at', [$startDate, $endDate]);
            $productsNotSoldQuery->whereHas('orderProducts', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            });
        }

        $topSellingProducts = $topSellingProductsQuery->get()
            ->map(function ($item) {
                $product = ShopProductModel::find($item->product_id);
                $item->product_name = $product->name;
                $item->src = $product->src;
                return $item;
            });

        $productsInStock = $productsInStockQuery->get();

        $lowStockProducts = $lowStockProductsQuery->get();

        $productsNotSold = $productsNotSoldQuery->get();

        return view('shop.statistical.product-shop', compact(
            'titlePage','page_menu','page_sub','topSellingProducts','productsInStock','lowStockProducts','productsNotSold'
        ));
    }

}
