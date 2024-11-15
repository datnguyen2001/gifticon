@extends('web.index')
@section('title','Tạo Đơn Hàng')

@section('style_page')
    <link rel="stylesheet" href="{{asset('assets/css/create-order.css')}}">
@stop
{{--content of page--}}
@section('content')
@include('web.partials.loading')
    <div class="container">
        @php
            $price = 200000;
        @endphp
        <div class="row">
            <div class="col-2">
                <img src="{{asset('assets/images/demo-product.png')}}" alt="Demo Product" class="product-image" />
            </div>
            <div class="col-10 product-content">
                <h5 class="product-name mb-0">Quán cà phê đá Starbucks Americano Size L</h5>
                <p class="product-price">Đơn giá: <span>{{ number_format($price, 0, ',', '.') }} VNĐ</span></p>
            </div>
        </div>
        <div class="buyer-info">
            <h6 class="buyer-title">Thông tin người gửi</h6>
            <div class="buyer-form">
                <div class="input-detail">
                    <label for="full_name" class="label-field">Tên của bạn</label>
                    <input type="text" class="input-field" name="full_name" value="{{$user->full_name}}" readonly/>
                </div>
                <div class="input-detail">
                    <label for="email" class="label-field">Email của bạn</label>
                    <input type="text" class="input-field" name="email" value="{{$user->email}}" readonly/>
                </div>
                <div class="input-detail">
                    <label for="email" class="label-field">Số điện thoại của bạn</label>
                    <input type="text" class="input-field" name="phone" value="{{$user->phone}}" readonly/>
                </div>
            </div>
            <div class="buy-for">
                <h6>Mua cho người khác</h6>
                <label class="buy-switch">
                    <input type="checkbox" id="toggleReceiverInfo">
                    <span class="slider"></span>
                </label>
            </div>
            <div class="buy-note">
                <div class="input-detail">
                    <label for="email" class="label-field">Lời nhắn</label>
                    <textarea rows="5" placeholder="Nhập tin nhắn của bạn muốn gửi đến người nhận ở đây" name="note" class="input-field"></textarea>
                </div>
            </div>
            <div class="buy-for-me-quantity">
                <div class="input-detail">
                    <label for="email" class="label-field">Nhập số lượng</label>
                    <input type="number" class="input-field for-me-quantity" name="quantity" id="receiver-number"/>
                </div>
            </div>
        </div>
        <div class="receiver-info">
            <div class="receiver-header">
                <h6 class="receiver-title">Thông tin người nhận</h6>
                <div class="excel-upload">
                    <img src="{{asset('assets/images/excel-icon.png')}}" alt="Excel icon" class="excel-icon"/>
                    <span class="upload-excel-text">Upload file excel</span>
                    <input type="file" id="excelFileInput" style="display: none;" accept=".xlsx, .xls" />
                    <div class="tooltip-text">Vui lòng upload file excel với 2 cột: Số điện thoại và Số lượng</div>
                </div>
            </div>
            <div class="receiver-detail">
                <label class="label-field">Số điện thoại của người nhận*</label>
                <div class="list-receiver" id="listReceiver">
                    @for ($i = 1; $i <= 30; $i++)
                        <div class="receiver-item" data-index="{{ $i }}">
                            <div class="ordinal-number">{{ $i }}</div>
                            <input type="text" placeholder="0123456789" class="receiver-phone">
                            <input type="number" value="1" class="receiver-number receiver-quantity" id="receiver-number">
                            <img src="{{ asset('assets/images/trash-icon.png') }}" alt="trash icon" class="trash-icon" />
                        </div>
                    @endfor
                </div>
            </div>
        </div>
        <div class="receiver-payment">
                <span class="payment-total">
                    Số tiền cần thanh toán:
                    <span class="fw-bold total-money">300,000vnđ</span>
                </span>
            <div class="payment-button">
                <p>Thanh toán</p>
                <i class="fa-solid fa-arrow-right"></i>
            </div>
        </div>
    </div>
@stop
@section('script_page')
    <script src="{{asset('assets/js/create-order.js')}}"></script>
    <script>
        const price = {{ $price }};
        const deleteIcon = "{{ asset('assets/images/trash-icon.png') }}";
    </script>
@stop
