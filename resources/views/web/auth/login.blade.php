@extends('web.auth.master')
@section('title','Đăng nhập')
@section('header-title', 'Đăng nhập')
@section('footer-title')
    Bạn chưa có tài khoản? <a href="{{ route('register') }}" class="link-route">Đăng Ký</a>
@stop
@section('style_page')
@stop
@section('content')
    @include('web.partials.loading')
    <div class="content-wrapper">
        <form method="POST" action="{{ route('login.submit') }}" class="input-wrapper" id="login-form">
            @csrf
            <h6 class="input-title">Đăng nhập</h6>
            <div class="input-detail">
                <label for="phone" class="label-field">Số điện thoại</label>
                <input type="text" class="input-field" name="phone" required value="{{ old('phone') }}"/>
                @if ($errors->has('phone'))
                    <span class="error-message">{{ $errors->first('phone') }}</span>
                @endif
            </div>

            <div class="input-detail">
                <label for="password" class="label-field">Mật khẩu</label>
                <div class="input-field-password">
                    <input type="password" class="input-field" name="password" required/>
                    <img src="{{ asset('assets/images/show-password.png') }}" alt="Show Password" class="show-password" />
                </div>
                @if ($errors->has('password'))
                    <span class="error-message">{{ $errors->first('password') }}</span>
                @endif
            </div>
            <div class="submit-btn-wrapper">
                <button type="submit" class="submit-btn">Đăng nhập</button>
            </div>
        </form>
        <div class="switch-wrapper">
            <div class="switch-slash"></div>
            <div class="switch-detail">HOẶC</div>
            <div class="switch-slash"></div>
        </div>
        <div class="link-wrapper">
            <a href="{{url('auth/zalo/redirect')}}" class="link-detail auth-link">
                <img src="{{asset('assets/images/zalo-icon.png')}}" alt="zalo" class="link-icon" />
                <span>Tiếp tục với Zalo</span>
            </a>
            <a href="{{url('auth/facebook/redirect')}}" class="link-detail auth-link">
                <img src="{{asset('assets/images/fb-icon.png')}}" alt="facebook" class="link-icon" />
                <span>Tiếp tục với Facebook</span>
            </a>
            <a href="{{url('auth/google/redirect')}}" class="link-detail auth-link">
                <img src="{{asset('assets/images/email-icon.png')}}" alt="email" class="link-icon" />
                <span>Tiếp tục bằng email</span>
            </a>
        </div>
    </div>

@stop
@section('script_page')
    <script>
        document.getElementById('login-form').addEventListener('submit', function (event) {
            event.preventDefault();
            showLoading();
            setTimeout(() => event.target.submit(), 100);
        });

        document.querySelectorAll('.link-detail').forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                showLoading();
                setTimeout(() => {
                    window.location.href = link.href;
                }, 100);
            });
        });
    </script>
@stop
