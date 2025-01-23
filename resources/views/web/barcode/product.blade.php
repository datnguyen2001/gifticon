<link rel="stylesheet" href="{{asset('assets/css/voucher.css')}}">
<section class="box-voucher">
    <div class="box-item-img-voucher">
        <div class="img-ticket-voucher">
            <p class="name-sp-voucher">{{@$product->product_name}}</p>
            <div class="d-flex justify-content-center">
                <img src="{{asset(@$product->product_src)}}" class="img-sp-voucher">
            </div>
            <div class="line-barcode">
                {!! @$product->barcode !!}
            </div>
            <p class="name-cs">Cơ sở áp dụng:</p>
            @if(!empty($product->locations) && is_array($product->locations))
                @foreach($product->locations as $location)
                    <div class="line-address">
                        <img src="{{asset('assets/images/location-icon.png')}}" class="icon-location">
                        <span>{{$location}}</span>
                    </div>
                @endforeach
            @else
                <p class="mb-0">Không có cơ sở</p>
            @endif
            <div class="line-bottom-voucher">
                <p class="code-voucher">Mã Voucher: <span>{!! @$product->voucher_id !!}</span></p>
            </div>
            <div class="circle1"><img src="{{asset('assets/images/ellipse1.png')}}" alt=""></div>
            <div class="circle2"><img src="{{asset('assets/images/ellipse1.png')}}" alt=""></div>
        </div>
    </div>
</section>
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
