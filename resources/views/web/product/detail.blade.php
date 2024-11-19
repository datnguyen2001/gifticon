@extends('web.index')
@section('title','Trang Cá Nhân')

@section('style_page')
    <link rel="stylesheet" href="{{asset('assets/css/product.css')}}">
@stop
{{--content of page--}}
@section('content')
    <a href="{{route('home')}}" class="line-back">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Trở lại</span>
    </a>
    <div class="product-detail">
        <div class="product-image-wrapper">
            <img src="{{asset('assets/images/product-image.png')}}" alt="Product" class="product-image"/>
        </div>
        <div class="product-information">
            <h5 class="product-name">Hi Tea Vải</h5>
            <div class="product-field">
                <label class="title-field">Giá thông thường:</label>
                <div class="item-field">100,000đ</div>
            </div>
            <div class="product-field">
                <label class="title-field">Giá chào bán:</label>
                <div class="item-field">90,000đ</div>
            </div>
            <div class="product-field">
                <label class="title-field">Đã áp dụng giảm giá:</label>
                <div class="item-field">Áp dụng giảm giá theo cấp độ thành viên</div>
            </div>
            <div class="product-field member-wrapper">
                <label class="title-field">Giá chiết khấu theo cấp độ thành viên:</label>
                <div class="member-field">
                    <div class="member-item">
                        <p>Bạc (5%)</p>
                        <span>85,000đ</span>
                    </div>
                    <div class="member-item">
                        <p>Vàng (10%)</p>
                        <span>85,000đ</span>
                    </div>
                    <div class="member-item">
                        <p>Kim cương (15%)</p>
                        <span>75,000đ</span>
                    </div>
                </div>
            </div>
            <div class="product-field flex-column">
                <label class="title-field">Cơ sở áp dụng:</label>
                <div class="location-field">
                    <div class="location-item">
                        <img src="{{asset('assets/images/location-icon.png')}}" alt="Location" class="location-icon" />
                        <p class="location-text">90 Hoàng Ngân, Trung Hoà, Cầu Giấy, Hà Nội</p>
                    </div>
                    <div class="location-item">
                        <img src="{{asset('assets/images/location-icon.png')}}" alt="Location" class="location-icon" />
                        <p class="location-text">90 Hoàng Ngân, Trung Hoà, Cầu Giấy, Hà Nội</p>
                    </div>
                    <div class="location-item">
                        <img src="{{asset('assets/images/location-icon.png')}}" alt="Location" class="location-icon" />
                        <p class="location-text">90 Hoàng Ngân, Trung Hoà, Cầu Giấy, Hà Nội</p>
                    </div>
                </div>
            </div>
            <div class="product-field">
                <label class="title-field">Thời hạn sử dụng:</label>
                <div class="item-field">12/12/2012 - 20/12/2024</div>
            </div>
            <div class="button-wrapper">
                <button class="add-to-cart">Thêm vào giỏ hàng</button>
                <button class="buy-now">Mua ngay</button>
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
                Nội dung chi tiết sản phẩm
            </div>
            <div class="guide-tab" style="display: {{ $activeTab === 'guide' ? 'block' : 'none' }};">
                Nội dung hướng dẫn sử dụng
            </div>
        </div>
    </div>
@stop
@section('script_page')
    <script src="{{asset('assets/js/product.js')}}"></script>

@stop
