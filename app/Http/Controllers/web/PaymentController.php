<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\CartModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class PaymentController extends Controller
{
    public function index (Request $request)
    {
        $user = JWTAuth::user();

        $carts = CartModel::where('user_id', $user->id)
            ->where('is_selected', true)
            ->with('product:id,src,name,start_date,end_date');

        $previousUrl = $request->session()->previousUrl();
        if ($previousUrl == route('cart.index')) {
            $carts = $carts->where('type', 1);
        } elseif (Str::contains($previousUrl, '/mua-ngay/tao-don-hang')) {
            $carts = $carts->where('type', 2);
        }

        $carts = $carts->get();

        $totalPayment = $carts->sum('total_price');

        return view('web.order.index', compact('carts', 'user', 'totalPayment'));
    }
}
