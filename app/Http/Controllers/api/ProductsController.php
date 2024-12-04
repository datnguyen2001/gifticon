<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ShopProductModel;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function detailProduct($id)
    {
        try{
            $data = ShopProductModel::where('id',$id)->first();

            return response()->json(['message' => 'Lấy dữ liệu thành công','data'=>$data, 'status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 400]);
        }
    }
}
