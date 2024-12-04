@extends('web.auth.master')
@section('title','Xác thực Số điện thoại')
@section('header-title', 'Xác thực Số điện thoại')
{{--@section('footer-title')--}}
{{--    Bạn chưa có tài khoản? <a href="{{ route('register') }}" class="link-route loading-href">Đăng Ký</a>--}}
{{--@stop--}}
@section('style_page')
@stop
@section('content')
    @include('web.partials.loading')
    <div class="content-wrapper">
        <form method="POST" action="{{ route('password.otp.submit') }}" class="input-wrapper" id="verify-form">
            @csrf
            <div class="input-detail">
                <label for="otp" class="label-field">Mã xác thực</label>
                <input type="text" value="{{$phone}}" name="phone" hidden>
                <input type="text" class="input-field" name="otp" required value="{{ old('otp') }}"/>
                @if ($errors->has('otp'))
                    <span class="error-message">{{ $errors->first('otp') }}</span>
                @endif
            </div>
            <div class="submit-btn-wrapper">
                <button type="submit" class="submit-btn">Xác thực</button>
            </div>
        </form>
    </div>

@stop
@section('script_page')
    <script>
        document.getElementById('verify-form').addEventListener('submit', function (event) {
            event.preventDefault();
            showLoading();
            setTimeout(() => event.target.submit(), 100);
        });

        window.addEventListener('pageshow', hideLoading);
    </script>
@stop
