<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = JWTAuth::user();
        return view('web.profile.index', compact('user'));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = JWTAuth::user();

        $user->update([
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
//            'phone' => $request->input('phone'),
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $filePath = $request->file('avatar')->store('avatars', 'public');
            $avatarPath = 'storage/' . $filePath;
            $user->update(['avatar' => $avatarPath]);
        }

        return redirect()->route('profile.index')->with('success', 'Thông tin được cập nhật thành công!');
    }

    public function updatePassword(Request $request)
    {
        // Flash message and redirect
        session()->flash('active_tab', 'change-password');

        $user = JWTAuth::user();

        // Validate inputs with custom messages
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

        // Update the password
        $user->update([
            'password' => Hash::make($request->input('password_new')),
        ]);

        return redirect()->route('profile.index')->with('success', 'Mật khẩu đã được cập nhật thành công.');
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            session()->flush();

            return redirect('/')->with([
                'success' => 'Đăng xuất thành công!',
            ]);
        } catch (JWTException $e) {
            return redirect()->back()->withErrors([
                'error' => 'Đăng xuất thất bại, vui lòng thử lại sau.',
            ]);
        }
    }

    public function sendOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:10',
        ]);

        $phoneExists = User::where('phone', $request->phone)->exists();

        if ($phoneExists) {
            return response()->json([
                'success' => false,
                'message' => 'Số điện thoại này đã được sử dụng.',
            ], 422);
        }

        $otp = random_int(10000, 99999);

        session([
            'otp_code' => $otp,
            'otp_created_at' => now(),
            'new_phone' => $request->phone
        ]);

        $this->sendZaloOTP($request->phone, $otp);

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'OTP đã được gửi!',
        ]);
    }

    public function verifyOTPProfile(Request $request)
    {
        session()->flash('active_tab', 'update-phone');

        $request->validate([
            'otp' => 'required|numeric',
        ]);

        // Check if OTP exists and matches
        if (!session()->has('otp_code') || !session()->has('new_phone')) {
            return redirect()->back()->withErrors([
                'otp' => 'Mã OTP không tồn tại hoặc đã hết hạn. Vui lòng thử lại.',
            ])->withInput(['phone' => session('new_phone')]);
        }

        if ($request->otp != session('otp_code')) {
            return redirect()->back()->withErrors([
                'otp' => 'Mã OTP không chính xác.',
            ])->withInput(['phone' => session('new_phone')]);
        }

        $user = JWTAuth::user();
        $user->update([
            'phone' => session('new_phone'),
        ]);

        session()->forget(['otp_code', 'otp_created_at', 'new_phone', 'active_tab']);

        return redirect()->route('profile.index')->with('success', 'Số điện thoại đã được cập nhật thành công!');
    }
}
