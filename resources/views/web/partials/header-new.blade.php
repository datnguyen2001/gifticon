<div id="header-new">
    <div id="header_new_in">
        <div class="bookmark"><a href="javascript:void(0);" onclick="setLoveTab();">Yêu thích</a></div>
        <div class="topsearch">
            @if($user)
                <a href="{{route('profile.index')}}">{{$user->full_name}}</a>
                @else
            <a href="{{route('login')}}">Đăng nhập</a>
            @endif
            <div class="box-search-new">
                <input type="text" id="top_search_keyword" class="top_search_keyword" title="Tìm kiếm bất cứ điều gì" name="keyword">
                <span><img src="{{asset('assets/images/topsearch.png')}}" loading="lazy"  alt="Tìm kiếm bất cứ điều gì"></span>
                <div class="box-search-result" id="search-results"></div>
            </div>
        </div>

        <div id="header-main">
            <a href="{{route('home')}}" class="title-logo">
                <img src="{{asset('assets/images/logo.png')}}" alt="" loading="lazy">
            </a>
            <div class="gnb1">
                <ul class="menuNaviUl">
                    <li class="depth1Li">
                        <a href="{{route('trademark','all')}}" class="depth1Link">Danh mục</a>
                        <div class="gnbover box-gnbover-1">
                            <div class="newbrandMenu">
                                <img src="{{asset('assets/images/overbullet.png')}}" loading="lazy" alt="" class="overbullet1">
                                <ul>
                                    @foreach($categories as $item)
                                        <li>
                                            <a href="{{route('trademark',$item->slug)}}">
                                                <img id="offbrandMenu0" loading="lazy" src="{{$item->src}}" loading="lazy" alt="{{$item->name}}">
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="specialMenu">
                                <ul>
                                    <li>
                                        <a href="#"><img src="{{asset('assets/images/menu01_sale.png')}}" loading="lazy" alt="giảm giá"></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="bannerMenu">
                                <a href="#" class="brandshop"><img
                                        src="{{asset('assets/images/btn_newbrandshop.png')}}" loading="lazy"
                                        alt="Đi đến Cửa hàng thương hiệu"></a>
                                <ul>
                                    <li><a href="{{route('product-new','all')}}">
                                            Sản phẩm mới
                                        </a></li>
                                    <li><a href="#">
                                            Chủ đề/Triển lãm đặc biệt
                                        </a></li>
                                </ul>
                            </div>
                        </div>
                    </li>

                    <li class="depth1Li">
                        <a href="{{route('trademark','all')}}" class="depth1Link">Thương hiệu</a>
                    </li>

                    <li class="depth1Li">
                        <a href="#" class="depth1Link">
                            Sự kiện
                        </a>
                        <div class="gnbover box-gnbover-2">
                            <div class="Gevent">
                                <img src="{{asset('assets/images/overbullet.png')}}" loading="lazy" alt="" class="overbullet3">
                                <div class="box-event">
                                    <img
                                        src="https://gifticon.com/upload/GCMS3/event/202503/20c0c281-70fd-4368-918f-19a6fb8b00a9_1741657213996.jpg"
                                        alt="" loading="lazy">
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="depth1Li">
                        <a href="#" class="depth1Link depth1Type">Mua số lượng lớn</a>
                    </li>

                </ul>
            </div>

            <div class="gnb2">
                <ul>
                    <li class="">
                        <a href="#">
                            Tìm kiếm quà tặng</a></li>
                    <li><a href="#">Trợ giúp</a>
                    </li>

                    <li class="on"><a href="{{url('phieu/cua-toi')}}">My gifticon</a></li>
                    <li class=""><a href="#">Khách hàng doanh nghiệp</a></li>
                </ul>
            </div>

{{--            <div class="menuall"><a href="#">Xem toàn bộ ></a></div>--}}
        </div>
    </div>
</div>

