<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\CartModel;
use App\Models\CartReceiverModel;
use App\Models\OrderModel;
use App\Models\OrderReceiverModel;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class PaymentController extends Controller
{
    public function index (Request $request)
    {
        $user = JWTAuth::user();
        $cartBuyNow = CartModel::where('user_id', $user->id)
            ->where('type', 2)
            ->first();

        if ($cartBuyNow) {
            if($cartBuyNow->buy_for == '2'){
                CartReceiverModel::where('cart_id', $cartBuyNow->id)->delete();
            }
            $cartBuyNow->delete();
        }

        $carts = CartModel::where('user_id', $user->id)
            ->where('is_selected', true)
            ->with('product:id,src,name,start_date,end_date');

        if ($request->session()->previousUrl() == route('cart.index')) {
            $carts = $carts->where('type', 1);
        } elseif ($request->session()->previousUrl() == route('create-order.buy-now.index')) {
            $carts = $carts->where('type', 2);
        }

        $carts = $carts->get();

        $totalPayment = $carts->sum('total_price');

        return view('web.order.index', compact('carts', 'user', 'totalPayment'));
    }
}
