<?php

namespace App\Http\Controllers\shop;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use App\Models\ShopModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login ()
    {
        $title = 'Shop';
        return view('shop.login', compact('title'));
    }

    public function doLogin (Request $request)
    {
        $bodyData = $request->all();
        $check = ShopModel::where('phone', $bodyData['username'])
            ->exists();
        if (!$check) {
            toastr()->error('Số điện thoại không tồn tại');
            return redirect()->route('shop.login');
        }
        $dataAttemptAdmin = [
            'phone' => $bodyData['username'],
            'password' => $bodyData['password'],
        ];
        if (Auth::guard('shop')->attempt($dataAttemptAdmin)) {
            return redirect()->route('shop.index');
        }
        toastr()->error('Tài khoản hoặc mật khẩu không chính xác');
        return redirect()->route('shop.login');
    }

    public function logout()
    {
        Auth::guard('shop')->logout();
        toastr()->success('Đăng xuất thành công');
        return redirect()
            ->route('shop.login');
    }

}
