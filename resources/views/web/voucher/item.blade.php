@extends('web.index')
@section('title','Chi tiết voucher')

@section('style_page')
    <link rel="stylesheet" href="{{asset('assets/css/voucher.css')}}">
@stop
{{--content of page--}}
@section('content')
    <section class="box-voucher">
        <a href="{{route('my-vote')}}" class="line-back">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Trở lại</span>
        </a>
        <div class="box-item-img-voucher">
            <div class="img-ticket-voucher">
                <p class="name-sp-voucher">Hi Tea Vải</p>
                <div class="d-flex justify-content-center">
                    <img src="{{asset('assets/images/demo-product.png')}}" class="img-sp-voucher">
                </div>
                <div class="line-barcode">
                    <img src="{{asset('assets/images/top-section.png')}}" class="img-barcode">
                </div>
                <p class="name-cs">Cơ sở áp dụng:</p>
                @for($i=0;$i<4;$i++)
                    <div class="line-address">
                        <img src="{{asset('assets/images/location-icon.png')}}" class="icon-location">
                        <span>90 Hoàng Ngân, Trung Hoà, Cầu Giấy, Hà Nội</span>
                    </div>
                @endfor
                <div class="line-bottom-voucher">
                    <p class="code-voucher">Mã Voucher: <span>1234569</span></p>
                    <button class="btn-copy-code">Copy mã</button>
                </div>
            </div>
        </div>
    </section>


@stop
@section('script_page')

@stop
