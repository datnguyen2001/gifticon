<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\BannerModel;
use App\Models\CategoryModel;
use App\Models\FavoritesModel;
use App\Models\FooterModel;
use App\Models\ProductReviewModel;
use App\Models\ShopModel;
use App\Models\ShopProductModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

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

    public function homeNew()
    {
        $banner = BannerModel::where('display', 1)->orderBy('created_at', 'desc')->get();
        $shop = ShopModel::where('display', 1)->limit(15)->get();
        $popularProducts = ShopProductModel::where('display', 1)
            ->where(function($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', Carbon::now());
            })
            ->where(function($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', Carbon::now());
            })->where('quantity','>',0)
            ->select('id', 'name', 'src', 'price', 'slug')
            ->inRandomOrder()
            ->limit(10)
            ->get();

        $plummetedProducts = ShopProductModel::where('display', 1)
            ->where(function($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', Carbon::now());
            })
            ->where(function($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', Carbon::now());
            })->where('quantity','>',0)
            ->select('id', 'name', 'src', 'price', 'slug')
            ->inRandomOrder()
            ->limit(10)
            ->get();

        $proposeProducts = ShopProductModel::where('display', 1)
            ->where(function($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', Carbon::now());
            })
            ->where(function($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', Carbon::now());
            })->where('quantity','>',0)
            ->select('id', 'name', 'src', 'price', 'slug','shop_id')
            ->with(['shop:id,src'])
            ->inRandomOrder()
            ->limit(10)
            ->get();
        $categories = CategoryModel::where('display', 1)->orderBy('id', 'asc')->get();
        $shops = ShopModel::select('id', 'name', 'src')
        ->with(['products'])
        ->inRandomOrder()
            ->limit(3)
            ->get();
        $newProducts = ShopProductModel::where('display', 1)
            ->where(function($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', Carbon::now());
            })
            ->where(function($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', Carbon::now());
            })->where('quantity','>',0)
            ->select('id', 'name', 'src', 'price', 'slug')
            ->orderBy('created_at','desc')
            ->inRandomOrder()
            ->limit(10)
            ->get();

        return view('web.home.index-new', compact('banner','shop', 'popularProducts','plummetedProducts', 'categories',
        'proposeProducts','shops','newProducts'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|min:1',
        ]);

        $keyword = $request->input('keyword');

        $shops = ShopModel::where('name', 'like', '%' . $keyword . '%')
            ->where('display', 1)
            ->select('id', 'name', 'slug', 'src')
            ->get();

        $products = ShopProductModel::where('name', 'like', '%' . $keyword . '%')
            ->where('display', 1)
            ->select('id', 'name', 'slug', 'price', 'src')
            ->get();

        return response()->json([
            'shops' => $shops,
            'products' => $products,
        ]);
    }

    public function customerSupport ($slug)
    {
        $data = FooterModel::where('slug',$slug)->first();

        return view('web.post-footer',compact('data'));
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
