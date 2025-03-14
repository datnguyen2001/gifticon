@extends('admin.layout.index')
@section('main')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Cập nhật bài viết</h1>
        </div><!-- End Page Title -->
        <section class="section dashboard">
            <div class="bg-white p-4">
                <form action="{{url("admin/footer/update",$new->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-3">
                        <div class="col-2">Tiêu đề :</div>
                        <div class="col-10">
                            <input type="text" class="form-control" name="name" value="{{$new->name}}" required>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header bg-info text-white">
                            Nội dung
                        </div>
                        <div class="card-body mt-2">
                            <textarea name="content" class="ckeditor" required>{!! $new->content !!}</textarea>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-2">Thuộc thể loại:</div>
                        <div class="col-10">
                            <select name="type" class="form-control">
                                <option value="1" @if($new->type == 1) selected @endif>Hỗ trợ khách hàng</option>
                                <option value="2" @if($new->type == 2) selected @endif>Về chúng tôi</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-3"></div>
                        <div class="col-8">
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <a href="{{route('admin.footer.index')}}" class="btn btn-danger">Hủy</a>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main><!-- End #main -->
@endsection
@section('script')
    <script src="//cdn.ckeditor.com/4.18.0/full/ckeditor.js"></script>
    <script type="text/javascript">
        CKEDITOR.replace('content', {
            filebrowserUploadUrl: "{{route('admin.ckeditor.image-upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form',
            height:'700px'
        });
    </script>
@endsection
