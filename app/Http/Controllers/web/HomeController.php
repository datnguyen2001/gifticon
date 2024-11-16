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

    public function promotionToday()
    {
        $title = "Khuyến mãi mới hôm nay";

        return view('web.trademark.list',compact('title'));
    }
    public function youLike ()
    {
        $title = "Có thể bạn cũng thích";

        return view('web.trademark.list',compact('title'));
    }

    public function myVote ()
    {
        return view('web.voucher.index');
    }

}
