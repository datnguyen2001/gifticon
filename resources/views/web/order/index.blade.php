@extends('web.index')
@section('title','Thanh toán')

@section('style_page')
    <link rel="stylesheet" href="{{asset('assets/css/order.css')}}">
@stop
{{--content of page--}}
@section('content')

    <section class="box-order">
        <a href="{{route('home')}}" class="line-back">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Trở lại</span>
        </a>
        <div class="box-content-order">
            <div class="item-order">
                <div class="col-item-order">
                    <div class="item-product-order">
                        <img src="{{asset('assets/images/demo-product.png')}}" class="img-item-product-order">
                        <div class="item-content-product-order">
                            <span class="name-product-order">Hi Tea Vải</span>
                            <div class="info-product-order">Size: vừa</div>
                            <div class="info-product-order">Topping: Đào miếng , Trái vải</div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="item-order item-order-use">
                <span class="name-product-order">Cảm ơn bạn</span>
                <div class="info-product-order">
                    Với lời nhắn: chúc câụ ngày mới thật nhiều năng lượng thật nhiều niềm vui và dồi dào sức khoẻ
                </div>
                <div class="info-product-order">Email: Tphi6012@gmail.com</div>
                <div class="info-product-order">Số điện thoại: 0379357213</div>
                <div class="info-product-order price-product-order">Số tiền thanh toán: <span>300,000vnđ</span></div>
            </div>
            <div class="item-order item-order-qr">
                <div class="title-qr">Qr thanh toán tiền</div>
                <img src="{{asset('assets/images/img-qr.png')}}" class="img-qr-bank">
                <div class="content-qr"><span>Trần đình phi</span><span>1018358676</span></div>
            </div>
        </div>
        <a href="#" class="btn-success-order">Xác nhận đã thanh toán</a>
    </section>

@stop
@section('script_page')

@stop
