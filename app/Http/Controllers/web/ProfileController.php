<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
            'phone' => $request->input('phone'),
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
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

}
