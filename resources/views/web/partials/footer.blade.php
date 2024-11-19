@php
    $setting = \App\Models\SettingModel::first();
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
                    <a href="#" class="item-menu-footer">Các câu hỏi thường gặp</a>
                    <a href="#" class="item-menu-footer">Gửi yêu cầu hỗ trợ</a>
                    <a href="#" class="item-menu-footer">Hướng dẫn đặt hàng</a>
                    <a href="#" class="item-menu-footer">Hướng dẫn hủy đơn hàng</a>
                    <a href="#" class="item-menu-footer">Phương thức vận chuyển</a>
                    <a href="#" class="item-menu-footer">Chính sách đổi trả</a>
                    <a href="#" class="item-menu-footer">Hỗ trợ khách hàng:lienhe@krmedi.com</a>
                    <a href="#" class="item-menu-footer">Báo lỗi bảo mật:technical@krmedi.com</a>
                </div>
            </div>
            <div class="item-footer">
                <p class="title-footer">Về chúng tôi</p>
                <div class="box-menu-footer">
                    <a href="#" class="item-menu-footer">Giới thiệu</a>
                    <a href="#" class="item-menu-footer">Blog Kinh Doanh</a>
                    <a href="#" class="item-menu-footer">Tuyển dụng</a>
                    <a href="#" class="item-menu-footer">Điều khoản sử dụng</a>
                    <a href="#" class="item-menu-footer">Thông tin thanh toán</a>
                    <a href="#" class="item-menu-footer">Chính sách bảo mật thanh toán</a>
                    <a href="#" class="item-menu-footer">Chính sách bảo mật thông tin</a>
                </div>
            </div>
            <div class="item-footer">
                <p class="title-footer">Kết nối với chúng tôi</p>
                <div class="d-flex align-center justify-content-between" style="max-width: 200px">
                    <a href="#" class="item-menu-footer"><img src="{{asset('assets/images/icon-gg.png')}}" ></a>
                    <a href="#" class="item-menu-footer"><img src="{{asset('assets/images/icon-fb.png')}}" ></a>
                    <a href="#" class="item-menu-footer"><img src="{{asset('assets/images/icon-tw.png')}}" ></a>
                    <a href="#" class="item-menu-footer"><img src="{{asset('assets/images/icon-apple.png')}}" ></a>
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
    <a href="#">Giỏ hàng</a>
    <a href="#">Profile</a>
</div>
