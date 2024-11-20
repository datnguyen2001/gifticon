@extends('web.index')
@section('title','Trang chủ')

@section('style_page')
    <link rel="stylesheet" href="{{asset('assets/css/home.css')}}">
@stop
{{--content of page--}}
@section('content')

    <section class="box-promotion">
        <div class="promotion-content">
            <div class="line-top-you-like">
                <span>Khuyến mãi mới hôm nay</span>
                <a href="{{route('promotion-today')}}">Xem tất cả</a>
            </div>
            <div class="swiper mySwiperPromotion">
                <div class="swiper-wrapper">
                    @for($i=0;$i<6;$i++)
                        <div class="swiper-slide item-product">
                            <div class="box-img-product">
                                <img src="{{asset('assets/images/image-product.png')}}" class="img-product">
                                <i class="fa-solid fa-heart fa-heart-sp"></i>
                            </div>
                            <div class="name-product custom-content-2-line">Triple Coffee Frappuccino</div>
                            <span class="price-product">200.000đ</span>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
        @if(isset($banner) && count($banner))
        <div class="promotion-content">
            <div class="swiper mySwiperBanner w-100">
                <div class="swiper-wrapper">
                    @foreach($banner as $banners)
                        <a @if($banners->link) href="{{$banners->link}}" @endif class="swiper-slide">
                            <img src="{{asset($banners->src)}}" class="w-100">
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
            @endif
    </section>

    <section class="box-popular">
        <p class="title-popular">Nhưng món quà phổ biến</p>
        <ul class="tabs">
            <li class="tab-link current" data-tab="tab-1">Đồ uống</li>
            <li class="tab-link" data-tab="tab-2">Trà sữa</li>
            <li class="tab-link" data-tab="tab-3">Gà rán</li>
        </ul>

        <div id="tab-1" class="tab-content current">
            <div class="drink-grid">
                <div class="drink-item item1">
                    <img src="{{asset('assets/images/image-drink.png')}}" >
                    <div class="price-tag">1,295,337đ</div>
                </div>
                <div class="drink-item item2">
                    <img src="{{asset('assets/images/image-drink.png')}}" >
                    <div class="price-tag">1,687,816đ</div>
                </div>
                <div class="drink-item item2 item3">
                    <img src="{{asset('assets/images/image-drink.png')}}" >
                    <div class="price-tag">4,200,000đ</div>
                </div>
                <div class="drink-item item1 item4">
                    <img src="{{asset('assets/images/image-drink.png')}}" >
                    <div class="price-tag">1,243,823đ</div>
                </div>
                <div class="drink-item item1 item4">
                    <img src="{{asset('assets/images/image-drink.png')}}">
                    <div class="price-tag">1,677,720đ</div>
                </div>
                <div class="drink-item item2 item3">
                    <img src="{{asset('assets/images/image-drink.png')}}" >
                    <div class="price-tag">6,632,124đ</div>
                </div>
                <div class="drink-item item2">
                    <img src="{{asset('assets/images/image-drink.png')}}" >
                    <div class="price-tag">4,186,867đ</div>
                </div>
                <div class="drink-item item1">
                    <img src="{{asset('assets/images/image-drink.png')}}" >
                    <div class="price-tag">3,132,184đ</div>
                </div>
            </div>
        </div>
        <div id="tab-2" class="tab-content">content2</div>
        <div id="tab-3" class="tab-content">content3</div>
    </section>

    @if(isset($shop) && count($shop)>0)
    <section class="box-trademark">
        <div class="line-top-trademark">
            <span>Những thương hiệu yêu thích</span>
            <a href="{{route('trademark')}}"><i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="swiper-buttons">
            <button class="trademark-button-prev"><i class="fa-solid fa-angle-left"></i></button>
            <button class="trademark-button-next"><i class="fa-solid fa-angle-right"></i></button>
        </div>

        <div class="swiper mySwiperTrademark">
            <div class="swiper-wrapper">
                @foreach($shop as $shops)
                    <a href="{{route('brand.detail',$shops->slug)}}" class="swiper-slide">
                        <img src="{{ asset($shops->src??'assets/images/kfc.png') }}" class="w-100">
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <section class="box-you-like">
        <div class="line-top-you-like">
            <span>Có thể bạn cũng thích</span>
            <a href="{{route('you-like')}}">Xem tất cả</a>
        </div>
        <div class="content-you-like">
            @for($i=0;$i<24;$i++)
                <div class="item-product">
                    <div class="box-img-product">
                        <img src="{{asset('assets/images/image-product.png')}}" class="img-product">
                        <i class="fa-solid fa-heart fa-heart-sp"></i>
                    </div>
                    <div class="name-product">Triple Coffee Frappuccino</div>
                    <span class="price-product">200.000đ</span>
                </div>
            @endfor
        </div>
    </section>
@stop
@section('script_page')
    <script src="{{asset('assets/js/home.js')}}"></script>
@stop
