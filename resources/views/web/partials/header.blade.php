@php
    $user = session('jwt_token') ? \Tymon\JWTAuth\Facades\JWTAuth::setToken(session('jwt_token'))->authenticate() : null;
@endphp
<div class="box-header">
    <div class="header">
         <div class="header-main">
             <a href="{{route('home')}}" class="title-logo">Gifticon</a>
             <div class="box-search">
                 <input type="text" class="input-search" placeholder="Tìm kiếm bất cứ điều gì">
                 <div class="icon-search"><img src="{{asset('assets/images/icon-search.png')}}" alt=""></div>
             </div>
            <div class="d-flex align-items-center box-infor-login">
                @if($user)
                    <a href="{{route('profile.index')}}" class="link-login"><img src="{{asset('assets/images/user-icon.png')}}" > <span>{{$user->full_name}}</span></a>
                    @else
                    <a href="{{route('login')}}" class="link-login">Đăng nhập</a>
                @endif
                <a href="#" class="icon-header"><img src="{{asset('assets/images/heart-icon.png')}}" ></a>
                <a href="#" class="icon-header"><img src="{{asset('assets/images/icon-cart.png')}}" ></a>
            </div>
         </div>
        <div class="header-bottom">
            <div class="item-menu-header"><img src="{{asset('assets/images/Container.png')}}" alt=""> <span>Gif card</span></div>
            <a href="{{route('trademark')}}" class="item-menu-header">Tất cả các thương hiệu</a>
            <a href="{{route('my-vote')}}" class="item-menu-header">Quà của tôi</a>
            <a href="#" class="item-menu-header">Mua số lượng lớn</a>
            @if(!$user)
            <a href="{{route('register')}}" class="item-menu-header">Đăng ký</a>
                @endif
        </div>
    </div>
</div>
