<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ShopModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    public function index()
    {
        $titlePage = 'Danh sách shop';
        $page_menu = 'shop';
        $page_sub = null;
        $listData = ShopModel::orderBy('created_at', 'desc')->paginate(20);

        return view('admin.shop.index', compact('titlePage', 'page_menu', 'page_sub', 'listData'));
    }

    public function create()
    {
        try {
            $titlePage = 'Thêm shop';
            $page_menu = 'shop';
            $page_sub = null;

            return view('admin.shop.create', compact('titlePage', 'page_menu', 'page_sub'));
        } catch (\Exception $e) {
            return back()->with(['error' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $imagePath = null;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $imagePath = Storage::url($file->store('shop', 'public'));
            }
            if ($request->get('display') == 'on'){
                $display = 1;
            }else{
                $display = 0;
            }

            $shop = new ShopModel([
                'name' => $request->get('title'),
                'slug' => Str::slug($request->get('title')),
                'phone'=> $request->get('phone'),
                'password'=>$request->get('password'),
                'content'=>$request->get('content'),
                'src'=>$imagePath,
                'display' => $display,
            ]);
            $shop->save();

            return redirect()->route('admin.shop.index')->with(['success' => 'Tạo dữ liệu thành công']);
        } catch (\Exception $exception) {
            return back()->with(['error' => $exception->getMessage()]);
        }
    }

    public function delete($id)
    {
        $shop = ShopModel::find($id);
        if (isset($shop->src) && Storage::exists(str_replace('/storage', 'public', $shop->src))) {
            Storage::delete(str_replace('/storage', 'public', $shop->src));
        }
        $shop->delete();

        return redirect()->route('admin.shop.index')->with(['success'=>"Xóa dữ liệu thành công"]);
    }

    public function edit($id)
    {
        try {
            $data = ShopModel::find($id);
            if (empty($data)) {
                return back()->with(['error' => 'Dữ liệu không tồn tại']);
            }
            $titlePage = 'Cập nhật shop';
            $page_menu = 'shop';
            $page_sub = null;

            return view('admin.shop.edit', compact('titlePage', 'page_menu', 'page_sub', 'data'));
        } catch (\Exception $exception) {
            return back()->with(['error' => $exception->getMessage()]);
        }
    }

    public function update($id, Request $request)
    {
        try {
            $shop = ShopModel::find($id);
            if ($request->hasFile('file')){
                $file = $request->file('file');
                $imagePath = Storage::url($file->store('shop', 'public'));
                if (isset($shop->src) && Storage::exists(str_replace('/storage', 'public', $shop->src))) {
                    Storage::delete(str_replace('/storage', 'public', $shop->src));
                }
                $shop->src = $imagePath;
            }
            if ($request->get('display') == 'on'){
                $display = 1;
            }else{
                $display = 0;
            }

            $shop->name = $request->get('title');
            $shop->slug = Str::slug($request->get('title'));
            $shop->phone = $request->get('phone');
            $shop->content = $request->get('content');
            $shop->password = $request->get('password')??$shop->password;
            $shop->display = $display;
            $shop->save();

            return redirect()->route('admin.shop.index')->with(['success' => 'Cập nhật dữ liệu thành công']);
        } catch (\Exception $e) {
            return back()->with(['error' => $e->getMessage()]);
        }
    }
}