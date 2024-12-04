@extends('web.index')
@section('title','Tạo Đơn Hàng')

@section('style_page')
    <link rel="stylesheet" href="{{asset('assets/css/create-order.css')}}">
@stop
{{-- Content of page --}}
@section('content')
    <form action="{{route('create-order.buy-now.submit')}}" method="POST" id="buy-now-form">
        @csrf
        <div class="container">
            <div class="row">
                <div class="col-md-2 col-4 product-image-col">
                    <img src="{{ asset($product->src) }}" alt="{{ @$product->name }}" class="product-image" />
                </div>
                <div class="col-md-10 col-8 product-content">
                    <h5 class="product-name mb-0">{{ @$product->name }}</h5>
                    <p class="product-price">Đơn giá: <span style="font-weight: 600;">{{ number_format($product->price, 0, ',', '.') }} VNĐ</span></p>
                    <p class="product-price">Số lượng còn lại: <span style="font-weight: 600;">{{ number_format($product->quantity, 0, ',', '.') }}</span></p>
                </div>
            </div>
            <div class="buyer-info">
                <h6 class="buyer-title">Thông tin người gửi</h6>
                <div class="buyer-form">
                    <div class="input-detail">
                        <label for="full_name" class="label-field">Tên của bạn</label>
                        <input type="text" class="input-field" name="full_name" value="{{ $user->full_name ?? '' }}" readonly />
                    </div>
                    <div class="input-detail">
                        <label for="email" class="label-field">Email của bạn</label>
                        <input type="text" class="input-field" name="email" value="{{ $user->email ?? '' }}" readonly />
                    </div>
                    <div class="input-detail">
                        <label for="email" class="label-field">Số điện thoại của bạn</label>
                        <input type="text" class="input-field" name="phone" value="{{ $user->phone ?? '' }}" readonly />
                    </div>
                </div>
                <div class="buy-for">
                    <h6>Mua cho người khác</h6>
                    <label class="buy-switch">
                        <input type="checkbox" id="toggleReceiverInfo" >
                        <span class="slider"></span>
                    </label>
                    <input type="hidden" name="buy_for" id="buyForInput" value="{{ old('buy_for', 1) }}">
                </div>
                <div class="buy-note">
                    <div class="input-detail">
                        <label for="note" class="label-field">Lời nhắn</label>
                        <textarea rows="5" placeholder="Nhập tin nhắn của bạn muốn gửi đến người nhận ở đây" name="note" class="input-field">{{ old('note') }}</textarea>
                    </div>
                </div>
                <div class="buy-for-me-quantity">
                    <div class="input-detail">
                        <label for="quantity" class="label-field">Nhập số lượng</label>
                        <input type="number" class="input-field for-me-quantity" name="quantity" id="receiver-number" value="{{ old('quantity') }}" min="0" max="{{ $product->quantity }}">
                    </div>
                </div>
            </div>
            <div class="receiver-info">
                <div class="receiver-header">
                    <h6 class="receiver-title">Thông tin người nhận</h6>
                    <div class="excel-upload">
                        <img src="{{ asset('assets/images/excel-icon.png') }}" alt="Excel icon" class="excel-icon" />
                        <span class="upload-excel-text">Upload file excel</span>
                        <input type="file" id="excelFileInput" style="display: none;" accept=".xlsx, .xls" />
                        <div class="tooltip-text">Vui lòng upload file excel với 2 cột: Số điện thoại và Số lượng</div>
                    </div>
                </div>
                <div class="example-excel">
                    <button class="download-link" type="button" onclick="window.location.href='{{ route('download.example') }}'">
                        Tải xuống File mẫu
                    </button>
                </div>
                <div class="receiver-detail">
                    <div class="d-flex justify-content-between align-items-center mb-3 wrap-mobile">
                        <label class="label-field">Số điện thoại của người nhận*</label>
                        <div class="add-receiver-btn">
                            <img src="{{ asset('assets/images/plus-icon.png') }}" alt="Plus icon" class="plus-icon" />
                            <span class="add-receiver-text text-nowrap">Thêm người nhận</span>
                        </div>
                    </div>
                    <div class="list-receiver" id="listReceiver">
                        @php
                            $oldReceivers = old('receivers', []);
                            $receiverCount = max(count($oldReceivers), 1);
                        @endphp

                        @for ($index = 0; $index < $receiverCount; $index++)
                            <div class="receiver-item" data-index="{{ $index }}">
                                <div class="ordinal-number">{{ $index + 1 }}</div>
                                <input type="text" placeholder="0123456789" name="receivers[{{ $index }}][phone]" class="receiver-phone"
                                       value="{{ old("receivers.$index.phone") }}">
                                <input type="number" name="receivers[{{ $index }}][quantity]" class="receiver-number receiver-quantity"
                                       value="{{ old("receivers.$index.quantity", 1) }}">
                                <img src="{{ asset('assets/images/trash-icon.png') }}" alt="trash icon" class="trash-icon">
                            </div>
                        @endfor
                    </div>

                </div>
            </div>
            <div class="receiver-payment">
                <span class="payment-total">
                    Số tiền cần thanh toán:
                    <span class="fw-bold total-money"></span>
                </span>
                <button type="submit" class="payment-button">
                    <p>Mua ngay</p>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
            <input type="hidden" value="{{ $product->id }}" name="product_id">
        </div>
    </form>
@stop
@section('script_page')
    <script src="{{ asset('assets/js/create-order.js') }}"></script>
    <script>
        const price = {{ $product->price }};
        const deleteIcon = "{{ asset('assets/images/trash-icon.png') }}";
        const maxQuantity = {{ $product->quantity ?? 0 }};
    </script>
    <script>
        document.getElementById('buy-now-form').addEventListener('submit', function (event) {
            event.preventDefault();
            showLoading();
            setTimeout(() => event.target.submit(), 100);
        });
        window.addEventListener('pageshow', hideLoading);
    </script>
@stop
