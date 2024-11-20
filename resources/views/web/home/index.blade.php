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
                    @foreach($saleProducts as $saleProduct)
                        <div class="swiper-slide">
                            <a href="{{route('product.detail', [$saleProduct->slug])}}" class="item-product">
                                <div class="box-img-product">
                                    <img src="{{asset($saleProduct->src)}}" class="img-product">
                                    <i class="fa-solid fa-heart fa-heart-sp"></i>
                                </div>
                                <div class="name-product custom-content-2-line">{{$saleProduct->name ?? 'N/A'}}</div>
                                <span class="price-product">{{ number_format($saleProduct->price ?? 0, 0, ',', '.') }}đ</span>
                            </a>
                        </div>
                    @endforeach
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

        @php
            $classSequence = [
                'item1',
                'item2',
                'item2 item3',
                'item1 item4',
                'item1 item4',
                'item2 item3',
                'item2',
                'item1'
            ];
        @endphp

        <div id="tab-1" class="tab-content current">
            <div class="drink-grid">
                @foreach($popularProducts as $index => $product)
                    <a href="{{route('product.detail', [$product->slug])}}" class="drink-item {{ $classSequence[$index % count($classSequence)] }}">
                        <img src="{{ asset($product->src) }}" alt="{{ $product->name }}">
                        <div class="price-tag">{{ number_format($product->price, 0, ',', '.') }}đ</div>
                    </a>
                @endforeach
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
            @foreach($likeProducts as $likeProduct)
                <a href="{{route('product.detail', [$likeProduct->slug])}}" class="item-product">
                    <div class="box-img-product">
                        <img src="{{asset($likeProduct->src)}}" class="img-product">
                        <i class="fa-solid fa-heart fa-heart-sp"></i>
                    </div>
                    <div class="name-product">{{$likeProduct->name ?? 'N/A'}}</div>
                    <span class="price-product">{{ number_format($likeProduct->price ?? 0, 0, ',', '.') }}đ</span>
                </a>
            @endforeach
        </div>
    </section>
@stop
@section('script_page')
    <script src="{{asset('assets/js/home.js')}}"></script>
@stop
