@extends('web.index')
@section('title','Phiếu sản phẩm')

@section('style_page')
    <link rel="stylesheet" href="{{ asset('assets/css/voucher.css') }}">
@stop

{{--content of page--}}
@section('content')
    <section class="box-voucher">
        <div class="line-menu-voucher">
            <a href="{{ url('phieu/cua-toi') }}" class="item-voucher @if($slug == 'cua-toi') item-voucher-active @endif">Của tôi</a>
            <a href="{{ url('phieu/da-tang') }}" class="item-voucher @if($slug == 'da-tang') item-voucher-active @endif">Đã tặng</a>
        </div>
        <div class="content-voucher">
            @foreach($orders as $order)
                @if($order->status_id == 2)
                    <a href="{{ route('detailmy-vote', [$order->order_product_id]) }}" class="voucher-item">
                @else
                    <div class="voucher-item">
                @endif
                    <img src="{{ asset('assets/images/Subtract.png') }}" class="img-voucher">
                    <img src="{{ asset($order->shop_src) }}" alt="{{ @$order->product_name }}" class="logo-trademark">
                    <div class="line-voucher"></div>
                    <div class="content-item-voucher">
                        <p class="name-voucher-product">{{ @$order->product_name }}</p>
                        <p class="price-voucher-product">
                            Đơn giá: {{ number_format(@$order->unit_price, 0, ',', '.') }}đ
                            @if($order->status_id == 1)
                                <span>(Chờ xác nhận)</span>
                            @endif
                        </p>
                        <div class="content-voucher-product">
                            Số lượng còn lại: {{ number_format(@$order->quantity, 0, ',', '.') }}
                        </div>
                        @if($slug == 'da-tang')
                            <div class="content-voucher-product">
                                Số điện thoại người nhận: {{ @$order->receiver_phone }}
                            </div>
                        @endif
                    </div>
                    <div class="expiry">
                        Từ: {{ \Carbon\Carbon::parse(@$order->product_start_date)->format('d/m/Y') }} đến
                        {{ \Carbon\Carbon::parse(@$order->product_end_date)->format('d/m/Y') }}
                    </div>
                @if($order->status_id == 2)
                    </a>
                @else
                    </div>
                @endif
            @endforeach
        </div>
    </section>
@stop

@section('script_page')
@stop
