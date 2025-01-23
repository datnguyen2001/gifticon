@extends('admin.layout.index')
@section('main')
    <style>
        .delete-icon{
            cursor: pointer;
            width: 24px;
            height: 24px;
            object-fit: cover;
        }
        .add-address{
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }
        .add-icon{
            width: 24px;
            height: 24px;
            object-fit: cover;
        }
        .text-error{
            font-size: 12px;
        }
    </style>
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Chỉnh sửa sản phẩm</h1>
        </div><!-- End Page Title -->
        <section class="section dashboard">
            <div class="bg-white p-4">
                <form action="{{ route('admin.product.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-3">Tên sản phẩm:</div>
                        <div class="col-8">
                            <input class="form-control" name="name" value="{{ old('name', $data->name) }}" type="text" required>
                            @error('name')
                            <span class="text-danger text-error mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">Chọn danh mục:</div>
                        <div class="col-8">
                            <select name="category_id" class="form-control" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $data->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <span class="text-danger text-error mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">Chọn shop:</div>
                        <div class="col-8">
                            <select name="shop_id" class="form-control" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($shops as $shop)
                                    <option value="{{ $shop->id }}" {{ $data->shop_id == $shop->id ? 'selected' : '' }}>
                                        {{ $shop->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('shop_id')
                            <span class="text-danger text-error mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">Hình ảnh:</div>
                        <div class="col-5">
                            <div class="form-control position-relative div-parent" style="padding-top: 50%">
                                <div class="position-absolute w-100 h-100 div-file" style="top: 0; left: 0;z-index: 10">
                                    <button type="button" class="position-absolute clear border-0 bg-danger p-0 d-flex justify-content-center align-items-center" style="top: -10px;right: -10px;width: 30px;height: 30px;border-radius: 50%">
                                        <i class="bi bi-x-lg text-white"></i>
                                    </button>
                                    <img src="{{ asset($data->src) }}" class="w-100 h-100" style="object-fit: cover">
                                </div>
                            </div>
                            @error('file')
                            <span class="text-danger text-error mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <div class="row mt-3">
                        <div class="col-3">Giá :</div>
                        <div class="col-8">
                            <input class="form-control" name="price" value="{{ old('price', $data->price) }}" type="text" required>
                            @error('price')
                            <span class="text-danger text-error mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-3">Số lượng phát hành :</div>
                        <div class="col-8">
                            <input class="form-control" name="quantity" value="{{ old('quantity', $data->quantity) }}" type="number" required>
                            @error('quantity')
                            <span class="text-danger text-error mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-3">Cơ sở áp dụng :</div>
                        <div class="col-8">
                            <div class="d-flex flex-column" style="gap: 10px">
                                @foreach($locations as $key => $location)
                                    <div class="d-flex align-items-center" style="gap: 10px">
                                        <input class="form-control" name="location[]" value="{{ $location->location }}" type="text" required>
                                        <img src="{{ asset('assets/images/trash-icon.png') }}" alt="trash icon" class="delete-icon"/>
                                    </div>
                                @endforeach
                                <div class="add-address">
                                    <img src="{{ asset('assets/images/add-icon.png') }}" alt="add icon" class="add-icon"/>
                                    <p class="mb-0">Thêm địa chỉ</p>
                                </div>
                            </div>
                            @error('location')
                            <span class="text-danger text-error mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-3">Thời gian sử dụng :</div>
                        <div class="col-8 d-flex flex-wrap" style="row-gap: 10px; column-gap: 30px">
                            <div class="d-flex align-items-center" style="gap: 10px">
                                <label class="text-nowrap">Bắt đầu</label>
                                <input type="date" class="form-control" name="start_date" value="{{ old('start_date', \Carbon\Carbon::parse($data->start_date)->format('Y-m-d')) }}">
                            </div>
                            <div class="d-flex align-items-center" style="gap: 10px">
                                <label class="text-nowrap">Kết thúc</label>
                                <input type="date" class="form-control" name="end_date" value="{{ old('end_date', \Carbon\Carbon::parse($data->end_date)->format('Y-m-d')) }}">
                            </div>
                            @error('start_date')
                                <span class="text-danger text-error mt-1 me-2">{{ $message }}</span>
                            @enderror
                            @error('end_date')
                                <span class="text-danger text-error mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header bg-info text-white">
                            Chi tiết sản phẩm
                        </div>
                        <div class="card-body mt-2">
                            <textarea name="describe" class="ckeditor" required>{{ old('describe', $data->describe) }}</textarea>
                            @error('describe')
                            <span class="text-danger text-error mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header bg-info text-white">
                            Hướng dẫn sử dụng
                        </div>
                        <div class="card-body mt-2">
                            <textarea name="guide" class="ckeditor" required>{{ old('guide', $data->guide) }}</textarea>
                            @error('guide')
                            <span class="text-danger text-error mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <div class="row mt-3">
                        <div class="col-3">Bật/tắt :</div>
                        <div class="col-8">
                            <div class="form-check form-switch">
                                <input class="form-check-input" name="display" @if($data->display == 1) checked
                                       @endif type="checkbox"
                                       id="flexSwitchCheckChecked">
                                <label class="form-check-label" for="flexSwitchCheckChecked">Hiện </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-3"></div>
                        <div class="col-8">
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <a href="{{route('admin.product.index')}}" class="btn btn-danger">Hủy</a>
                        </div>
                        <input type="file" name="file" accept="image/x-png,image/gif,image/jpeg" hidden>
                    </div>
                </form>
            </div>
        </section>
    </main><!-- End #main -->
@endsection
@section('script')
    <script src="//cdn.ckeditor.com/4.18.0/full/ckeditor.js"></script>
    <script type="text/javascript">
        CKEDITOR.replace('describe', {
            filebrowserUploadUrl: "{{route('admin.ckeditor.image-upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form',
            height:'600px'
        });
        CKEDITOR.replace('guide', {
            filebrowserUploadUrl: "{{route('admin.ckeditor.image-upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form',
            height:'600px'
        });
    </script>
    <script>
        let parent;
        $(document).on("click", ".select-image", function () {
            $('input[name="file"]').click();
            parent = $(this).parent();
        });
        $('input[type="file"]').change(function (e) {
            imgPreview(this);
        });

        function imgPreview(input) {
            let file = input.files[0];
            let mixedfile = file['type'].split("/");
            let filetype = mixedfile[0]; // (image, video)
            if (filetype == "image") {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $("#preview-img").show().attr("src",);
                    let html = '<div class="position-absolute w-100 h-100 div-file" style="top: 0; left: 0;z-index: 10">' +
                        '<button type="button" class="position-absolute clear border-0 bg-danger p-0 d-flex justify-content-center align-items-center" style="top: -10px;right: -10px;width: 30px;height: 30px;border-radius: 50%"><i class="bi bi-x-lg text-white"></i></button>' +
                        '<img src="' + e.target.result + '" class="w-100 h-100" style="object-fit: cover">' +
                        '</div>';
                    parent.html(html);
                }
                reader.readAsDataURL(input.files[0]);
            } else if (filetype == "video" || filetype == "mp4") {
                let html = '<div class="position-absolute w-100 h-100 div-file" style="top: 0; left: 0;z-index: 10">' +
                    '<button type="button" class="position-absolute clear border-0 bg-danger p-0 d-flex justify-content-center align-items-center" style="top: -10px;right: -10px;width: 30px;height: 30px;border-radius: 50%;z-index: 14"><i class="bi bi-x-lg text-white"></i></button>' +
                    '<video class="w-100 h-100" style="object-fit: cover" controls>\n' +
                    '<source src="' + URL.createObjectURL(input.files[0]) + '"></video>' +
                    '</div>';
                parent.html(html);
            } else {
                alert("Invalid file type");
            }
        }

        $(document).on("click", "button.clear", function () {
            parent = $(this).closest(".div-parent");
            $(".div-file").remove();
            let html = '<button type="button" class="position-absolute border-0 bg-transparent select-image" style="top: 50%;left: 50%;transform: translate(-50%,-50%)">\n' +
                '                                    <i style="font-size: 30px" class="bi bi-download"></i>\n' +
                '                                </button>';
            parent.html(html);
            $('input[type="file"]').val("");
        });

        $(document).ready(function () {
            // Delete input row when clicking the trash icon
            $(document).on("click", ".delete-icon", function () {
                $(this).closest("div").remove();
            });

            // Add a new input row when clicking the add icon
            $(".add-address").click(function () {
                let newInputRow = `
                <div class="d-flex align-items-center" style="gap: 10px">
                    <input class="form-control" name="location[]" type="text" required>
                    <img src="{{asset('assets/images/trash-icon.png')}}" alt="trash icon" class="delete-icon"/>
                </div>`;
                $(this).before(newInputRow);
            });

            // Validate start_date <= end_date
            $('input[name="start_date"], input[name="end_date"]').on('change', function () {
                const startDate = new Date($('input[name="start_date"]').val());
                const endDate = new Date($('input[name="end_date"]').val());

                if (startDate > endDate) {
                    alert('Ngày bắt đầu phải nhỏ hơn hoặc bằng ngày kết thúc!');
                    $(this).val('');
                }
            });
        });
    </script>
@endsection
