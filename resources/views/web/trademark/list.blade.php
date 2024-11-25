@extends('web.index')
@section('title',$title)

@section('style_page')
    <link rel="stylesheet" href="{{asset('assets/css/trademark.css')}}">
@stop
{{--content of page--}}
@section('content')
    <section class="box-trademark">
        <a href="{{route('home')}}" class="line-back">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Trở lại</span>
        </a>
        <p class="title-trademark">{{$title}}</p>
        <div class="box-menu-trademark">
            @foreach($categories as $key => $category)
                @if($title == 'Khuyến mãi mới hôm nay')
                <a href="{{route('promotion-today',$category->slug)}}"
                   class="item-menu-trademark @if($category->slug == $slug) menu-trademark-active @endif">
                    @else
                        <a href="{{route('you-like',$category->slug)}}"
                           class="item-menu-trademark @if($category->slug == $slug) menu-trademark-active @endif">
                        @endif
                    <img src="{{ asset($category->src) }}" alt="{{ $category->name }}">
                    <span>{{ $category->name }}</span>
                </a>
            @endforeach
        </div>
        <div class="search-wrapper">
            @if($title == 'Khuyến mãi mới hôm nay')
            <form method="GET" action="{{ route('promotion-today', $slug) }}">
                @else
                    <form method="GET" action="{{ route('you-like', $slug) }}">
                        @endif
                <div class="filter-search">
                    <span class="price-range-title">Khoảng giá</span>
                    <div class="price-range">
                        <input type="text" name="start_price" id="start-price" placeholder="Từ"
                               value="{{request()->get('start_price')}}" class="input-price"/>
                        <span>-</span>
                        <input type="text" name="end_price" id="end-price" placeholder="Đến"
                               value="{{request()->get('end_price')}}" class="input-price"/>
                    </div>
                    <div class="search-container">
                        <input type="text" id="product-name" name="product_name"
                               value="{{request()->get('product_name')}}" class="search-input"
                               placeholder="Tìm kiếm theo tên sản phẩm"/>
                        <button id="search-button" class="search-button" type="submit">
                            <img src="{{ asset('assets/images/search-icon.png') }}" alt="Search icon"
                                 class="search-icon">
                        </button>
                    </div>
                    <button type="submit" id="apply-button" class="apply-button">Áp dụng</button>
                </div>
            </form>
        </div>
        <div class="line-center-trademark"></div>

        <div class="content-you-like mt-4">
            @foreach($products as $product)
                <div class="item-product">
                    <div class="box-img-product">
                        <img src="{{ asset($product->src) }}" class="img-product">
                        <i class="fa-solid fa-heart fa-heart-sp {{ $product->isFavorite() ? 'fa-heart-sp-active' : '' }}"
                           data-product-id="{{ $product->id }}"></i>
                    </div>
                    <a href="{{route('product.detail', [$product->slug])}}"
                       class="name-product">{{ $product->name }}</a>
                    <span class="price-product">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $products->appends(request()->all())->links('web.partials.pagination') }}
        </div>
    </section>

@stop
@section('script_page')

@stop
