<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\ShopProductLocationModel;
use App\Models\ShopProductModel;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function detail($slug)
    {
        $product = ShopProductModel::where('slug', $slug)->first();
        $productLocations = ShopProductLocationModel::where('product_id', $product->id)->get();

        return view('web.product.detail', compact('product', 'productLocations'));
    }
}
