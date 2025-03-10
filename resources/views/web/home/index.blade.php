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
                <a href="{{url('khuyen-mai-hom-nay/all')}}">Xem tất cả</a>
            </div>
            <div class="swiper mySwiperPromotion">
                <div class="swiper-wrapper">
                    @foreach($saleProducts as $saleProduct)
                        <div class="swiper-slide">
                            <a href="{{route('product.detail', [$saleProduct->slug])}}">
                                <div class="item-product">
                                        <div class="box-img-product">
                                            <img src="{{asset($saleProduct->src)}}" class="img-product">
                                            <i class="fa-solid fa-heart fa-heart-sp {{ $saleProduct->isFavorite() ? 'fa-heart-sp-active' : '' }}"  data-product-id="{{ $saleProduct->id }}"></i>
                                        </div>

                                    <a href="{{route('product.detail', [$saleProduct->slug])}}" class="name-product custom-content-2-line" style="height: 42px">{{$saleProduct->name ?? 'N/A'}}</a>
                                    <span class="price-product">{{ number_format($saleProduct->price ?? 0, 0, ',', '.') }}đ</span>
                                </div>
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
                        @if($banners->link)
                            <a href="{{ $banners->link }}" class="swiper-slide">
                                <img src="{{ asset($banners->src) }}" class="w-100">
                            </a>
                        @else
                            <div class="swiper-slide">
                                <img src="{{ asset($banners->src) }}" class="w-100">
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
            @endif
    </section>

    <section class="box-popular">
        <p class="title-popular">Những món quà phổ biến</p>
        <ul class="tabs">
            @foreach($categories as $key => $category)
                <li class="tab-link @if($key == 0) current @endif" data-tab="tab-{{ $key + 1 }}">{{ $category->name }}</li>
            @endforeach
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

        @foreach($categories as $key => $category)
            <div id="tab-{{ $key + 1 }}" class="tab-content @if($key == 0) current @endif">
                <div class="drink-grid">
                    @php
                        $filteredProducts = $categoryProducts[$category->id];
                    @endphp
                    @foreach($filteredProducts as $index => $product)
                        @php
                            $class = $classSequence[$index % count($classSequence)];
                        @endphp
                        <a href="{{ route('product.detail', [$product->slug]) }}" class="drink-item {{ $class }}">
                            <img src="{{ asset($product->src) }}" alt="{{ $product->name }}">
                            <div class="price-tag">{{ number_format($product->price, 0, ',', '.') }}đ</div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </section>

    @if(isset($shop) && count($shop)>0)
    <section class="box-trademark">
        <div class="line-top-trademark">
            <span>Những thương hiệu yêu thích</span>
            <a href="{{route('trademark','all')}}"><i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="swiper-buttons">
            <button class="trademark-button-prev"><i class="fa-solid fa-angle-left"></i></button>
            <button class="trademark-button-next"><i class="fa-solid fa-angle-right"></i></button>
        </div>

        <div class="swiper mySwiperTrademark">
            <div class="swiper-wrapper">
                @foreach($shop as $shops)
                    <a href="{{route('brand.detail',$shops->slug)}}" class="swiper-slide">
                        <img src="{{ asset($shops->src??'assets/images/kfc.png') }}" class="img-shop-like">
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <section class="box-you-like">
        <div class="line-top-you-like">
            <span>Có thể bạn cũng thích</span>
            <a href="{{url('co-the-ban-thich/all')}}">Xem tất cả</a>
        </div>
        <div class="content-you-like">
            @foreach($likeProducts as $likeProduct)
                <a href="{{route('product.detail', [$likeProduct->slug])}}">
                    <div class="item-product">
                        <div class="box-img-product">
                            <img src="{{asset($likeProduct->src)}}" class="img-product">
                            <i class="fa-solid fa-heart fa-heart-sp {{ $likeProduct->isFavorite() ? 'fa-heart-sp-active' : '' }}"  data-product-id="{{ $likeProduct->id }}"></i>
                        </div>
                        <a href="{{route('product.detail', [$likeProduct->slug])}}" class="name-product">{{$likeProduct->name ?? 'N/A'}}</a>
                        <span class="price-product">{{ number_format($likeProduct->price ?? 0, 0, ',', '.') }}đ</span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@stop
@section('script_page')
    <script src="{{asset('assets/js/home.js')}}"></script>
@stop
