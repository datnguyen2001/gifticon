@extends('web.index')
@section('title','Thương hiệu '.@$data->name)

@section('style_page')
    <link rel="stylesheet" href="{{asset('assets/css/brand-detail.css')}}">
@stop
{{--content of page--}}
@section('content')
    <a href="{{route('home')}}" class="line-back">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Trở lại</span>
    </a>
    <section class="box-promotion">
        <div class="promotion-content">
            <div class="brand-information">
                <div class="brand-logo">
                    <img src="{{asset($data->src??'assets/images/brand-logo.png')}}" alt="brand logo"
                         class="brand-img"/>
                </div>
                <div class="brand-introduction">
                    <h5 class="brand-name">{{@$data->name}}</h5>
                    <p class="brand-content brand-truncated">{{ @$data->content }}</p>
                    <button class="brand-toggle-button">Xem thêm</button>
                </div>
            </div>
            <div class="swiper mySwiperPromotion">
                <div class="brand-discount">
                    <span>Khuyến mãi của shop</span>
                </div>
                <div class="swiper-wrapper">
                    @foreach($saleProducts as $saleProduct)
                        <div class="swiper-slide">
                            <div class="item-product">
                                <div class="box-img-product">
                                    <img src="{{asset($saleProduct->src ??'assets/images/image-product.png')}}"
                                         class="img-product">
                                    <i class="fa-solid fa-heart fa-heart-sp {{ $saleProduct->isFavorite() ? 'fa-heart-sp-active' : '' }}"
                                       data-product-id="{{ $saleProduct->id }}"></i>
                                </div>
                                <a href="{{route('product.detail', [$saleProduct->slug])}}"
                                   class="name-product">{{$saleProduct->name ?? ''}}</a>
                                <span
                                    class="price-product">{{ number_format($saleProduct->price, 0, ',', '.') }}đ</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <section class="box-you-like">
        <form method="GET" action="{{ route('brand.detail', $data->slug) }}">
            <div class="search-wrapper">
                <div class="filter-search">
                    <span class="price-range-title">Khoảng giá</span>
                    <div class="price-range">
                        <input type="text" name="start_price" id="start-price" placeholder="Từ" value="{{request()->get('start_price')}}" class="input-price"/>
                        <span>-</span>
                        <input type="text" name="end_price" id="end-price" placeholder="Đến" value="{{request()->get('end_price')}}" class="input-price"/>
                    </div>
                    <div class="search-container">
                        <input type="text" id="product-name" name="product_name" value="{{request()->get('product_name')}}" class="search-input"
                               placeholder="Tìm kiếm theo tên sản phẩm"/>
                        <button id="search-button" class="search-button" type="submit">
                            <img src="{{ asset('assets/images/search-icon.png') }}" alt="Search icon"
                                 class="search-icon">
                        </button>
                    </div>
                    <button type="submit" id="apply-button" class="apply-button">Áp dụng</button>
                </div>
            </div>
        </form>
        <div class="content-you-like">
            @foreach($shopProducts as $shopProduct)
                <div class="item-product"
                     data-price="{{ $shopProduct->price }}"
                     data-name="{{ strtolower($shopProduct->name) }}">
                    <div class="box-img-product">
                        <img src="{{ asset($shopProduct->src ?? 'assets/images/image-product.png') }}"
                             class="img-product">
                        <i class="fa-solid fa-heart fa-heart-sp {{ $shopProduct->isFavorite() ? 'fa-heart-sp-active' : '' }}"
                           data-product-id="{{ $shopProduct->id }}"></i>
                    </div>
                    <a href="{{ route('product.detail', [$shopProduct->slug]) }}"
                       class="name-product">{{ $shopProduct->name ?? '' }}</a>
                    <span class="price-product">{{ number_format($shopProduct->price, 0, ',', '.') }}đ</span>
                </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $shopProducts->appends(request()->all())->links('web.partials.pagination') }}
        </div>
    </section>
@stop
@section('script_page')
    <script src="{{asset('assets/js/brand-detail.js')}}"></script>
@stop
