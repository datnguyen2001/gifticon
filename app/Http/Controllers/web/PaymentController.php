<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\CartModel;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class PaymentController extends Controller
{
    public function index ()
    {
        $user = JWTAuth::user();
        $carts = CartModel::where('user_id', $user->id)
            ->where('is_selected', true)
            ->with('product:id,src,name,start_date,end_date')->get();

        $totalPayment = $carts->sum('total_price');

        return view('web.order.index', compact('carts', 'user', 'totalPayment'));
    }
}
