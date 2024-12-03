<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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

        session([
            'user_data' => [
                'phone' => $user->phone,
                'full_name' => $user->full_name,
                'password' => $user->password,
            ],
        ]);

        $otp = random_int(100000, 999999);

        session([
            'otp_code' => $otp,
            'otp_created_at' => now(),
        ]);

        $response = $this->sendZaloOTP($user->phone, $otp);
        if (!$response){
            return redirect()->back()->with(['error' => 'Refresh Token đã hết hạn']);
        }

        return redirect()->route('otp.verify')->with('success', 'Vui lòng kiểm tra tin nhắn Zalo để nhận mã OTP và xác thực.');
    }

    public function showOtpVerify()
    {
        $otpCreatedAt = session('otp_created_at');

        if (!$otpCreatedAt || now()->diffInMinutes($otpCreatedAt) > 5) {
            session()->forget(['user_data', 'otp_code', 'otp_created_at']);

            return redirect()->route('register')->with('error', 'OTP đã hết hạn. Vui lòng đăng ký lại.');
        }

        return view('web.auth.verify_otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        if ($request->otp != session('otp_code')) {
            return redirect()->back()->with('otp', 'Mã OTP không chính xác.');
        }

        $userData = session('user_data');

        $user = new User();
        $user->phone = $userData['phone'];
        $user->full_name = $userData['full_name'];
        $user->password = $userData['password'];
        $user->save();

        session()->forget(['user_data', 'otp_code', 'otp_created_at']);

        return redirect()->route('login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }

}
