<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\OrderModel;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class MyVoteController extends Controller
{
    public function myVote ($slug)
    {
        $user = JWTAuth::user();
        $orders = OrderModel::where('user_id', $user->id)->get();
        dd($orders);
        return view('web.voucher.index',compact('slug'));
    }

    public function detailMyVote ()
    {
        return view('web.voucher.detail');
    }
}
