<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\BannerModel;
use App\Models\CategoryModel;
use App\Models\FavoritesModel;
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
        $likeProducts = ShopProductModel::where('display', 1)->select('id', 'name', 'src', 'price', 'slug')->inRandomOrder()->limit(24)->get();
        $categories = CategoryModel::where('display', 1)->orderBy('id', 'asc')->limit(5)->get();
        $categoryProducts = [];
        foreach ($categories as $category) {
            $products = ShopProductModel::where('display', 1)
                ->where('category_id', $category->id)
                ->select('id', 'name', 'src', 'price', 'slug', 'category_id')
                ->inRandomOrder()
                ->limit(8)
                ->get();
            $categoryProducts[$category->id] = $products;
        }

        return view('web.home.index', compact('banner','shop', 'saleProducts', 'categoryProducts', 'likeProducts', 'categories'));
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

    public function cart ()
    {
        return view('web.cart.index');
    }

    public function toggleFavorite($productId)
    {
        $user = session('jwt_token') ? \Tymon\JWTAuth\Facades\JWTAuth::setToken(session('jwt_token'))->authenticate() : null;

        if (!$user) {
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'Vui lòng đăng nhập để tiếp tục'], 401);
            }
        }

        $favorite = FavoritesModel::where('user_id', $user->id)->where('product_id', $productId)->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['status' => 'removed']);
        } else {
            FavoritesModel::create(['user_id' => $user->id, 'product_id' => $productId]);
            return response()->json(['status' => 'added']);
        }
    }

}
