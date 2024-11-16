@extends('web.index')
@section('title','Thương hiệu yêu thích')

@section('style_page')
    <link rel="stylesheet" href="{{asset('assets/css/trademark.css')}}">
@stop
{{--content of page--}}
@section('content')
    <section class="box-trademark">
        <a href="#" class="line-back">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Trở lại</span>
        </a>
        <p class="title-trademark">{{$title}}</p>
        <div class="box-menu-trademark">
            <a href="#" class="item-menu-trademark menu-trademark-active">
                <img src="{{asset('assets/images/tea.png')}}" >
                <span>Cafe & bánh</span>
            </a>
            <a href="#" class="item-menu-trademark">
                <img src="{{asset('assets/images/tea.png')}}" >
                <span>Siêu thị & Mua sắm</span>
            </a>
            <a href="#" class="item-menu-trademark">
                <img src="{{asset('assets/images/tea.png')}}" >
                <span>Thời trang & Phụ Kiện</span>
            </a>
            <a href="#" class="item-menu-trademark">
                <img src="{{asset('assets/images/tea.png')}}" >
                <span>Du lịch & Di chuyển</span>
            </a>
            <a href="#" class="item-menu-trademark">
                <img src="{{asset('assets/images/tea.png')}}" >
                <span>Hoa, Trái cây & Thực phẩm sạch</span>
            </a>
            <a href="#" class="item-menu-trademark">
                <img src="{{asset('assets/images/tea.png')}}" >
                <span>Siêu thị & Mua sắm</span>
            </a>
            <a href="#" class="item-menu-trademark">
                <img src="{{asset('assets/images/tea.png')}}" >
                <span>Thời trang & Phụ Kiện</span>
            </a>
            <a href="#" class="item-menu-trademark">
                <img src="{{asset('assets/images/tea.png')}}" >
                <span>Du lịch & Di chuyển</span>
            </a>
            <a href="#" class="item-menu-trademark">
                <img src="{{asset('assets/images/tea.png')}}" >
                <span>Hoa, Trái cây & Thực phẩm sạch</span>
            </a>
        </div>
        <div class="search-wrapper">
            <div class="filter-search">
                <span class="price-range-title">Khoảng giá</span>
                <div class="price-range">
                    <input type="text" placeholder="Từ" class="input-price"/>
                    <span>-</span>
                    <input type="text" placeholder="Đến" class="input-price"/>
                </div>
                <div class="search-container">
                    <input type="text" class="search-input" placeholder="Tìm kiếm theo tên sản phẩm" />
                    <button class="search-button">
                        <img src="{{asset('assets/images/search-icon.png')}}" alt="Search icon" class="search-icon">
                    </button>
                </div>
                <button class="apply-button">Áp dụng</button>
            </div>

        </div>
        <div class="line-center-trademark"></div>

        <div class="content-you-like mt-4">
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

@stop
