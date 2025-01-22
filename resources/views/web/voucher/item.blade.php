@extends('web.index')
@section('title','Chi tiết voucher')

@section('style_page')
    <link rel="stylesheet" href="{{asset('assets/css/voucher.css')}}">
@stop
{{--content of page--}}
@section('content')
    <section class="box-voucher">
        <a href="{{route('detailmy-vote', [$orderProductID])}}" class="line-back">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Trở lại</span>
        </a>
        <div class="box-item-img-voucher">
            <div class="img-ticket-voucher">
                <p class="name-sp-voucher">{{@$vouchers->product_name}}</p>
                <div class="d-flex justify-content-center">
                    <img src="{{asset(@$vouchers->product_src)}}" class="img-sp-voucher">
                </div>
                <div class="line-barcode">
                    {!! @$vouchers->barcode !!}
                </div>
                <p class="name-cs">Cơ sở áp dụng:</p>
                @if(!empty($vouchers->locations) && is_array($vouchers->locations))
                    @foreach($vouchers->locations as $location)
                        <div class="line-address">
                            <img src="{{asset('assets/images/location-icon.png')}}" class="icon-location">
                            <span>{{$location}}</span>
                        </div>
                    @endforeach
                @else
                    <p class="mb-0">Không có cơ sở</p>
                @endif
                <div class="line-bottom-voucher">
                    <p class="code-voucher">Mã Voucher: <span>{!! @$vouchers->voucher_id !!}</span></p>
                    <button class="btn-copy-code">Copy mã</button>
                </div>
                <div class="circle1"><img src="{{asset('assets/images/ellipse1.png')}}" alt=""></div>
                <div class="circle2"><img src="{{asset('assets/images/ellipse1.png')}}" alt=""></div>
            </div>
        </div>
    </section>


@stop
@section('script_page')
    <script>
        document.querySelector('.btn-copy-code').addEventListener('click', function () {
            const voucherCode = document.querySelector('.code-voucher span').textContent;

            const tempInput = document.createElement('input');
            tempInput.value = voucherCode;
            document.body.appendChild(tempInput);

            tempInput.select();
            document.execCommand('copy');

            document.body.removeChild(tempInput);

        });

    </script>
@stop