<div class="header-new-mobile">
    <div class="topmenu">
        <span class="topmenuL" id="sessioncheck">
             @if($user)
            <a href="{{route('profile.index')}}" class="login">{{$user->full_name}}</a>
                 @else
                <a href="{{route('login')}}" class="login">Đăng nhập</a>
            @endif
        </span>
        <span class="topmenuR">
            <a href="#">Sự kiện</a>
            <a href="{{url('phieu/cua-toi')}}">Quà tặng của tôi</a>
        </span>
    </div>
    <div class="mainsearch">
        <a href="{{route('home')}}"><img src="{{asset('assets/images/logo.png')}}" loading="lazy" alt="" class="img-logo-mob"></a>
        <div class="searchbg">
				<span class="schbar">
					<input type="text" id="top_search_keyword" class="top_search_keyword" name="keyword">
				</span>
            <span class="text-search">tìm kiếm</span>
            <div class="box-search-result" id="search-results"></div>
        </div>
    </div>
    <div id="gnb">
        <ul>
            <li><a href="{{route('home')}}" class="on"><img id="gnb_header1" src="{{asset('assets/images/gnb1_on.gif')}}" alt="">Trang chủ</a></li>
            <li><a href="{{route('trademark','all')}}"><img id="gnb_header2" src="{{asset('assets/images/gnb2.gif')}}" alt="">Thương hiệu</a></li>
            <li><a href="{{route('trademark','all')}}"><img id="gnb_header3" src="{{asset('assets/images/gnb3.gif')}}" alt="">Danh mục</a></li>
            <li><a href="{{route('product-new','all')}}"><img id="gnb_header4" src="{{asset('assets/images/gnb4.gif')}}" alt="">Sản phẩm mới</a></li>
            <li><a href="javascript:void(0);" onclick="setLoveTab();"><img id="gnb_header5" src="{{asset('assets/images/gnb5.gif')}}" alt="">Yêu thích</a></li>
        </ul>
    </div>
</div>

<script>
    function setLoveTab() {
        localStorage.setItem('activeTab', 'love');
        window.location.href = "{{ route('profile.index') }}";
    }

    function setChangePassTab() {
        localStorage.setItem('activeTab', 'change-password');
        window.location.href = "{{ route('profile.index') }}";
    }

    function logOutOnClick() {
        const logoutForm = document.createElement('form');
        logoutForm.method = 'POST';
        logoutForm.action = "{{ route('logout.submit') }}";

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = "{{ csrf_token() }}";

        logoutForm.appendChild(csrfInput);

        document.body.appendChild(logoutForm);
        logoutForm.submit();
    }
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        let searchTimeout;

        $('.top_search_keyword').on('input', function () {
            clearTimeout(searchTimeout);

            const keyword = $(this).val().trim();

            if (keyword.length > 0) {
                searchTimeout = setTimeout(function () {
                    $.ajax({
                        url: '/search',
                        method: 'GET',
                        data: {
                            keyword: keyword
                        },
                        success: function (response) {
                            displaySearchResults(response);
                        },
                        error: function (error) {
                            console.error('Error:', error);
                        }
                    });
                }, 300); // Delay to prevent too many requests
            } else {
                $('.box-search-result').empty().hide();
            }
        });

        function formatPrice(price) {
            return new Intl.NumberFormat('vi-VN').format(price);
        }

        function displaySearchResults(data) {
            const resultsContainer = $('.box-search-result');
            resultsContainer.empty();

            const shops = data.shops;
            const products = data.products;

            if (shops.length === 0 && products.length === 0) {
                resultsContainer.append('<div class="no-results">Không tìm thấy kết quả</div>');
            } else {
                // Display shops
                shops.forEach(function (shop) {
                    const shopItem = `
                    <a href="{{ url('thuong-hieu') }}/${shop.slug}" class="result-item">
                        <img src="{{ asset('') }}${shop.src}" alt="${shop.name}">
                        <div class="item-info">
                            <div class="item-name">${shop.name}</div>
                        </div>
                    </a>
                `;
                    resultsContainer.append(shopItem);
                });

                // Display products
                products.forEach(function (product) {
                    const productItem = `
                    <a href="{{ url('chi-tiet') }}/${product.slug}" class="result-item">
                        <img src="{{ asset('') }}${product.src}" alt="${product.name}">
                        <div class="item-info">
                            <div class="item-name">${product.name}</div>
                            <div class="item-type">${formatPrice(product.price)} VNĐ</div>
                        </div>
                    </a>
                `;
                    resultsContainer.append(productItem);
                });
            }

            resultsContainer.show();
        }

        // Hide the search results when clicking outside
        $(document).on('click', function (event) {
            if (!$(event.target).closest('.box-search-new').length) {
                $('.box-search-result').empty().hide();
            }
        });
    });

</script>
