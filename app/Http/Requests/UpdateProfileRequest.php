<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // You can add custom authorization logic here if needed.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $userId = $this->user()->id; // Get the authenticated user's ID

        return [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $userId,
//            'phone' => 'required|digits:10|unique:users,phone,' . $userId,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Họ và tên là bắt buộc.',
            'full_name.string' => 'Họ và tên phải là chuỗi.',
            'full_name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'email.unique' => 'Email này đã được đăng ký.', // Custom message for email uniqueness
//            'phone.required' => 'Số điện thoại là bắt buộc.',
//            'phone.digits' => 'Số điện thoại phải là 10 chữ số.',
//            'phone.unique' => 'Số điện thoại này đã được đăng ký.',
            'avatar.image' => 'Ảnh đại diện phải là tệp hình ảnh.',
            'avatar.mimes' => 'Ảnh đại diện phải có định dạng: jpeg, png, jpg, gif.',
            'avatar.max' => 'Ảnh đại diện không được vượt quá 2MB.',
        ];
    }
}
