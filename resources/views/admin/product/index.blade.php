@extends('admin.layout.index')
@section('main')
    <main id="main" class="main">
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Danh sách sản phẩm</h5>
                                <a class="btn btn-success"
                                   href="{{route('admin.product.create')}}">Thêm sản phẩm</a>
                            </div>
                            <table class="table">
                                <thead class="thead-dark">
                                <tr>
                                    <th scope="col">STT</th>
                                    <th scope="col">Tên sản phẩm</th>
                                    <th scope="col">Hình ảnh</th>
                                    <th scope="col">Trạng thái</th>
                                    <th scope="col">Hành động</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($listData))
                                    @foreach($listData as $key => $value)
                                        <tr>
                                            <th scope="row">{{$key+1}}</th>
                                            <td>
                                                {{$value->name}}
                                            </td>
                                            <td>
                                                <div class="w-50 position-relative"
                                                     style="padding-top: 50%;min-width: 20px">
                                                    <img src="{{asset($value->src)}}" class="position-absolute w-100 h-100"
                                                         style="top: 0;left: 0;object-fit: cover">
                                                </div>
                                            </td>
                                            <td>
                                                @if($value->display == 1)Bật @else Tắt @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{url('admin/product/'. $value->id . '/edit')}}"
                                                       class="btn btn-icon btn-light btn-hover-success btn-sm"
                                                       data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                                       data-bs-original-title="Cập nhật">
                                                        <i class="bi bi-pencil-square "></i>
                                                    </a>
                                                    <form action="{{ route('admin.product.destroy', $value->id) }}" method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-delete btn-icon btn-light btn-sm" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="Xóa">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                @else
                                    <tr>
                                        <td colspan="4" class="text-center" style="color: red;font-size: 18px">Không có dữ liệu</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center">
                                {{ $listData->appends(request()->all())->links('admin.pagination_custom.index') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


    </main><!-- End #main -->
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('button.btn-delete').on('click', function (e) {
                e.preventDefault(); // Prevent the default form submission

                let form = $(this).closest('form'); // Get the closest form
                $.confirm({
                    title: 'Xác nhận!',
                    content: 'Bạn có chắc chắn muốn xóa bản ghi này?',
                    buttons: {
                        ok: {
                            text: 'Xóa',
                            btnClass: 'btn-danger',
                            action: function () {
                                form.submit(); // Submit the form on confirmation
                            }
                        },
                        close: {
                            text: 'Hủy',
                            action: function () {
                                // Do nothing on cancel
                            }
                        }
                    }
                });
            });
        });
    </script>
@endsection
