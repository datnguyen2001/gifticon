<div class="box-header">
    <div class="header">
         <div class="header-main">
             <a href="{{route('home')}}" class="title-logo">Gifticon</a>
             <div class="box-search">
                 <input type="text" class="input-search" placeholder="Tìm kiếm bất cứ điều gì" id="search-input">
                 <div class="icon-search"><img src="{{asset('assets/images/icon-search.png')}}" alt=""></div>
                 <div class="box-search-result" id="search-results"></div>
             </div>
            <div class="d-flex align-items-center box-infor-login">
                @if($user)
                    <div class="profile-menu-container">
                        <a href="{{route('profile.index')}}" class="link-login">
                            <img src="{{asset('assets/images/user-icon.png')}}">
                            <span>{{$user->full_name}}</span>
                        </a>
                        <div class="profile-menu">
                            <a href="{{route('profile.index')}}"><img src="{{asset('assets/images/user-icon.png')}}" alt="user" class="profile-icon" /> Trang cá nhân</a>
                            <a href="javascript:void(0);" onclick="setChangePassTab();"><img src="{{asset('assets/images/lock-icon.png')}}" alt="lock" class="profile-icon" /> Thay đổi mật khẩu</a>
                            <a href="javascript:void(0);" onclick="logOutOnClick();"><img src="{{asset('assets/images/logout-icon.png')}}" alt="logout icon" class="profile-icon" /> Đăng xuất</a>
                        </div>
                    </div>
                    @else
                    <a href="{{route('login')}}" class="link-login">Đăng nhập</a>
                @endif
                    <a href="javascript:void(0);" class="icon-header" onclick="setLoveTab();">
                        <img src="{{asset('assets/images/heart-icon.png')}}" alt="Heart Icon">
                    </a>
                <a href="#" class="icon-header"><img src="{{asset('assets/images/icon-cart.png')}}" ></a>
            </div>
         </div>
        <div class="header-bottom">
{{--            <div class="item-menu-header"><img src="{{asset('assets/images/Container.png')}}" alt=""> <span>Gif card</span></div>--}}
            <a href="{{route('trademark')}}" class="item-menu-header">Tất cả các thương hiệu</a>
            <a href="{{route('my-vote')}}" class="item-menu-header">Quà của tôi</a>
            <a href="#" class="item-menu-header">Mua số lượng lớn</a>
            @if(!$user)
            <a href="{{route('register')}}" class="item-menu-header">Đăng ký</a>
                @endif
        </div>
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

        $('#search-input').on('input', function () {
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
                $('#search-results').empty().hide();
            }
        });

        function displaySearchResults(data) {
            const resultsContainer = $('#search-results');
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
                            <div class="item-type">${product.price}</div>
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
            if (!$(event.target).closest('.box-search').length) {
                $('#search-results').empty().hide();
            }
        });
    });

</script>
