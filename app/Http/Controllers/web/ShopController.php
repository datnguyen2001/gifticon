<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\CategoryModel;
use App\Models\ShopModel;
use App\Models\ShopProductModel;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function trademark($slug)
    {
        $categories = CategoryModel::where('display', 1)->get();
        if ($slug != 'all'){
            $brands = ShopModel::where('display', 1)
                ->whereHas('categories', function ($query) use ($slug) {
                    $query->where('slug', $slug);
                })
                ->select('name', 'id', 'src', 'slug')
                ->orderBy('created_at', 'desc')
                ->get();
        }else{
            $brands = ShopModel::where('display', 1)->orderBy('created_at','desc')->select('name', 'id', 'src', 'slug')->get();
        }

        return view('web.trademark.index', compact('categories', 'brands','slug'));
    }

    public function promotionToday(Request $request)
    {
        $title = "Khuyến mãi mới hôm nay";
        $categories = CategoryModel::where('display', 1)->orderBy('id', 'asc')->get();

        // Fetch all products for all categories
        $products = ShopProductModel::where('display', 1)
            ->select('id', 'name', 'src', 'price', 'slug', 'category_id')
            ->get();

        return view('web.trademark.list', compact('title', 'categories', 'products'));
    }

    public function youLike ()
    {
        $title = "Có thể bạn cũng thích";

        $categories = CategoryModel::where('display', 1)->orderBy('id', 'asc')->get();

        // Fetch all products for all categories
        $products = ShopProductModel::where('display', 1)
            ->select('id', 'name', 'src', 'price', 'slug', 'category_id')
            ->get();

        return view('web.trademark.list', compact('title', 'categories', 'products'));
    }
}
