<!doctype html>
<html lang="vi">

<head>
    <meta name="google-site-verification" content="googleeacc2166ce777ac3.html"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>@yield('title')</title>
    <link href="{{ asset('assets/images/logo.png') }}" rel="icon">
    <link href="{{ asset('assets/images/logo.png') }}" rel="apple-touch-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
          integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    {{--    <link rel="stylesheet" href="{{ asset('assets/css/header.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('assets/css/header-new.css') }}">
@yield('style_page')
<!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-6329DR07MN"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'G-6329DR07MN');

        gtag('event', 'page_view_time', {
            'page_title': document.title,
            'page_location': window.location.href,
            'view_time': new Date().toLocaleTimeString()  // Thời gian truy cập trang theo định dạng giờ:phút:giây
        });
    </script>
</head>

<body>
@php
    $user = session('jwt_token') ? \Tymon\JWTAuth\Facades\JWTAuth::setToken(session('jwt_token'))->authenticate() : null;
    if($user){
        $cart = \App\Models\CartModel::where('user_id',$user->id)->where('type', 1)->count('id');
    }else{
        $cart = 0;
    }
    $isHome = request()->is('/');
@endphp
<div class="box-big-layout">
    @if($isHome)
        @include('web.partials.header-new')
    @else
        @include('web.partials.header')
    @endif
    <main class="main">
        @if($isHome)
            @include('web.partials.right-bar')
        @endif
        @yield('content')
    </main>
    @if($isHome)
        @include('web.partials.footer-new')
    @else
        @include('web.partials.footer')
    @endif

    @include('web.partials.loading')
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
        integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    document.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            showLoading();
            if (link.getAttribute('href') === '#') {
                hideLoading();
            }
            setTimeout(() => {
                window.location.href = link.href;
            }, 100);
        });
    });

    window.addEventListener('pageshow', hideLoading);

</script>
<script src="{{asset('assets/js/main.js')}}"></script>
@yield('script_page')
</body>

</html>
