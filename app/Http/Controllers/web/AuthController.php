<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\UserVerificationModel;
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

    public function checkPhone()
    {
        return view('web.auth.check_phone');
    }

    public function verifyOtpPhone(Request $request)
    {
        $phone = $request->get('phone');
        $user = User::where('phone',$phone)->first();
        if (!$user){
            return redirect()->back()->with('error','Số điện thoại không tồn tại');
        }

        $otp = random_int(100000, 999999);
        $response = $this->sendZaloOTP($user->phone, $otp);
        if (!$response){
            return redirect()->back()->with(['error' => 'Refresh Token đã hết hạn']);
        }
        $verification = UserVerificationModel::updateOrCreate(
            ['phone' => $phone],
            [
                'otp' => $otp,
                'created_at' => now(),
            ]
        );

        return redirect()->route('phone.otp.verify', ['phone' => $phone]);
    }

    public function showOtpVerifyPhone($phone)
    {
        return view('web.auth.verify_otp_phone',compact('phone'));
    }

    public function verifyOtpPassword(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);
        $phone = $request->get('phone');
        if (!$phone) {
            return redirect()->route('check-phone')->with('error','Số điện thoại không tồn tại');
        }
        $enteredOtp = $request->get('otp');
        $verification = UserVerificationModel::where('phone', $phone)->latest()->first();

        if (!$verification) {
            return redirect()->back()->with('error','Mã OTP không hợp lệ hoặc chưa được gửi');
        }

        $otpCreatedAt = $verification->created_at;
        $expiryTime = now()->diffInMinutes($otpCreatedAt);
        if ($expiryTime > 5) {
            $verification->delete();
            return redirect()->route('check-phone')->with('error','Mã OTP đã hết hạn');
        }

        if ($enteredOtp == $verification->otp) {
            $verification->delete();
            return redirect()->route('forget-password', ['phone' => $phone]);
        }

      return redirect()->back()->with('error','Mã OTP không chính xác');
    }

    public function forgetPassword($phone)
    {
        return view('web.auth.forget_password',compact('phone'));
    }

    public function saveForgetPassword(Request $request)
    {
        $phone = $request->get('phone');
        if (!$phone) {
            return redirect()->route('check-phone')->with('error','Số điện thoại không tồn tại');
        }

        $user = User::where('phone',$phone)->first();

        $request->validate([
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|string|same:password',
        ], [
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'password_confirmation.required' => 'Vui lòng xác nhận mật khẩu mới.',
            'password_confirmation.same' => 'Mật khẩu mới và nhập lại mật khẩu không khớp.',
        ]);

        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return redirect()->route('login')->with('success','Thay đổi mật khẩu thành công');
    }

}
