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
        <p class="name-title">Chế độ quà tặng giúp việc tặng quà trở nên cực kỳ dễ dàng</p>
        <p class="title-trademark">Nhấn để chọn thương hiệu mà bạn muốn tặng </p>
        <div class="box-menu-trademark">
            <a href="#" class="item-menu-trademark menu-trademark-active">
                <img src="{{asset('assets/images/tea.png')}}" >
                <span>Cafe & bánh</span>
            </a>
            <a href="#" class="item-menu-trademark">
                <img src="{{asset('assets/images/tea.png')}}" >
                <span>Siêu thị & Mua sắm</span>
            </a>
            <a href="#" class="item-menu-trademark">
                <img src="{{asset('assets/images/tea.png')}}" >
                <span>Thời trang & Phụ Kiện</span>
            </a>
            <a href="#" class="item-menu-trademark">
                <img src="{{asset('assets/images/tea.png')}}" >
                <span>Du lịch & Di chuyển</span>
            </a>
            <a href="#" class="item-menu-trademark">
                <img src="{{asset('assets/images/tea.png')}}" >
                <span>Hoa, Trái cây & Thực phẩm sạch</span>
            </a>
            <a href="#" class="item-menu-trademark">
                <img src="{{asset('assets/images/tea.png')}}" >
                <span>Siêu thị & Mua sắm</span>
            </a>
            <a href="#" class="item-menu-trademark">
                <img src="{{asset('assets/images/tea.png')}}" >
                <span>Thời trang & Phụ Kiện</span>
            </a>
            <a href="#" class="item-menu-trademark">
                <img src="{{asset('assets/images/tea.png')}}" >
                <span>Du lịch & Di chuyển</span>
            </a>
            <a href="#" class="item-menu-trademark">
                <img src="{{asset('assets/images/tea.png')}}" >
                <span>Hoa, Trái cây & Thực phẩm sạch</span>
            </a>
        </div>

        <div class="line-center-trademark"></div>

        <div class="content-trademark">
            @for($i = 0; $i < 10; $i++)
                <img src="{{ asset('assets/images/kfc.png') }}" class="img-trademark">
            @endfor
        </div>
    </section>

@stop
@section('script_page')

@stop
