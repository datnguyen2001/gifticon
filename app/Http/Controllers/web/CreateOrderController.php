<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateOrderController extends Controller
{
    public function index()
    {
        $user = JWTAuth::user();

        return view('web.create-order.index', compact('user'));
    }
}
