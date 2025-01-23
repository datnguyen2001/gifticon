<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\OrderProductModel;
use App\Models\ShopProductModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    public function index()
    {
        return view('web.barcode.index');
    }

    public function scanBarcode(Request $request)
    {
        $barcode = $request->barcode;
        $product = OrderProductModel::with('locations')
            ->where('order_products.voucher_id', $barcode)
            ->join('shop_products', 'shop_products.id', '=', 'order_products.product_id')
            ->select(
                'order_products.id as order_product_id',
                'order_products.product_id',
                'order_products.barcode',
                'order_products.voucher_id',
                'order_products.start_date as product_start_date',
                'order_products.end_date as product_end_date',
                'order_products.quantity',
                'shop_products.name as product_name',
                'shop_products.src as product_src'
            )
            ->first();

        if ($product) {
            $currentDate = Carbon::now();
            $startDate = Carbon::createFromFormat('Y-m-d', $product->product_start_date);
            $endDate = Carbon::createFromFormat('Y-m-d', $product->product_end_date);

            $html = view('web.barcode.product', compact('product'))->render();

            if ($currentDate->lt($startDate) || $currentDate->gt($endDate)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Voucher hết hạn sử dụng',
                    'html' => $html
                ]);
            }

            if ($product->quantity == 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Voucher đã hết',
                    'html' => $html
                ]);
            }

            // Update the quantity
            $productToUpdate = OrderProductModel::find($product->order_product_id);
            $productToUpdate->quantity -= 1;
            $productToUpdate->save();

            $product->locations = $product->locations->pluck('location')->toArray();

            return response()->json([
                'status' => 'success',
                'html' => $html,
                'message' => 'Sử dụng voucher thành công'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Không tìm thấy sản phẩm'
        ]);
    }
}
