@extends('web.auth.master')
@section('title','Đăng ký')
@section('header-title', 'Đăng ký')
@section('footer-title')
    Bạn đã có tài khoản? <a href="{{ route('login') }}" class="link-route">Đăng Nhập</a>
@stop
@section('style_page')
@stop
@section('content')
    <div class="content-wrapper">
        <div class="input-wrapper">
            <h6 class="input-title">Đăng ký</h6>
            <div class="input-detail">
                <label for="phone" class="label-field">Số điện thoại</label>
                <input type="text" class="input-field" name="phone" required/>
            </div>
            <div class="input-detail">
                <label for="phone" class="label-field">Họ và tên</label>
                <input type="text" class="input-field" name="full_name" required/>
            </div>
            <div class="input-detail">
                <label for="phone" class="label-field">Mật khẩu</label>
                <div class="input-field-password">
                    <input type="password" class="input-field" name="password" required/>
                    <img src="{{asset('assets/images/show-password.png')}}" alt="Show Password" class="show-password" />
                </div>
            </div>
            <div class="input-detail">
                <label for="phone" class="label-field">Nhập lại mật khẩu</label>
                <div class="input-field-password">
                    <input type="password" class="input-field" name="password_confirmation" required/>
                    <img src="{{asset('assets/images/show-password.png')}}" alt="Show Password" class="show-password" />
                </div>
            </div>
            <div class="submit-btn-wrapper">
                <button class="submit-btn">Đăng ký</button>
            </div>
        </div>
        <div class="switch-wrapper">
            <div class="switch-slash"></div>
            <div class="switch-detail">HOẶC</div>
            <div class="switch-slash"></div>
        </div>
        <div class="link-wrapper">
            <div class="link-detail">
                <img src="{{asset('assets/images/zalo-icon.png')}}" alt="zalo" class="link-icon" />
                <span>Tiếp tục với Zalo</span>
            </div>
            <div class="link-detail">
                <img src="{{asset('assets/images/fb-icon.png')}}" alt="facebook" class="link-icon" />
                <span>Tiếp tục với Facebook</span>
            </div>
            <div class="link-detail">
                <img src="{{asset('assets/images/email-icon.png')}}" alt="email" class="link-icon" />
                <span>Tiếp tục bằng email</span>
            </div>
        </div>
    </div>
@stop
@section('script_page')
@stop
