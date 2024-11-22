<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\CategoryModel;
use App\Models\ShopModel;
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
}
