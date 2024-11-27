<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\OrderModel;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class MyVoteController extends Controller
{
    public function myVote($slug)
    {
        $user = JWTAuth::user();

        $query = OrderModel::where('user_id', $user->id)
            ->join('order_products', 'order_products.order_id', '=', 'orders.id')
            ->select(
                'orders.id as order_id',
                'orders.order_code',
                'orders.total_price',
                'orders.status_id',
                'order_products.product_id',
                'order_products.id as order_product_id',
                'order_products.buy_for',
                'order_products.barcode'
            );

        if ($slug == 'cua-toi') {
            $query->where('order_products.buy_for', 1);
        } elseif ($slug == 'da-tang') {
            $query->where('order_products.buy_for', 2);
        }

        $orders = $query->get();

        return view('web.voucher.index', compact('slug', 'orders'));
    }


    public function detailMyVote ()
    {
        return view('web.voucher.detail');
    }
}
