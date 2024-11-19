@extends('admin.layout.index')
@section('title', 'Cài đặt')

@section('style')

@endsection

@section('main')
    <main id="main" class="main d-flex flex-column justify-content-center">
        <div class="">
            <h1 class="h3 mb-4 text-gray-800">{{$titlePage}}</h1>
            <hr>
            <form action="{{ route('admin.setting.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mt-3">
                    <div class="col-2">Mô tả :</div>
                    <div class="col-10">
                        <textarea name="describe" class="form-control" cols="30" rows="6">{{@$data->describe}}</textarea>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-2">Số điện thoại:</div>
                    <div class="col-10">
                        <input class="form-control" name="phone" value="{{@$data->phone}}" type="text" >
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-2">Địa chỉ :</div>
                    <div class="col-10">
                        <input class="form-control" name="address" value="{{@$data->address}}" type="text" >
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-2">Email :</div>
                    <div class="col-10">
                        <input class="form-control" name="email" value="{{@$data->email}}" type="text" >
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-2">Facebook :</div>
                    <div class="col-10">
                        <input class="form-control" name="facebook" value="{{@$data->facebook}}" type="text" >
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-2">Twitter :</div>
                    <div class="col-10">
                        <input class="form-control" name="twitter" value="{{@$data->twitter}}" type="text" >
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-2">Zalo :</div>
                    <div class="col-10">
                        <input class="form-control" name="zalo" value="{{@$data->zalo}}" type="text" >
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Lưu</button>
            </form>
        </div>

    </main>
@endsection
@section('script')

@endsection
