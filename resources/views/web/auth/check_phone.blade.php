@extends('web.auth.master')
@section('title','Xác thực Số điện thoại')
@section('header-title', 'Quên mật khẩu')

@section('style_page')
@stop
@section('content')
    @include('web.partials.loading')
    <div class="content-wrapper">
        <form method="POST" action="{{ route('verify-phone-otp') }}" class="input-wrapper" id="verify-form">
            @csrf
            <div class="input-detail">
                <label for="otp" class="label-field">Số điện thoại</label>
                <input type="text" class="input-field" name="phone" required value="{{ old('phone') }}"/>
                @if ($errors->has('phone'))
                    <span class="error-message">{{ $errors->first('phone') }}</span>
                @endif
            </div>
            <div class="submit-btn-wrapper">
                <button type="submit" class="submit-btn">Kiểm tra số điện thoại</button>
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
