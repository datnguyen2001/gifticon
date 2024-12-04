<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\BannerModel;
use App\Models\CategoryModel;
use App\Models\NotificationModel;
use App\Models\OrderModel;
use App\Models\OrderProductModel;
use App\Models\ShopModel;
use App\Models\ShopProductModel;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class HomeController extends Controller
{
    public function banner()
    {
        try {
            $data = BannerModel::where('display', 1)->orderBy('created_at', 'desc')->get();

            return response()->json(['message' => 'Lấy dữ liệu thành công','data'=>$data, 'status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 400]);
        }
    }

    public function saleToday()
    {
        try{
            $data = ShopProductModel::where('display', 1)->select('id', 'name', 'src', 'price', 'slug')->inRandomOrder()->get();

            return response()->json(['message' => 'Lấy dữ liệu thành công','data'=>$data, 'status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 400]);
        }
    }

    public function trademark()
    {
        try{
            $data = ShopModel::where('display', 1)->get();

            return response()->json(['message' => 'Lấy dữ liệu thành công','data'=>$data, 'status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 400]);
        }
    }

    public function category()
    {
        try{
            $data = CategoryModel::where('display', 1)->orderBy('id', 'asc')->get();

            return response()->json(['message' => 'Lấy dữ liệu thành công','data'=>$data, 'status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 400]);
        }
    }

    public function mayLike()
    {
        try{
            $data = ShopProductModel::where('display', 1)->select('id', 'name', 'src', 'price', 'slug')->inRandomOrder()->get();

            return response()->json(['message' => 'Lấy dữ liệu thành công','data'=>$data, 'status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 400]);
        }
    }

    public function search(Request $request)
    {
        try{
            $keyword = $request->input('keyword');

            $shops = ShopModel::where('name', 'like', '%' . $keyword . '%')
                ->where('display', 1)
                ->select('id', 'name', 'slug', 'src')
                ->get();

            $products = ShopProductModel::where('name', 'like', '%' . $keyword . '%')
                ->where('display', 1)
                ->select('id', 'name', 'slug', 'price', 'src')
                ->get();

            $data = [
                'shops' => $shops,
                'products' => $products,
            ];

            return response()->json(['message' => 'Lấy dữ liệu thành công','data'=>$data, 'status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 400]);
        }
    }

    public function notification()
    {
        try {
            $data = NotificationModel::where('receiver_id',JWTAuth::user()->id)->orderBy('created_at','desc')->get();
            foreach ($data as $item){
                $shop = ShopModel::find($item->sender_id);
                $item->src = $shop->src??null;
            }

            return response()->json(['message' => 'Lấy dữ liệu thành công','data'=>$data, 'status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 400]);
        }
    }

    public function readNotification($id)
    {
        try {
            $data = NotificationModel::find($id);
            $data->is_read = 1;
            $data->save();

            return response()->json(['message' => 'Lấy dữ liệu thành công','data'=>$data, 'status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 400]);
        }
    }


}
