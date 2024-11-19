@extends('web.index')
@section('title','Trang chủ')

@section('style_page')
    <style>
        .title-post-footer{
            font-size: 22px;
            font-weight: bold;
        }
        .content-post-footer{
            font-size: 16px;
            color: black;
        }
        .content-post-footer img{
            max-width: 100%;
            object-fit: cover;
        }
        @media (max-width: 767px) {
            .title-post-footer{
                font-size: 18px;
            }
            .content-post-footer{
                font-size: 14px;
            }
        }
    </style>
@stop
{{--content of page--}}
@section('content')

    <section class="box-post-footer">
        <p class="title-post-footer">{{$data->name}}</p>
        <div class="content-post-footer">{!! $data->content !!}</div>
    </section>

@stop
@section('script_page')

@stop
