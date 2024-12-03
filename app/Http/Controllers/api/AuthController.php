<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserVerificationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function registerSendOTP(Request $request)
    {
        $phone = $request->get('phone');
        $existingUser = User::where('phone', $phone)->first();

        if ($existingUser) {
            return response()->json(['message' => 'SĐT này đã được đăng ký', 'status' => false]);
        }

        $otp = random_int(100000, 999999);

        $verification = UserVerificationModel::updateOrCreate(
            ['phone' => $phone],
            [
                'otp' => $otp,
                'created_at' => now(),
            ]
        );

//        $zaloResponse = $this->sendZaloOTP($phone, $otp);
//
//        if (!$zaloResponse){
//            return response()->json(['message' => 'Refresh Token Zalo đã hết hạn', 'status' => false]);
//        }

        return response()->json(['message' => 'Gửi mã xác nhận thành công', 'status' => true]);
    }

    public function registerVerifyOTP(Request $request)
    {
        $enteredOtp = $request->get('otp');
        $phone = $request->get('phone');

        $verification = UserVerificationModel::where('phone', $phone)->latest()->first();

        if (!$verification) {
            return response()->json(['message' => 'Mã OTP không hợp lệ hoặc chưa được gửi', 'status' => false]);
        }

        $otpCreatedAt = $verification->created_at;
        $expiryTime = now()->diffInMinutes($otpCreatedAt);
        if ($expiryTime > 5) {
            $verification->delete();
            return response()->json(['message' => 'Mã OTP đã hết hạn', 'status' => false]);
        }

        if ($enteredOtp == $verification->otp) {
            $verification->delete();

            return response()->json(['message' => 'Xác thực thành công', 'status' => true]);
        }

        return response()->json(['message' => 'Mã OTP không chính xác', 'status' => false]);
    }

    public function registerCreatePassword(Request $request)
    {
        $phone = $request->get('phone');
        $password = $request->get('password');
        $passwordConfirm = $request->get('password_confirmation');

        if ($password !== $passwordConfirm) {
            return response()->json(['message' => 'Mật khẩu xác nhận không khớp', 'status' => false]);
        }

        $hashedPassword = Hash::make($password);

        $user = User::create([
            'phone' => $phone,
            'password' => $hashedPassword,
        ]);

        return response()->json(['message' => 'Tạo mật khẩu thành công', 'status' => true]);
    }

    public function registerCreateProfile(Request $request)
    {
        $phone = $request->get('phone');
        $fullName = $request->get('full_name');
        $email = $request->get('email');
        $dob = $request->get('date_of_birth');

        $user = User::where('phone', $phone)->first();
        if (!$user) {
            return response()->json(['message' => 'Người dùng không tồn tại', 'status' => false]);
        }

        $user->update([
            'full_name' => $fullName,
            'email' => $email,
            'date_of_birth' => $dob,
        ]);

        return response()->json(['message' => 'Cập nhật hồ sơ thành công', 'status' => true]);
    }

    public function loginCheckPhone(Request $request)
    {
        $phone = $request->get('phone');

        $user = User::where('phone', $phone)->first();
        if (!$user) {
            return response()->json(['message' => 'Người dùng không tồn tại', 'status' => false]);
        }

        return response()->json(['message' => 'Số điện thoại tồn tại, vui lòng tiếp tục', 'status' => true]);
    }

    public function loginSubmit(Request $request)
    {
        $credentials = $request->only('phone', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'message' => 'Mật khẩu không chính xác.',
                    'status' => false,
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Đăng nhập thất bại, vui lòng thử lại sau.',
                'status' => false,
            ], 500);
        }

        $user = JWTAuth::user();
        $user->token = $token;
        $user->save();

        return response()->json([
            'message' => 'Đăng nhập thành công!',
            'status' => true,
            'token' => $token,
            'user' => $user,
        ], 200);
    }
}
