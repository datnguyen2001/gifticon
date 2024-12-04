@extends('web.index')
@section('title','Trang Cá Nhân')

@section('style_page')
    <link rel="stylesheet" href="{{asset('assets/css/create-order.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/product.css')}}">
@stop
{{--content of page--}}
@section('content')
    @include('web.partials.failed-alert')
    @include('web.partials.warning-alert')
    @php
        $userLogin = session('jwt_token') ? \Tymon\JWTAuth\Facades\JWTAuth::setToken(session('jwt_token'))->authenticate() : null;
    @endphp
    <a href="{{route('home')}}" class="line-back">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Trở lại</span>
    </a>
    <div class="product-detail">
        <div class="product-image-wrapper">
            <img src="{{asset($product->src)}}" alt="Product" class="product-image"/>
        </div>
        <div class="product-information">
            <h5 class="product-name">{{$product->name ?? 'N/A'}}</h5>
            <div class="product-field">
                <label class="title-field">Giá thông thường:</label>
                <div class="item-field">{{ number_format($product->price ?? 0, 0, ',', '.') }}đ</div>
            </div>
            <div class="product-field">
                <label class="title-field">Giá chào bán:</label>
                <div class="item-field">{{ number_format($product->price ?? 0, 0, ',', '.') }}đ</div>
            </div>
            <div class="product-field">
                <label class="title-field">Số lượng còn lại:</label>
                <div class="item-field">{{ number_format($product->quantity ?? 0, 0, ',', '.') }}</div>
            </div>
            <div class="product-field">
                <label class="title-field">Đã áp dụng giảm giá:</label>
                <div class="item-field">Áp dụng giảm giá theo cấp độ thành viên</div>
            </div>
            <div class="product-field member-wrapper">
                <label class="title-field">Giá chiết khấu theo cấp độ thành viên:</label>
                <div class="member-field">
                    @foreach($memberShip as $member)
                        <div class="member-item">
                            <p>{{$member->name}} ({{$member->discount_percent}}%)</p>
                            <span>{{ number_format($product->price * (100 - $member->discount_percent) / 100, 0, ',', '.') }}đ</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="product-field flex-column">
                <label class="title-field">Cơ sở áp dụng:</label>
                <div class="location-field">
                    @foreach($productLocations as $productLocation)
                        <div class="location-item">
                            <img src="{{asset('assets/images/location-icon.png')}}" alt="Location" class="location-icon" />
                            <p class="location-text">{{$productLocation->location ?? 'N/A'}}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="product-field">
                <label class="title-field">Thời hạn sử dụng:</label>
                <div class="item-field">
                    Từ {{ \Carbon\Carbon::parse($product->start_date)->format('d/m/Y') }} -
                    Đến {{ \Carbon\Carbon::parse($product->end_date)->format('d/m/Y') }}
                </div>
            </div>
            <div class="button-wrapper mt-3">
                <a href="javascript:void(0)"
                   class="add-to-cart"
                   data-quantity="{{ $product->quantity ?? 0 }}">Thêm vào giỏ hàng</a>
                @include('web.product.modal-add-cart')
                <a href="javascript:void(0)"
                   class="buy-now"
                   data-quantity="{{ $product->quantity ?? 0 }}"
                   data-route="{{ route('create-order.buy-now.index', ['productID' => $product->id]) }}">Mua ngay</a>
            </div>
        </div>
    </div>
    <div class="related-information-wrapper">
        <div class="related-information-header d-flex justify-content-between">
            <div class="d-flex">
                @php
                    $activeTab = 'detail';
                @endphp
                <div
                    class="product-wrapper {{ $activeTab === 'detail' ? 'active' : '' }}"
                    onclick="setActiveTab('detail')"
                >
                    Chi tiết sản phẩm
                </div>
                <div
                    class="store-wrapper {{ $activeTab === 'guide' ? 'active' : '' }}"
                    onclick="setActiveTab('guide')"
                >
                    Hướng dẫn sử dụng
                </div>
            </div>
        </div>

        <div class="tab-content">
            <div class="detail-tab" style="display: {{ $activeTab === 'detail' ? 'block' : 'none' }};">
                {!! $product->describe ?? '' !!}
            </div>
            <div class="guide-tab" style="display: {{ $activeTab === 'guide' ? 'block' : 'none' }};">
                {!! $product->guide ?? '' !!}
            </div>
        </div>
    </div>
@stop
@section('script_page')
    <script src="{{asset('assets/js/create-order.js')}}"></script>
    <script src="{{asset('assets/js/product.js')}}"></script>
    <script>
        const price = {{ $product->price }};
        const deleteIcon = "{{ asset('assets/images/trash-icon.png') }}";
        const maxQuantity = {{ $product->quantity ?? 0 }};
        const userLogin = @json($userLogin);
    </script>
@stop
