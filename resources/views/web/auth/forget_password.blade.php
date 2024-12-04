@extends('web.auth.master')
@section('title','Quên mật khẩu')
@section('header-title', 'Quên mật khẩu')

@section('style_page')
@stop
@section('content')
    @include('web.partials.loading')
    <div class="content-wrapper">
        <form method="POST" action="{{ route('save.forget.password') }}" class="input-wrapper" id="register-form">
            @csrf
            <input type="text" value="{{$phone}}" name="phone" hidden>
            <div class="input-detail">
                <label for="password" class="label-field">Mật khẩu mới</label>
                <div class="input-field-password">
                    <input type="password" class="input-field" name="password" required/>
                    <img src="{{ asset('assets/images/show-password.png') }}" alt="Show Password"
                         class="show-password"/>
                </div>
                @if ($errors->has('password'))
                    <span class="error-message">{{ $errors->first('password') }}</span>
                @endif
            </div>

            <div class="input-detail">
                <label for="password_confirmation" class="label-field">Nhập lại mật khẩu</label>
                <div class="input-field-password">
                    <input type="password" class="input-field" name="password_confirmation" required/>
                    <img src="{{ asset('assets/images/show-password.png') }}" alt="Show Password"
                         class="show-password"/>
                </div>
                @if ($errors->has('password_confirmation'))
                    <span class="error-message">{{ $errors->first('password_confirmation') }}</span>
                @endif
            </div>

            <div class="submit-btn-wrapper">
                <button class="submit-btn">Thay đổi mật khẩu</button>
            </div>
        </form>
    </div>
@stop
@section('script_page')
    <script>
        document.getElementById('register-form').addEventListener('submit', function (event) {
            event.preventDefault();
            showLoading();
            setTimeout(() => event.target.submit(), 100);
        });

        document.querySelectorAll('.loading-href').forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                showLoading();
                setTimeout(() => {
                    window.location.href = link.href;
                }, 100);
            });
        });
        window.addEventListener('pageshow', hideLoading);
    </script>
@stop
