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

                @foreach($carts as $cart)
                    <div class="item-cart">
                        <div class="d-flex" style="gap: 10px">
                            <div class="d-flex flex-column align-center justify-content-center">
                                <input type="checkbox" class="tick-box"  data-total-price="{{ $cart->total_price }}" {{ $cart->is_selected ? 'checked' : '' }}>
                            </div>
                            <img src="{{asset($cart->product->src)}}" class="img-sp-cart">
                            <div class="content-item-cart">
                                <p class="name-sp-cart">{{$cart->product->name ?? ''}}</p>
                                <p class="price-sp-cart">{{ number_format($cart->total_price, 0, ',', '.') }} VNĐ</p>
                                <div class="content-sp-cart">
                                    HSD: Từ <span class="fw-bold">{{ \Carbon\Carbon::parse($cart->product->start_date)->format('d/m/Y') }}</span>
                                    đến <span class="fw-bold">{{ \Carbon\Carbon::parse($cart->product->end_date)->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>
                        <form action="{{route('cart.delete')}}" method="POST" class="btn-delete-cart" id="delete-cart-form">
                            @csrf
                            <input type="hidden" name="cart_id" value="{{ $cart->id }}">
                            <button type="submit">
                                <img src="{{ asset('assets/images/icon-delete-cart.png') }}" alt="Delete">
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
            <div class="item-right">
                <p class="name-order">Tóm tắt đơn hàng</p>
                <div class="d-flex justify-content-between align-center">
                    <span class="name-total-price">Tiền hàng</span>
                    <span class="content-total-price">0đ</span>
                </div>
                <form action="{{ route('cart.payment') }}" method="POST" id="payment-form">
                    @csrf
                    <input type="hidden" name="selected_cart_id[]" value="" id="selected_cart_id"/>
                    <button type="submit" name="action" value="payment" class="btn-pay">Thanh toán</button>
                </form>
            </div>
        </div>
    </section>

@stop
@section('script_page')
    <script src="{{ asset('assets/js/cart.js') }}"></script>
    <script>
        document.getElementById('delete-cart-form').addEventListener('submit', function (event) {
            event.preventDefault();
            showLoading();
            setTimeout(() => event.target.submit(), 100);
        });
        document.getElementById('payment-form').addEventListener('submit', function (event) {
            event.preventDefault();
            showLoading();
            setTimeout(() => event.target.submit(), 100);
        });
        window.addEventListener('pageshow', hideLoading);
    </script>
@stop
