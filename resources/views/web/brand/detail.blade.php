@extends('web.index')
@section('title','Tạo Đơn Hàng')

@section('style_page')
    <link rel="stylesheet" href="{{asset('assets/css/brand-detail.css')}}">
@stop
{{--content of page--}}
@section('content')
    <section class="box-promotion">
        <div class="promotion-content">
            <div class="brand-information">
                <div class="brand-logo">
                    <img src="{{asset('assets/images/brand-logo.png')}}" alt="brand logo" class="brand-img" />
                </div>
                <div class="brand-introduction">
                    <h5 class="brand-name">Mc Donald</h5>
                    <p class="brand-content">Mcdonal’s phấn đấu trở thành địa điểm yêu thích của khách hàng và là cách tốt nhấn để thưởng thức đồ ăn  bắng cách mang đến cho khách hàng trải nghiệm tốt hơn.</p>
                </div>
            </div>
            <div class="swiper mySwiperPromotion">
                <div class="brand-discount">
                    <span>Khuyến mãi của shop</span>
                </div>
                <div class="swiper-wrapper">
                    @for($i=0;$i<6;$i++)
                        <div class="swiper-slide item-product">
                            <div class="box-img-product">
                                <img src="{{asset('assets/images/image-product.png')}}" class="img-product">
                                <i class="fa-solid fa-heart fa-heart-sp"></i>
                            </div>
                            <div class="name-product">Triple Coffee Frappuccino</div>
                            <span class="price-product">200.000đ</span>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </section>
    <section class="box-you-like">
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
    <script src="{{asset('assets/js/brand-detail.js')}}"></script>
@stop
