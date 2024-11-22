<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\CategoryModel;
use App\Models\ShopModel;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function trademark()
    {
        $categories = CategoryModel::where('display', 1)->get();
        $brands = ShopModel::where('display', 1)->select('name', 'id', 'src', 'slug')->get();
        return view('web.trademark.index', compact('categories', 'brands'));
    }
}
