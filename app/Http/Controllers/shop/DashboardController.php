<?php

namespace App\Http\Controllers\shop;

use App\Http\Controllers\Controller;
use App\Models\CategoryModel;
use App\Models\ShopModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $titlePage = 'Trang chủ';
        $page_menu = 'dashboard';
        $page_sub = null;
        return view('shop.index', compact('titlePage','page_menu','page_sub'));
    }

    public function profile()
    {
        $titlePage = 'Thông tin cá nhân';
        $page_menu = 'profile';
        $page_sub = null;
        $data = Auth::guard('shop')->user();
        $categories = CategoryModel::where('display',1)->get();

        return view('shop.profile.index', compact('titlePage','page_menu','page_sub','data','categories'));
    }

    public function updateProfile(Request $request)
    {
        try {
            $shop = Auth::guard('shop')->user();
            if ($request->hasFile('file')){
                $file = $request->file('file');
                $imagePath = Storage::url($file->store('shop', 'public'));
                if (isset($shop->src) && Storage::exists(str_replace('/storage', 'public', $shop->src))) {
                    Storage::delete(str_replace('/storage', 'public', $shop->src));
                }
                $shop->src = $imagePath;
            }
            $display = $request->get('display') == 'on' ? 1 : 0;
            if (trim($request->input('password')) !== '') {
                $shop->password = Hash::make($request->get('password'));
            }

            $shop->name = $request->get('title');
            $shop->slug = Str::slug($request->get('title'));
            $shop->phone = $request->get('phone');
            $shop->content = $request->get('content');
            $shop->display = $display;
            $shop->save();

            if ($request->has('categories')) {
                $shop->categories()->sync($request->get('categories'));
            } else {
                $shop->categories()->detach();
            }

            return redirect()->route('shop.profile')->with(['success' => 'Cập nhật dữ liệu thành công']);
        } catch (\Exception $e) {
            return back()->with(['error' => $e->getMessage()]);
        }
    }

}
