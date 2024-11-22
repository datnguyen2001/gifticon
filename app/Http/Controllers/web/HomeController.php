<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\BannerModel;
use App\Models\FooterModel;
use App\Models\ShopModel;
use App\Models\ShopProductModel;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function home()
    {
        $banner = BannerModel::where('display', 1)->orderBy('created_at', 'desc')->get();
        $shop = ShopModel::where('display', 1)->limit(15)->get();
        $saleProducts = ShopProductModel::where('display', 1)->select('id', 'name', 'src', 'price', 'slug')->inRandomOrder()->limit(6)->get();
        $popularProducts = ShopProductModel::where('display', 1)->select('id', 'name', 'src', 'price', 'slug')->inRandomOrder()->limit(8)->get();
        $likeProducts = ShopProductModel::where('display', 1)->select('id', 'name', 'src', 'price', 'slug')->inRandomOrder()->limit(24)->get();

        return view('web.home.index', compact('banner','shop', 'saleProducts', 'popularProducts', 'likeProducts'));
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

    public function detailMyVote ()
    {
        return view('web.voucher.detail');
    }

    public function voucher ()
    {
        return view('web.voucher.item');
    }

    public function customerSupport ($slug)
    {
        $data = FooterModel::where('slug',$slug)->first();

        return view('web.post-footer',compact('data'));
    }

    public function order ()
    {
        return view('web.order.index');
    }

}
