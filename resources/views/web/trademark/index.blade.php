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
            @foreach($categories as $category)
                <a href="{{route('trademark',$category->slug)}}" class="item-menu-trademark @if($category->slug == $slug) menu-trademark-active @endif">
                    <img src="{{asset($category->src)}}" >
                    <span>{{$category->name ?? ''}}</span>
                </a>
            @endforeach
        </div>

        <div class="line-center-trademark"></div>

        <div class="content-trademark">
            @foreach($brands as $brand)
                <a href="{{route('brand.detail', [$brand->slug])}}" class="img-trademark-wrapper">
                    <img src="{{ asset($brand->src) }}" alt="{{$brand->name}}" class="img-trademark">
                </a>
            @endforeach
        </div>
    </section>

@stop
@section('script_page')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var trademarkItems = document.querySelectorAll('.item-menu-trademark');

        trademarkItems.forEach(function(item) {
            item.addEventListener('click', function(event) {
                event.preventDefault();

                // Toggle the 'menu-trademark-active' class on the clicked item
                this.classList.toggle('menu-trademark-active');
            });
        });
    });

</script>
@stop
