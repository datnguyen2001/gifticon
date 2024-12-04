<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\OrderModel;
use App\Models\OrderProductModel;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class VoucherController extends Controller
{
    public function myVoucher()
    {
        try{
            $user = JWTAuth::user();
            $data = OrderModel::where('user_id', $user->id)
                ->join('order_products', 'order_products.order_id', '=', 'orders.id')
                ->join('shop_products', 'shop_products.id', '=', 'order_products.product_id')
                ->join('shops', 'shops.id', '=', 'order_products.shop_id')
                ->select(
                    'order_products.id as order_product_id',
                    'order_products.buy_for',
                    'order_products.unit_price',
                    'order_products.quantity',
                    'order_products.receiver_phone',
                    'shop_products.name as product_name',
                    'shop_products.start_date as product_start_date',
                    'shop_products.end_date as product_end_date',
                    'shops.src as shop_src'
                )->where('order_products.buy_for', 1)->get();

            return response()->json(['message' => 'Lấy dữ liệu thành công','data'=>$data, 'status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 400]);
        }
    }

    public function voucherGiven()
    {
        try{
            $user = JWTAuth::user();
            $data = OrderModel::where('user_id', $user->id)
                ->join('order_products', 'order_products.order_id', '=', 'orders.id')
                ->join('shop_products', 'shop_products.id', '=', 'order_products.product_id')
                ->join('shops', 'shops.id', '=', 'order_products.shop_id')
                ->select(
                    'order_products.id as order_product_id',
                    'order_products.buy_for',
                    'order_products.unit_price',
                    'order_products.quantity',
                    'order_products.receiver_phone',
                    'shop_products.name as product_name',
                    'shop_products.start_date as product_start_date',
                    'shop_products.end_date as product_end_date',
                    'shops.src as shop_src'
                )->where('order_products.buy_for', 2)->get();

            return response()->json(['message' => 'Lấy dữ liệu thành công','data'=>$data, 'status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 400]);
        }
    }

    public function voucherInfo($id)
    {
        try{
            $orderProducts = OrderProductModel::with('locations')
                ->where('order_products.id', $id)
                ->join('shop_products', 'shop_products.id', '=', 'order_products.product_id')
                ->join('shops', 'shops.id', '=', 'order_products.shop_id')
                ->select(
                    'order_products.id as order_product_id',
                    'order_products.product_id',
                    'order_products.unit_price as product_price',
                    'order_products.quantity',
                    'order_products.buy_for',
                    'shop_products.name as product_name',
                    'shop_products.describe as product_describe',
                    'shop_products.guide as product_guide',
                    'shops.name as shop_name'
                )
                ->get();

            $orderProducts->transform(function ($orderProduct) {
                $orderProduct->locations = $orderProduct->locations->pluck('location')->toArray();
                return $orderProduct;
            });
            $data = $orderProducts->first();

            return response()->json(['message' => 'Lấy dữ liệu thành công','data'=>$data, 'status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 400]);
        }
    }

    public function detailVoucher($id)
    {
        try{
            $vouchers = OrderProductModel::with('locations')
                ->where('order_products.id', $id)
                ->join('shop_products', 'shop_products.id', '=', 'order_products.product_id')
                ->select(
                    'order_products.id as order_product_id',
                    'order_products.product_id',
                    'order_products.barcode',
                    'shop_products.name as product_name',
                    'shop_products.src as product_src',
            )
                ->get();
            $vouchers->transform(function ($voucher) {
                $voucher->locations = $voucher->locations->pluck('location')->toArray();
                return $voucher;
            });
            $data = $vouchers->first();

            return response()->json(['message' => 'Lấy dữ liệu thành công','data'=>$data, 'status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 400]);
        }
    }

}
