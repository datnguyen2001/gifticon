@extends('web.index')
@section('title','Trang chủ')

@section('style_page')
<link rel="stylesheet" href="{{asset('assets/css/home.css')}}">
@stop
{{--content of page--}}
@section('content')
    <a href="{{url('auth/google/redirect')}}">Google</a>
    <a href="{{url('auth/facebook/redirect')}}">Facebook</a>
    <a href="{{url('auth/zalo/redirect')}}">Zalo</a>

@stop
@section('script_page')
<script src="{{asset('assets/js/home.js')}}"></script>
@stop
