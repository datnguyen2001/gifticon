@extends('web.index')
@section('title','Trang chủ')
@php
    $user = session('jwt_token') ? \Tymon\JWTAuth\Facades\JWTAuth::setToken(session('jwt_token'))->authenticate() : null;
@endphp
@section('style_page')
    <link rel="stylesheet" href="{{asset('assets/css/home-new.css')}}">
@stop
{{--content of page--}}
@section('content')
    <div class="box-main-container">
        <div class="container_top">
            <div class="container_top-col-left">
                <div class="login_area">
                    @if(!empty($user))
                        <a href="{{route('profile.index')}}" class="login_area-btn">{{$user->full_name}}</a>
                        @else
                        <a href="{{route('login')}}" class="login_area-btn">Đăng nhập</a>
                        @endif
                    <div class="line-login_area">
                        <a href="#" class="link-line-login_area">Tìm ID</a>
                        @if(!empty($user))
                            <a href="#" onclick="setChangePassTab();" class="link-line-login_area">Đổi mật khẩu</a>
                            @else
                        <a href="{{route('check-phone')}}" class="link-line-login_area">Tìm mật khẩu</a>
                        @endif
                        <a href="#" class="link-line-login_area">Tham gia thành viên</a>
                    </div>
                </div>
                <div class="bastRanking">
                    <h2>Bảng xếp hạng</h2>
                    <div class="rankTablist">
                        <div class="line-menu-sp-hot">
                            <h3 class="item-menu-hot on"><span class="ir on">Phổ biến</span></h3>
                            <h3 class="item-menu-hot"><span class="ir">Giảm mạnh</span></h3>
                        </div>
                        <div class="ranking-container">
                            <div class="rankingViewport has-scrollbar" style="display: block;">
                                <div class="overViewpage" tabindex="0" style="right: -15px;">
                                    @foreach($popularProducts as $key => $popular)
                                        <a href="{{route('product.detail', [$popular->slug])}}">
                                            <div class="img">
                                                @if($key==0)
                                                    <img src="{{asset('assets/images/ranking_1.png')}}" class="ico"
                                                         alt="Tốt nhất 1">
                                                @elseif($key==1)
                                                    <img src="{{asset('assets/images/ranking_2.png')}}" class="ico"
                                                         alt="Tốt nhất 2">
                                                @elseif($key==2)
                                                    <img src="{{asset('assets/images/ranking_3.png')}}" class="ico"
                                                         alt="Tốt nhất 3">
                                                @endif
                                                <img src="{{$popular->src}}" loading="lazy" class="img-sp-top">
                                            </div>
                                            <div class="text-sp-hot">
                                                <span class="title">{{$popular->name}}</span>
                                                <span class="cost">{{number_format($popular->price)}}đ</span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                                <div class="pane" style="opacity: 1; visibility: visible;">
                                    <div class="slider" style="height: 62px; top: 0px;"></div>
                                </div>
                            </div>
                            <div class="rankingViewport rankconHide has-scrollbar" style="display: none;">
                                <div class="overViewpage" tabindex="0" style="right: -15px;">
                                    @foreach($plummetedProducts as $key => $plummeted)
                                        <a href="{{route('product.detail', [$plummeted->slug])}}">
                                            <div class="img">
                                                @if($key==0)
                                                    <img src="{{asset('assets/images/ranking_1.png')}}" class="ico"
                                                         alt="Tốt nhất 1">
                                                @elseif($key==1)
                                                    <img src="{{asset('assets/images/ranking_2.png')}}" class="ico"
                                                         alt="Tốt nhất 2">
                                                @elseif($key==2)
                                                    <img src="{{asset('assets/images/ranking_3.png')}}" class="ico"
                                                         alt="Tốt nhất 3">
                                                @endif
                                                <img src="{{$plummeted->src}}" loading="lazy" class="img-sp-top">
                                            </div>
                                            <div class="text-sp-hot">
                                                <span class="title">{{$plummeted->name}}</span>
                                                <span class="cost">{{number_format($plummeted->price)}}đ</span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                                <div class="pane" style="opacity: 1; visibility: visible;">
                                    <div class="slider" style="height: 62px; top: 0px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container_top-col-center">
                <div class="swiper mySwiperBanner">
                    <div class="swiper-wrapper">
                        @foreach($banner as $banners)
                            <div class="swiper-slide">
                                @if($banners->link)<a href="{{ $banners->link }}">@else
                                        <div>@endif
                                            <img src="{{ $banners->src }}" loading="lazy" alt="">
                                        @if($banners->link)</a>@else</div>@endif
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="swiper-pagination-banner"></div>
        </div>
        <div class="container_top-col-right">
            <h2 class="title-event-sale">Sự kiện/Giảm giá</h2>
            <div class="box-img-event">
                @for($i=0;$i<4;$i++)
                    <img src="{{asset('assets/images/img-event.png')}}" alt="" loading="lazy">
                @endfor
            </div>
        </div>
    </div>

    <div class="productbox">
        <div class="productbox-item">
            <h2>Sản phẩm đề xuất</h2>
            <div class="box-product-all">
                @foreach($proposeProducts as $key => $propose)
                    @if($key<4)
                    <a href="{{route('product.detail', [$propose->slug])}}" class="item-product-all">
                        <div class="img-product-all">
                            {{--                                <div class="icobg1">2%</div>--}}
                            <img src="{{asset($propose->src)}}"
                                 alt="" loading="lazy" class="img-prod-all">
                        </div>
                        <div class="content-product-all">
                            <img src="{{asset($propose->shop->src)}}"
                                 alt="" loading="lazy" class="logo-th-item">
                            <div class="name-product-all">{{$propose->name}}</div>
                            <div class="prod_info">
                                <span class="sail">{{number_format($propose->price)}}đ</span>
                                <span class="cost"></span>
                            </div>
                        </div>
                    </a>
                @endif
                        @endforeach
            </div>
        </div>
        <div class="productbox-item position-relative">
            <h2>Các thương hiệu</h2>
            <div class="swiper mySwiperProductTH">
                <div class="swiper-wrapper">
                    @foreach($shops as $shop)
                        <div class="swiper-slide box-th-product">
                            <img src="{{asset($shop->src)}}" alt="" loading="lazy" class="logo-th">
                            <div class="col-right-th">
                                @foreach($shop->products as $value)
                                    <div class="info-product-th">
                                        <div class="col-info-product-th-left">
                                            {{--                                                <div class="icobg1">2%</div>--}}
                                            <img
                                                src="{{asset($value->src)}}"
                                                alt="" loading="lazy" class="img-sp-th">
                                        </div>
                                        <div class="col-info-product-th-right">
                                            <div class="name-sp-th">{{$value->name}}</div>
                                            <div class="price-sp-th">
                                                {{--                                                    <span class="cost">30.000 won</span>--}}
                                                <span class="sail">{{number_format($value->price)}}đ</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
            <div class="swiper-pagination-product-th"></div>
        </div>
    </div>

    <div class="noticebox">
        <div class="notice">
            <div class="line-notice">
                <span>Tin tức/Thông báo</span>
                <a href="#">Xem thêm <i class="fa-solid fa-caret-right"></i></a>
            </div>
            <ul class="depth1">
                <li>
                    <a href="#" class="line-notice-text">
                        <span class="tit">[Thông báo] Thông báo về việc công khai Phí nạp tiền trả trước </span>
                        <span class="date">2025.03.13</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="line-notice-text">
                            <span
                                class="tit">[Thông báo] Thông báo về hoạt động của Hệ thống thẻ trả trước Hàn Quốc...</span>
                        <span class="date">2025.03.13</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="line-notice-text">
                        <span class="tit">[Thông báo] Thông báo bảo trì hệ thống Megabox...</span>
                        <span class="date">2025.03.13</span>
                    </a>
                </li>

            </ul>
        </div>
        <img src="{{asset('assets/images/giftics.gif')}}" loading="lazy" alt="">
        <img src="{{asset('assets/images/refund.gif')}}" loading="lazy" alt="">
        <img src="{{asset('assets/images/mobileapp.gif')}}" loading="lazy" alt="">
    </div>
    </div>

    <div class="box-main-container-mob">
        <div class="swiper mySwiperBannerMob">
            <div class="swiper-wrapper">
                @foreach($banner as $banners)
                    <div class="swiper-slide">
                        @if($banners->link)<a href="{{ $banners->link }}">@else
                                <div>@endif
                                    <img src="{{asset($banners->src)}}" loading="lazy" alt="">
                                @if($banners->link)</a>@else</div>@endif
            </div>
            @endforeach
        </div>
    </div>

    <h2 class="title-sp-home">Sản phẩm được đề xuất</h2>
    <div class="swiper-pagination-product-propose"></div>
    <div class="swiper mySwiperProductPropose">
        <div class="swiper-wrapper">
            @foreach($proposeProducts as $propose)
                <div class="swiper-slide">
                    <a href="{{route('product.detail', [$propose->slug])}}" class="item-propose">
                        <div class="box-img-propose">
{{--                            <div class="icobg1">2%</div>--}}
                            <img src="{{asset($propose->src)}}" loading="lazy" class="img-sp-propose">
                        </div>
                        <div class="name-sp-propose">{{$propose->name}}</div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <div class="line-sp-news">
        <h2 class="title-sp-new">Sản phẩm mới</h2>
        <a href="{{route('product-new','all')}}" class="see-more-news"><i class="fa-solid fa-plus"></i> Xem thêm</a>
    </div>
    <div class="container-slider">
        <div class="swiper-button-prev-news"></div>
        <div class="swiper mySwiperProductNew">
            <div class="swiper-wrapper">
                @foreach($newProducts as $newsprod)
                    <div class="swiper-slide">
                        <a href="{{route('product.detail', [$newsprod->slug])}}" class="item-prod-news">
                            <div class="box-img-propose">
{{--                                <div class="icobg1">2%</div>--}}
                                <img src="{{asset($newsprod->src)}}" loading="lazy" class="img-sp-news">
                            </div>
                            <div class="name-sp-news">{{$newsprod->name}}</div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="swiper-button-next-news"></div>
    </div>


    <div class="line-sp-rank">
        <h2 class="title-sp-new">Xếp hạng Gifticon</h2>
        <div class="line-right-rank">
            <span class="item-menu-rank active"><i class="fa-solid fa-thumbs-up"></i> Xếp hạng hấp dẫn</span>
            <span class="item-menu-rank-line">|</span>
            <span class="item-menu-rank"><i class="fa-solid fa-star"></i> Bán chạy nhất</span>
        </div>
    </div>
    <div class="box-rank">
        <div class="swiper mySwiperProductRank1">
            <div class="swiper-wrapper">
                @foreach($popularProducts as $key => $popular)
                    @if($key<8)
                    <div class="swiper-slide">
                        <a href="{{route('product.detail', [$popular->slug])}}" class="item-propose">
                            <div class="box-img-propose">
                                <img src="{{ asset('assets/images/ico_' . ($loop->iteration) . '.png') }}" loading="lazy" class="img-top-rank">
                                <img src="{{asset($popular->src)}}"
                                     loading="lazy" alt="" class="img-sp-propose">
                            </div>
                            <div class="name-sp-propose">{{$popular->name}}</div>
                        </a>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        <div class="swiper-pagination-product-rank1"></div>
    </div>
    <div class="box-bestseller">
        <div class="swiper mySwiperProductRank2">
            <div class="swiper-wrapper">
                @foreach($plummetedProducts as $key => $popular)
                    @if($key<8)
                        <div class="swiper-slide">
                            <a href="{{route('product.detail', [$popular->slug])}}" class="item-propose">
                                <div class="box-img-propose">
                                    <img src="{{ asset('assets/images/ico_' . ($loop->iteration) . '.png') }}" loading="lazy" class="img-top-rank">
                                    <img src="{{asset($popular->src)}}"
                                         loading="lazy" alt="" class="img-sp-propose">
                                </div>
                                <div class="name-sp-propose">{{$popular->name}}</div>
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        <div class="swiper-pagination-product-rank2"></div>
    </div>

    <div class="notice">
        <h2 class="noticetit"><a href="#">Thông báo</a></h2>
    </div>
    </div>
@stop
@section('script_page')
    <script src="{{asset('assets/js/home-new.js')}}"></script>
@stop
