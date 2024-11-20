@extends('web.index')
@section('title','voucher')

@section('style_page')
    <link rel="stylesheet" href="{{asset('assets/css/voucher.css')}}">
@stop
{{--content of page--}}
@section('content')
    <section class="box-voucher">
        <div class="line-menu-voucher">
            <a href="#" class="item-voucher item-voucher-active">Của tôi</a>
            <a href="#" class="item-voucher">Đã tặng</a>
        </div>
        <div class="content-voucher">
            @for($i=0;$i<10;$i++)
            <a href="#" class="voucher-item">
                <img src="{{asset('assets/images/Subtract.png')}}" class="img-voucher">
                <img src="{{asset('assets/images/phuc-long.png')}}" class="logo-trademark">
                <div class="line-voucher"></div>
                <div class="content-item-voucher">
                    <p class="name-voucher-product">Hi Tea Vải</p>
                    <p class="price-voucher-product">200.000đ</p>
                    <div class="content-voucher-product">Chút ngọt ngào của Vải, mix cùng vị chua thanh tao từ trà hoa Hibiscus, mang đến cho bạn thức uống đúng chuẩn vừa ngon, vừa healthy. Chút ngọt ngào của Vải, mix cùng vị chua thanh tao từ trà hoa Hibiscus, mang đến cho bạn thức uống đúng chuẩn vừa ngon, vừa healthy.</div>
                </div>
                <div class="expiry">HSD: 24/9/2024</div>
            </a>
                @endfor
        </div>
    </section>

@stop
@section('script_page')

@stop
