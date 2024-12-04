<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ShopModel;
use App\Models\ShopProductModel;
use Illuminate\Http\Request;

class TrademarkController extends Controller
{
    public function filterTrademark($id)
    {
        try{
            $data = ShopModel::where('display', 1)
                ->whereHas('categories', function ($query) use ($id) {
                    $query->where('category_id', $id);
                })
                ->select('name', 'id', 'src', 'slug')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json(['message' => 'Lấy dữ liệu thành công','data'=>$data, 'status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 400]);
        }
    }

    public function detailTrademark($id)
    {
        try{
            $shop = ShopModel::where('id',$id)->where('display',1)->first();
            if (!$shop){
                return response()->json(['message' => 'Sản phẩm không tồn tại', 'status' => 400]);
            }
            $products = ShopProductModel::where('shop_id', $shop->id)->where('display', 1)->get();

            $data = [
                'shop' => $shop,
                'products' => $products,
            ];

            return response()->json(['message' => 'Lấy dữ liệu thành công','data'=>$data, 'status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 400]);
        }
    }
}
