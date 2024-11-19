<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\BannerModel;
use App\Models\FooterModel;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function home()
    {
        $banner = BannerModel::where('display',1)->orderBy('created_at','desc')->get();

        return view('web.home.index',compact('banner'));
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

    public function customerSupport ($slug)
    {
        $data = FooterModel::where('slug',$slug)->first();

        return view('web.post-footer',compact('data'));
    }

}
