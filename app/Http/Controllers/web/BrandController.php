<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\ShopModel;
use App\Models\ShopProductModel;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function detail($slug)
    {
        $data = ShopModel::where('slug',$slug)->where('display',1)->first();
        if (!$data){
            toastr()->error('Cửa hàng không tồn tại');
            return back();
        }
        $saleProducts = ShopProductModel::where('shop_id', $data->id)->where('display', 1)->inRandomOrder()->limit(6)->get();
        $shopProducts = ShopProductModel::where('shop_id', $data->id)->where('display', 1)->inRandomOrder()->limit(24)->get();

        return view('web.brand.detail',compact('data', 'saleProducts', 'shopProducts'));
    }
}
