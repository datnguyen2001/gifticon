<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SettingModel;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $titlePage = 'Cài đặt chung';
        $page_menu = 'setting';
        $page_sub = null;
        $data = SettingModel::first();

        return view('admin.setting.index',compact('titlePage','page_menu','page_sub','data'));
    }

    public function save(Request $request){
        $setting = SettingModel::first();
        if ($setting){
            $setting->describe = $request->get('describe');
            $setting->phone = $request->get('phone');
            $setting->address = $request->get('address');
            $setting->email = $request->get('email');
            $setting->facebook = $request->get('facebook');
            $setting->twitter = $request->get('twitter');
            $setting->zalo = $request->get('zalo');
            $setting->save();
        }else{
            $setting = new SettingModel([
                'describe'=>$request->get('describe'),
                'phone'=>$request->get('phone'),
                'address'=>$request->get('address'),
                'facebook'=>$request->get('facebook'),
                'email'=>$request->get('email'),
                'twitter'=>$request->get('twitter'),
                'zalo'=>$request->get('zalo'),
            ]);
            $setting->save();
        }

        return redirect()->back()->with(['success'=>"Lưu thông tin thành công"]);
    }
}
