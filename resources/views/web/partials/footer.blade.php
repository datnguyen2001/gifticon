@php
    $setting = \App\Models\SettingModel::first();
    $client_support = \App\Models\FooterModel::where('type',1)->get();
    $about_us = \App\Models\FooterModel::where('type',2)->get();
@endphp
<div class="box-footer">
    <div class="footer">
        <div class="footer-top">
            <a href="{{route('home')}}" class="title-logo">Gifticon</a>
            <div class="describe-footer">
                {{@$setting->describe}}
            </div>
            <div class="line-infor-cty">
                <div class="box-info-footer">
                    <img src="{{asset('assets/images/icon-phone.png')}}" class="icon-info-footer">
                    <div class="d-flex flex-column ">
                        <span class="title-info-footer">Điện thoại</span>
                        <span class="content-info-footer">{{@$setting->phone}}</span>
                    </div>
                </div>
                <div class="box-info-footer">
                    <img src="{{asset('assets/images/icon-mail.png')}}" class="icon-info-footer">
                    <div class="d-flex flex-column ">
                        <span class="title-info-footer">Email</span>
                        <span class="content-info-footer">{{@$setting->email}}</span>
                    </div>
                </div>
                <div class="box-info-footer" style="max-width: 406px">
                    <img src="{{asset('assets/images/icon-map.png')}}" class="icon-info-footer">
                    <div class="d-flex flex-column ">
                        <span class="title-info-footer">Địa chỉ</span>
                        <span class="content-info-footer">{{@$setting->address}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-main">
            <div class="item-footer">
                <p class="title-footer">Hỗ trợ khách hàng</p>
                <div class="box-menu-footer">
                    @foreach($client_support as $client_supports)
                    <a href="{{route('customer-support',$client_supports->slug)}}" class="item-menu-footer">{{$client_supports->name}}</a>
                   @endforeach
                </div>
            </div>
            <div class="item-footer">
                <p class="title-footer">Về chúng tôi</p>
                <div class="box-menu-footer">
                    @foreach($about_us as $about_uses)
                        <a href="{{route('about-us',$about_uses->slug)}}" class="item-menu-footer">{{$about_uses->name}}</a>
                    @endforeach
                </div>
            </div>
            <div class="item-footer">
                <p class="title-footer">Kết nối với chúng tôi</p>
                <div class="d-flex align-center justify-content-between" style="max-width: 200px">
                    <a href="mailto:{{ @$settings->email }}" class="item-menu-footer"><img src="{{asset('assets/images/icon-gg.png')}}" ></a>
                    <a href="{{ @$settings->facebook }}" class="item-menu-footer"><img src="{{asset('assets/images/icon-fb.png')}}" ></a>
                    <a href="{{ @$settings->twitter }}" class="item-menu-footer"><img src="{{asset('assets/images/icon-tw.png')}}" ></a>
                    <a href="{{ @$settings->zalo }}" class="item-menu-footer"><img src="{{asset('assets/images/icon-apple.png')}}" ></a>
                </div>
                <div class="line-app">
                    <a href="#">
                        <img src="{{asset('assets/images/appStore.png')}}" class="img-app">
                    </a>
                    <a href="">
                        <img src="{{asset('assets/images/chPlay.png')}}" class="img-app">
                    </a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            © 2023. All Rights Reserved Country & Region: Vietnam | China | Japan | Korea
        </div>
    </div>
</div>

<div class="box-footer-mobile">
    <a href="{{route('home')}}" class="item-footer-menu"> <img src="{{asset('assets/images/home.png')}}" class="icon-menu-footer"> Trang chủ</a>
    <a href="#" class="item-footer-menu"> <img src="{{asset('assets/images/shopping-bag.png')}}" class="icon-menu-footer"> Giỏ hàng</a>
    <div class="item-footer-menu" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom" aria-controls="offcanvasBottom"> <img src="{{asset('assets/images/menu.png')}}" class="icon-menu-footer"> Danh mục</div>
    <a href="{{ $user ? route('profile.index') : route('login') }}" class="item-footer-menu"> <img src="{{asset('assets/images/heart-icons.png')}}" class="icon-menu-footer"> Yêu thích</a>
    <a href="{{ $user ? route('profile.index') : route('login') }}" class="item-footer-menu"> <img src="{{asset('assets/images/user-circle.png')}}" class="icon-menu-footer"> Cá nhân</a>
</div>
<div class="offcanvas offcanvas-bottom" tabindex="-1" id="offcanvasBottom" aria-labelledby="offcanvasBottomLabel" style="z-index: 3;border-radius: 32px">
    <div class="offcanvas-body small position-relative" style="padding-top: 28px">
        <button type="button" class="btn-close btn-close-menu" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        <a href="{{route('trademark','all')}}" class="item-menu-footer-offcanvas">Tất cả các thương hiệu</a>
        <a href="{{route('my-vote')}}" class="item-menu-footer-offcanvas">Quà của tôi</a>
        <a href="#" class="item-menu-footer-offcanvas">Mua số lượng lớn</a>
    </div>
</div>
