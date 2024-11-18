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
                    <div class="profile-menu-container">
                        <a href="{{route('profile.index')}}" class="link-login">
                            <img src="{{asset('assets/images/user-icon.png')}}">
                            <span>{{$user->full_name}}</span>
                        </a>
                        <div class="profile-menu">
                            <a href="{{route('profile.index')}}"><img src="{{asset('assets/images/user-icon.png')}}" alt="user" class="profile-icon" /> Trang cá nhân</a>
                            <a href="javascript:void(0);" onclick="setChangePassTab();"><img src="{{asset('assets/images/lock-icon.png')}}" alt="lock" class="profile-icon" /> Thay đổi mật khẩu</a>
                            <a href="javascript:void(0);" onclick="logOutOnClick();"><img src="{{asset('assets/images/logout-icon.png')}}" alt="logout icon" class="profile-icon" /> Đăng xuất</a>
                        </div>
                    </div>
                    @else
                    <a href="{{route('login')}}" class="link-login">Đăng nhập</a>
                @endif
                    <a href="javascript:void(0);" class="icon-header" onclick="setLoveTab();">
                        <img src="{{asset('assets/images/heart-icon.png')}}" alt="Heart Icon">
                    </a>
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

<script>
    function setLoveTab() {
        localStorage.setItem('activeTab', 'love');
        window.location.href = "{{ route('profile.index') }}";
    }

    function setChangePassTab() {
        localStorage.setItem('activeTab', 'change-password');
        window.location.href = "{{ route('profile.index') }}";
    }

    function logOutOnClick() {
        const logoutForm = document.createElement('form');
        logoutForm.method = 'POST';
        logoutForm.action = "{{ route('logout.submit') }}";

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = "{{ csrf_token() }}";

        logoutForm.appendChild(csrfInput);

        document.body.appendChild(logoutForm);
        logoutForm.submit();
    }

</script>
