@extends('web.index')
@section('title','Giỏ hàng')

@section('style_page')
    <link rel="stylesheet" href="{{asset('assets/css/cart.css')}}">
@stop
{{--content of page--}}
@section('content')

    <section class="box-cart">
        <div class="line-menu-item-top">
            <a href="{{route('home')}}">Trang chủ</a>
            <span style="margin: 0 7px;font-size: 11px"> <i class="fa-solid fa-angle-right"></i> </span>
            <span>Giỏ hàng</span>
        </div>

        <div class="box-content-cart">
            <div class="item-left">
                <div class="header-cart">
                    <p class="name-cart-use">Giỏ hàng của tôi</p>
                    <div class="content-cart-use">Giỏ hàng hiện cho phép bạn đặt một hoặc nhiều đơn hàng từ một nhà cung
                        cấp
                    </div>
                </div>

                @for($i=0;$i<4;$i++)
                    <div class="item-cart">
                        <div class="d-flex flex-column align-center justify-content-center">
                            <input type="checkbox" class="tick-box">
                        </div>
                        <img src="{{asset('assets/images/image-product.png')}}" class="img-sp-cart">
                        <div class="content-item-cart">
                            <p class="name-sp-cart">Hi Tea Vải</p>
                            <p class="price-sp-cart">200.000đ</p>
                            <div class="content-sp-cart">Chút ngọt ngào của Vải, mix cùng vị chua thanh tao từ trà hoa
                                Hibiscus, mang đến cho bạn thức uống đúng chuẩn vừa ngon, vừa healthy.
                            </div>
                        </div>
                        <a href="#" class="btn-delete-cart">
                            <img src="{{asset('assets/images/icon-delete-cart.png')}}">
                        </a>
                    </div>
                @endfor

            </div>
            <div class="item-right">
                <p class="name-order">Tóm tắt đơn hàng</p>
                <div class="d-flex justify-content-between align-center">
                    <span class="name-total-price">Tiền hàng</span>
                    <span class="content-total-price">2.900.000đ</span>
                </div>
                <a href="#" class="btn-pay">Thanh toán</a>
            </div>
        </div>

    </section>

@stop
@section('script_page')

@stop
