@extends('web.index')
@section('title','Chi tiết voucher')

@section('style_page')
    <link rel="stylesheet" href="{{asset('assets/css/voucher.css')}}">
@stop
{{--content of page--}}
@section('content')
    <section class="box-voucher">
        <a href="{{url('phieu/cua-toi')}}" class="line-back">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Trở lại</span>
        </a>
        <div class="box-content-voucher">
            <p class="name-product-voucher">Hi Tea Vải</p>
            <p class="price-product-voucher">200.000đ</p>
            <p class="name-cs">Cơ sở áp dụng:</p>
            @for($i=0;$i<4;$i++)
                <div class="line-address">
                    <img src="{{asset('assets/images/location-icon.png')}}" class="icon-location">
                    <span>90 Hoàng Ngân, Trung Hoà, Cầu Giấy, Hà Nội</span>
                </div>
            @endfor

            <div class="accordion accordion-flush box-info-sp-voucher" id="accordionFlushExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                            Chi tiết sản phẩm
                        </button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <div class="title-detail-sp-voucher">Tên sản phẩm: <span>Hi Tea Vải</span></div>
                            <div class="title-detail-sp-voucher">Giới thiệu sản phẩm:</div>
                            <div class="content-detail-sp-voucher">Chút ngọt ngào của Vải, mix cùng vị chua thanh tao từ trà hoa Hibiscus, mang đến cho bạn thức uống đúng chuẩn vừa ngon, vừa healthy.</div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                            Hướng dẫn sử dụng
                        </button>
                    </h2>
                    <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <div class="content-detail-sp-voucher">Chút ngọt ngào của Vải, mix cùng vị chua thanh tao từ trà hoa Hibiscus, mang đến cho bạn thức uống đúng chuẩn vừa ngon, vừa healthy.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4 ">
                <a href="#" class="btn-use-voucher">Sử dụng</a>
            </div>

        </div>
    </section>

@stop
@section('script_page')

@stop
