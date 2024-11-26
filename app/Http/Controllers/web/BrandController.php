<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\ShopModel;
use App\Models\ShopProductModel;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function detail($slug,Request $request)
    {
        $data = ShopModel::where('slug',$slug)->where('display',1)->first();
        if (!$data){
            toastr()->error('Cửa hàng không tồn tại');
            return back();
        }
        $saleProducts = ShopProductModel::where('shop_id', $data->id)->where('display', 1)->inRandomOrder()->limit(6)->get();

        $query = ShopProductModel::where('shop_id', $data->id)->where('display', 1);

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

        $shopProducts = $query->inRandomOrder()->paginate(24);

        return view('web.brand.detail',compact('data', 'saleProducts', 'shopProducts'));
    }
}
