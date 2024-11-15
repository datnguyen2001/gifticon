<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function home()
    {
        return view('web.home.index');
    }

    public function trademark()
    {
        return view('web.trademark.index');
    }
}
