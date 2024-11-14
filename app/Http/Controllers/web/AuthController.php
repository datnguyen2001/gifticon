<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login()
    {
        return view('web.auth.login');
    }

    public function loginSubmit(LoginRequest $request)
    {
        $credentials = $request->only('phone', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return redirect()->back()->withErrors([
                    'password' => 'Mật khẩu không chính xác.',
                ])->withInput($request->except('password'));
            }
        } catch (JWTException $e) {
            return redirect()->back()->withErrors([
                'error' => 'Đăng nhập thất bại, vui lòng thử lại sau.',
            ])->withInput($request->except('password'));
        }

        $user = auth()->user();
        $user->token = $token;
        $user->save();

        session(['jwt_token' => $token]);

        return redirect()->intended('/')->with([
            'success' => 'Đăng nhập thành công!',
        ]);
    }

    public function register()
    {
        return view('web.auth.register');
    }

    public function registerSubmit(RegisterRequest $request)
    {
        $user = new User();
        $user->phone = $request->phone;
        $user->full_name = $request->full_name;
        $user->password = Hash::make($request->password);

        $user->save();

        return redirect()->route('login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }
}
