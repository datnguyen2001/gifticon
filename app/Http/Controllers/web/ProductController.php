<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\MemberShipModel;
use App\Models\ShopProductLocationModel;
use App\Models\ShopProductModel;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProductController extends Controller
{
    public function detail($slug)
    {
        $product = ShopProductModel::where('slug', $slug)->first();
        $productLocations = ShopProductLocationModel::where('product_id', $product->id)->get();
        $memberShip = MemberShipModel::get();

        return view('web.product.detail', compact('product', 'productLocations', 'memberShip'));
    }
}
