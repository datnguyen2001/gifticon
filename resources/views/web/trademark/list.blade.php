@extends('web.index')
@section('title','Thương hiệu yêu thích')

@section('style_page')
    <link rel="stylesheet" href="{{asset('assets/css/trademark.css')}}">
@stop
{{--content of page--}}
@section('content')
    <section class="box-trademark">
        <a href="{{route('home')}}" class="line-back">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Trở lại</span>
        </a>
        <p class="title-trademark">{{$title}}</p>
        <div class="box-menu-trademark">
            @foreach($categories as $key => $category)
                <div class="item-menu-trademark" data-category-id="{{ $category->id }}">
                    <img src="{{ asset($category->src) }}" alt="{{ $category->name }}">
                    <span>{{ $category->name }}</span>
                </div>
            @endforeach
        </div>
        <div class="search-wrapper">
            <div class="filter-search">
                <span class="price-range-title">Khoảng giá</span>
                <div class="price-range">
                    <input type="text" placeholder="Từ" id="start-price" class="input-price"/>
                    <span>-</span>
                    <input type="text" placeholder="Đến" id="end-price" class="input-price"/>
                </div>
                <div class="search-container">
                    <input type="text" id="product-name" class="search-input" placeholder="Tìm kiếm theo tên sản phẩm" />
                    <button class="search-button" id="search-button">
                        <img src="{{asset('assets/images/search-icon.png')}}" alt="Search icon" class="search-icon">
                    </button>
                </div>
                <button id="apply-button" class="apply-button">Áp dụng</button>
            </div>

        </div>
        <div class="line-center-trademark"></div>

        <div class="content-you-like mt-4">
            @foreach($products as $product)
                <div class="item-product" data-category-id="{{ $product->category_id }}" data-price="{{ $product->price }}"
                     data-name="{{ strtolower($product->name) }}">
                    <div class="box-img-product">
                        <img src="{{ asset($product->src) }}" class="img-product">
                        <i class="fa-solid fa-heart fa-heart-sp"></i>
                    </div>
                    <div class="name-product">{{ $product->name }}</div>
                    <span class="price-product">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                </div>
            @endforeach
        </div>
    </section>

@stop
@section('script_page')
    <script src="{{asset('assets/js/trademark.js')}}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var trademarkItems = document.querySelectorAll('.item-menu-trademark');
            var products = document.querySelectorAll('.item-product');

            function showProducts(categoryId) {
                products.forEach(function(product) {
                    if (product.getAttribute('data-category-id') === categoryId) {
                        product.style.display = 'flex';
                    } else {
                        product.style.display = 'none';
                    }
                });
            }

            // Initialize: select the first category and show its products
            if (trademarkItems.length > 0) {
                var firstCategoryId = trademarkItems[0].getAttribute('data-category-id');
                showProducts(firstCategoryId);
                trademarkItems[0].classList.add('menu-trademark-active');
            }

            trademarkItems.forEach(function(item) {
                item.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent default behavior (if any)

                    // Remove 'menu-trademark-active' class from all items
                    trademarkItems.forEach(function(el) {
                        el.classList.remove('menu-trademark-active');
                    });

                    // Add 'menu-trademark-active' class to the clicked item
                    this.classList.add('menu-trademark-active');

                    // Get the category ID from the clicked item
                    var categoryId = this.getAttribute('data-category-id');

                    // Show products of the selected category
                    showProducts(categoryId);

                    $('#start-price, #end-price, #product-name').val('');
                    $('.no-products-message').remove();
                });
            });
        });
    </script>
@stop
