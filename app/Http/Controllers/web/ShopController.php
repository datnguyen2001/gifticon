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
                ->paginate(20);
        }else{
            $brands = ShopModel::where('display', 1)->orderBy('created_at','desc')->select('name', 'id', 'src', 'slug')->paginate(20);
        }

        return view('web.trademark.index', compact('categories', 'brands','slug'));
    }

    public function promotionToday($slug,Request $request)
    {
        $title = "Khuyến mãi mới hôm nay";
        $categories = CategoryModel::where('display', 1)->orderBy('id', 'asc')->get();
        if ($slug != 'all') {
            $dataCate = CategoryModel::where('slug', $slug)->first();
            $query = ShopProductModel::where('display', 1)->where('category_id',$dataCate->id)
                ->select('id', 'name', 'src', 'price', 'slug', 'category_id');
        }else{
            $query = ShopProductModel::where('display', 1)
                ->select('id', 'name', 'src', 'price', 'slug', 'category_id');
        }

        if (!empty($request->input('start_price'))) {
            $startPrice = (int) $request->input('start_price');
            $query->where('price', '>=', $startPrice);
        }

        if (!empty($request->input('end_price'))) {
            $endPrice = (int) $request->input('end_price');
            $query->where('price', '<=', $endPrice);
        }

        if (!empty($request->input('product_name'))) {
            $productName = strtolower($request->input('product_name'));
            $query->whereRaw('LOWER(name) LIKE ?', ["%$productName%"]);
        }

        $products = $query->paginate(24);

        return view('web.trademark.list', compact('title', 'categories', 'products','slug'));
    }

    public function youLike ($slug, Request $request)
    {
        $title = "Có thể bạn cũng thích";

        $categories = CategoryModel::where('display', 1)->orderBy('id', 'asc')->get();

        if ($slug != 'all') {
            $dataCate = CategoryModel::where('slug', $slug)->first();
            $query = ShopProductModel::where('display', 1)->where('category_id',$dataCate->id)
                ->select('id', 'name', 'src', 'price', 'slug', 'category_id');
        }else{
            $query = ShopProductModel::where('display', 1)
                ->select('id', 'name', 'src', 'price', 'slug', 'category_id')->inRandomOrder();
        }

        if (!empty($request->input('start_price'))) {
            $startPrice = (int) $request->input('start_price');
            $query->where('price', '>=', $startPrice);
        }

        if (!empty($request->input('end_price'))) {
            $endPrice = (int) $request->input('end_price');
            $query->where('price', '<=', $endPrice);
        }

        if (!empty($request->input('product_name'))) {
            $productName = strtolower($request->input('product_name'));
            $query->whereRaw('LOWER(name) LIKE ?', ["%$productName%"]);
        }

        $products = $query->paginate(24);

        return view('web.trademark.list', compact('title', 'categories', 'products','slug'));
    }

    public function productNew ($slug, Request $request)
    {
        $title = "Sản phẩm mới";

        $categories = CategoryModel::where('display', 1)->orderBy('id', 'asc')->get();

        if ($slug != 'all') {
            $dataCate = CategoryModel::where('slug', $slug)->first();
            $query = ShopProductModel::where('display', 1)->where('category_id',$dataCate->id)
                ->select('id', 'name', 'src', 'price', 'slug', 'category_id');
        }else{
            $query = ShopProductModel::where('display', 1)
                ->select('id', 'name', 'src', 'price', 'slug', 'category_id')->inRandomOrder();
        }

        if (!empty($request->input('start_price'))) {
            $startPrice = (int) $request->input('start_price');
            $query->where('price', '>=', $startPrice);
        }

        if (!empty($request->input('end_price'))) {
            $endPrice = (int) $request->input('end_price');
            $query->where('price', '<=', $endPrice);
        }

        if (!empty($request->input('product_name'))) {
            $productName = strtolower($request->input('product_name'));
            $query->whereRaw('LOWER(name) LIKE ?', ["%$productName%"]);
        }

        $products = $query->orderBy('created_at','desc')->paginate(24);

        return view('web.trademark.list', compact('title', 'categories', 'products','slug'));
    }
}
