@extends('web.index')
@section('title','Thanh toán')

@section('style_page')
    <link rel="stylesheet" href="{{asset('assets/css/order.css')}}">
@stop
{{--content of page--}}
@section('content')

    <form action="{{route('create-order.confirm')}}" method="POST" id="confirm-form">
        @csrf
        <section class="box-order">
            <a href="{{route('home')}}" class="line-back">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Trở lại</span>
            </a>
            <div class="box-content-order">
                <div class="item-left-order">
                    @foreach($carts as $cart)
                        <input type="hidden" name="carts[{{ $loop->index }}][product_id]" value="{{ $cart->product->id }}">
                        <input type="hidden" name="carts[{{ $loop->index }}][message]" value="{{ @$cart->message }}">
                        <input type="hidden" name="carts[{{ $loop->index }}][id]" value="{{ @$cart->id }}">
                        <div class="item-info-order">
                            <div class="item-order">
                                <div class="col-item-order">
                                    <div class="item-product-order">
                                        <img src="{{asset($cart->product->src)}}" class="img-item-product-order">
                                        <div class="item-content-product-order">
                                            <span class="name-product-order">{{$cart->product->name}}</span>
                                            <div class="info-product-order">
                                                HSD: Từ <span class="fw-bold">{{ \Carbon\Carbon::parse($cart->product->start_date)->format('d/m/Y') }}</span>
                                                đến <span class="fw-bold">{{ \Carbon\Carbon::parse($cart->product->end_date)->format('d/m/Y') }}</span>
                                            </div>
                                            <div class="info-product-order">
                                                Số lượng <span class="fw-bold">{{$cart->quantity}}</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="item-order-use">
                                <span class="name-product-order">Cảm ơn bạn</span>
                                <div class="info-product-order">{{@$cart->message}}</div>
                                <div class="info-product-order">Email: {{$user->email ?? ''}}</div>
                                <div class="info-product-order">Số điện thoại: {{$user->phone ?? ''}}</div>
                                <div class="info-product-order price-product-order">Số tiền: <span>{{ number_format($cart->total_price, 0, ',', '.') }} VNĐ</span></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="item-order-qr">
                    <div class="title-qr">Qr thanh toán tiền</div>
                    <img src="{{asset('assets/images/img-qr.png')}}" class="img-qr-bank">
                    <div class="content-qr"><span>Trần đình phi</span><span>1018358676</span></div>
                </div>
            </div>
            <p class="total-payment">
                Tổng số tiền thanh toán:
                <span>{{ number_format($totalPayment, 0, ',', '.') }} VNĐ</span>
                <input type="hidden" name="total_price" value="{{ $totalPayment }}">
            </p>
            <button type="submit" class="btn-success-order">Xác nhận đã thanh toán</button>
        </section>
    </form>

@stop
@section('script_page')
<script>
    document.getElementById('confirm-form').addEventListener('submit', function (event) {
        event.preventDefault();
        showLoading();
        setTimeout(() => event.target.submit(), 100);
    });
    window.addEventListener('pageshow', hideLoading);
</script>
@stop
