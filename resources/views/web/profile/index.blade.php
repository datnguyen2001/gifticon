@extends('web.index')
@section('title','Trang Cá Nhân')

@section('style_page')
    <link rel="stylesheet" href="{{asset('assets/css/trademark.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/profile.css')}}">
@stop
{{--content of page--}}
@section('content')
    <div class="menu-toggle" id="menu-toggle">
        <span class="menu-dash"></span>
        <span class="menu-dash"></span>
        <span class="menu-dash"></span>
    </div>
    <div class="overlay" id="overlay"></div>
    <div class="profile-wrapper">
        <div class="profile-side-bar">
            <div class="close-icon">
                <i class="fa fa-times" aria-hidden="true"></i>
            </div>
            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('assets/images/user-icon.png') }}" alt="avatar" class="profile-avatar" />
            <ul class="list-tab">
                <li class="li-tab-wrapper">
                    <div class="list-tab-wrapper">
                        <img src="{{asset('assets/images/user-icon.png')}}" alt="user" class="profile-icon" />
                        <span class="profile-menu-title">Thông tin cá nhân</span>
                    </div>
                </li>
                <li class="li-tab-wrapper">
                    <div class="list-tab-wrapper">
                        <img src="{{asset('assets/images/heart.png')}}" alt="heart" class="profile-icon" />
                        <span class="profile-menu-title">Danh sách yêu thích</span>
                    </div>
                </li>
                <li class="li-tab-wrapper">
                    <div class="list-tab-wrapper">
                        <img src="{{asset('assets/images/lock-icon.png')}}" alt="lock" class="profile-icon" />
                        <span class="profile-menu-title">Thay đổi mật khẩu</span>
                    </div>
                </li>
                <li class="li-tab-wrapper">
                    <div class="list-tab-wrapper">
                        <form method="POST" action="{{ route('logout.submit') }}">
                            @csrf
                            <button type="submit" class="logout-submit-btn" id="logout-form">
                                <img src="{{asset('assets/images/logout-icon.png')}}" alt="logout icon" class="profile-icon" />
                                <span class="profile-menu-title">Đăng xuất</span>
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
        <div class="profile-tab">
            <div class="information-tab-action">
                <form method="POST" action="{{route('profile.update')}}" enctype="multipart/form-data" id="update-profile-form">
                    @csrf
                    <div class="information-tab">
                        @include('web.profile.partials.information-tab')
                    </div>
                </form>
            </div>
            <div class="love-tab">
                @include('web.profile.partials.love-tab')
            </div>
            <div class="change-password-tab-action">
                <form method="POST" action="{{ route('password.update') }}" id="change-password-form">
                    @csrf
                    <div class="change-password-tab">
                        @include('web.profile.partials.change-password-tab')
                    </div>
                </form>
            </div>
            <div class="update-phone-tab-action">
                <div class="update-phone-tab">
                    @include('web.profile.partials.update-phone')
                </div>
            </div>
        </div>
    </div>
@stop
@section('script_page')
    <script src="{{asset('assets/js/profile.js')}}"></script>
    <script>
        var userIcon = "{{asset('assets/images/user-icon.png')}}";
        var userIconActive = "{{asset('assets/images/user-icon-active.png')}}";
        var userIconHover = "{{asset('assets/images/user-icon-hover.png')}}";
        var heartIcon = "{{asset('assets/images/heart.png')}}";
        var heartIconActive = "{{asset('assets/images/heart-active.png')}}";
        var heartIconHover = "{{asset('assets/images/heart-hover.png')}}"
        var lockIcon = "{{asset('assets/images/lock-icon.png')}}";
        var lockIconActive = "{{asset('assets/images/lock-icon-active.png')}}";
        var lockIconHover = "{{asset('assets/images/lock-icon-hover.png')}}"
        var logoutIcon = "{{asset('assets/images/logout-icon.png')}}";
        var logoutIconActive = "{{asset('assets/images/logout-icon-active.png')}}";
        var logoutIconHover = "{{asset('assets/images/logout-icon-hover.png')}}";

        var activeTab = "{{ session('active_tab', 'information') }}";
    </script>
@stop
