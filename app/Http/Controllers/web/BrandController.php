<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\ShopModel;
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

        return view('web.brand.detail',compact('data'));
    }
}
