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
            return response()->json(['message' => 'SĐT này đã được đăng ký', 'status' => 400]);
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

        return response()->json(['message' => 'Gửi mã xác nhận thành công', 'status' => 200]);
    }

    public function registerVerifyOTP(Request $request)
    {
        $enteredOtp = $request->get('otp');
        $phone = $request->get('phone');

        $verification = UserVerificationModel::where('phone', $phone)->latest()->first();

        if (!$verification) {
            return response()->json(['message' => 'Mã OTP không hợp lệ hoặc chưa được gửi', 'status' => 400]);
        }

        $otpCreatedAt = $verification->created_at;
        $expiryTime = now()->diffInMinutes($otpCreatedAt);
        if ($expiryTime > 5) {
            $verification->delete();
            return response()->json(['message' => 'Mã OTP đã hết hạn', 'status' => 400]);
        }

        if ($enteredOtp == $verification->otp) {
            $verification->delete();

            return response()->json(['message' => 'Xác thực thành công', 'status' => 200]);
        }

        return response()->json(['message' => 'Mã OTP không chính xác', 'status' => 400]);
    }

    public function registerCreatePassword(Request $request)
    {
        $phone = $request->get('phone');
        $password = $request->get('password');
        $passwordConfirm = $request->get('password_confirmation');

        if ($password !== $passwordConfirm) {
            return response()->json(['message' => 'Mật khẩu xác nhận không khớp', 'status' => 400]);
        }

        $hashedPassword = Hash::make($password);

        $user = User::create([
            'phone' => $phone,
            'password' => $hashedPassword,
        ]);

        return response()->json(['message' => 'Tạo mật khẩu thành công', 'status' => 200]);
    }

    public function registerCreateProfile(Request $request)
    {
        $phone = $request->get('phone');
        $fullName = $request->get('full_name');
        $email = $request->get('email');
        $dob = $request->get('date_of_birth');

        $user = User::where('phone', $phone)->first();
        if (!$user) {
            return response()->json(['message' => 'Người dùng không tồn tại', 'status' => 400]);
        }

        $user->update([
            'full_name' => $fullName,
            'email' => $email,
            'date_of_birth' => $dob,
        ]);

        return response()->json(['message' => 'Cập nhật hồ sơ thành công', 'status' => 200]);
    }

    public function loginCheckPhone(Request $request)
    {
        $phone = $request->get('phone');

        $user = User::where('phone', $phone)->first();
        if (!$user) {
            return response()->json(['message' => 'Người dùng không tồn tại', 'status' => 400]);
        }

        return response()->json(['message' => 'Số điện thoại tồn tại, vui lòng tiếp tục', 'status' => 200]);
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

    public function logout(Request $request)
    {
        try {
            $token = $request->bearerToken();
            if (!$token) {
                return response()->json(['message' => 'Token không hợp lệ', 'status' => 400]);
            }
            $user = User::where('token', $token)->first();
            JWTAuth::setToken($token);
            JWTAuth::invalidate($token);
            $user->token = null;
            $user->is_online = 0;
            $user->save();

            return response()->json(['message' => 'Đăng xuất thành công', 'status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 400]);
        }
    }

    public function updateProfile(Request $request)
    {
        try{
            $user = JWTAuth::user();

            $user->update([
                'full_name' => $request->input('full_name'),
                'email' => $request->input('email'),
                'date_of_birth' => $request->input('date_of_birth'),
            ]);

            if ($request->hasFile('avatar')) {
                $filePath = $request->file('avatar')->store('avatars', 'public');
                $avatarPath = 'storage/' . $filePath;
                $user->update(['avatar' => $avatarPath]);
            }

            return response()->json(['message' => 'Lấy dữ liệu thành công','data'=>$user, 'status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 400]);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $user = JWTAuth::user();

            $request->validate([
                'current_password' => [
                    'required',
                    function ($attribute, $value, $fail) use ($user) {
                        if (!Hash::check($value, $user->password)) {
                            $fail('Mật khẩu hiện tại không đúng.');
                        }
                    },
                ],
                'password_new' => 'required|string|min:8',
                'password_confirmation' => 'required|string|same:password_new',
            ], [
                'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
                'password_new.required' => 'Vui lòng nhập mật khẩu mới.',
                'password_new.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
                'password_confirmation.required' => 'Vui lòng xác nhận mật khẩu mới.',
                'password_confirmation.same' => 'Mật khẩu mới và nhập lại mật khẩu không khớp.',
            ]);

            $user->update([
                'password' => Hash::make($request->input('password_new')),
            ]);

            return response()->json([
                'message' => 'Mật khẩu đã được thay đổi thành công.',
                'status' => 200
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 400
            ]);
        }
    }

    public function passwordVerifyOTP(Request $request)
    {
        $enteredOtp = $request->get('otp');
        $phone = $request->get('phone');

        $verification = UserVerificationModel::where('phone', $phone)->latest()->first();

        if (!$verification) {
            return response()->json(['message' => 'Mã OTP không hợp lệ hoặc chưa được gửi', 'status' => 400]);
        }

        if ($enteredOtp == $verification->otp) {
            $verification->delete();

            return response()->json(['message' => 'Xác thực thành công', 'status' => 200]);
        }

        return response()->json(['message' => 'Mã OTP không chính xác', 'status' => 400]);
    }

    public function forgotPassword(Request $request)
    {
        try {
            $user = User::where('phone',$request->get('phone'))->first();

            $request->validate([
                'password_new' => 'required|string|min:8',
                'password_confirmation' => 'required|string|same:password_new',
            ], [
                'password_new.required' => 'Vui lòng nhập mật khẩu mới.',
                'password_new.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
                'password_confirmation.required' => 'Vui lòng xác nhận mật khẩu mới.',
                'password_confirmation.same' => 'Mật khẩu mới và nhập lại mật khẩu không khớp.',
            ]);

            $user->update([
                'password' => Hash::make($request->input('password_new')),
            ]);

            return response()->json([
                'message' => 'Thay đổi mật khẩu thành công.',
                'status' => 200
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 400
            ]);
        }
    }

}
