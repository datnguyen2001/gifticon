@extends('shop.layout.index')
@section('main')
    <main id="main" class="main">
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body d-flex align-items-center flex-wrap"
                             style="padding-top: 20px">
                            <a href="{{url('admin/order/index/all')}}" type="button"
                               class="btn btn-outline-secondary mb @if($status == 'all') active @endif"> Tất cả đơn hàng
                                <span style="font-weight: 700">{{$order_all}}</span></a>
                            <a href="{{url('admin/order/index/0')}}"
                               class="btn btn-outline-warning mx-3 @if($status == 0) active @endif">Chờ thanh toán <span
                                    style="font-weight: 700">{{$order_pending}}</span></a>
                            <a href="{{url('admin/order/index/1')}}" type="button"
                               class="btn btn-outline-success @if($status == 1) active @endif">Đã thanh toán <span
                                    style="font-weight: 700">{{$order_paid}}</span></a>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body d-flex justify-content-end" style="padding: 20px">
                            <form class="d-flex align-items-center w-50" method="get"
                                  action="{{url('admin/order/index/'.$status)}}">
                                <input name="search" type="text" value="{{request()->get('search')}}"
                                       placeholder="Tìm kiếm..." class="form-control" style="margin-right: 16px">
                                <button class="btn btn-info" style="margin-left: 15px"><i class="bi bi-search"></i>
                                </button>
                                <a href="{{url('admin/order/index/all')}}" class="btn btn-danger"
                                   style="margin-left: 15px">Hủy </a>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">{{$titlePage}}</h5>
                            </div>
                            @if(count($listData) > 0)
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Mã đơn</th>
                                        <th scope="col">Bên mua</th>
                                        <th scope="col" style="width: 12%;">Tổng tiền</th>
                                        <th scope="col">Trạng thái đơn hàng</th>
                                        <th scope="col">Thời gian mua</th>
                                        @if($status == 0 || $status == 'all')
                                            <th scope="col" style="width: 15%;">Xác nhận nhanh</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($listData as $k => $value)
                                        <tr>
                                            <th id="{{$value->id}}" scope="row">{{$k+1}}</th>
                                            <td>
                                                {{$value->order_code}}<br>
                                                <a class="btn btn-icon btn-light btn-hover-success btn-sm mt-1"
                                                   data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                                   data-bs-original-title="Chi tiết đơn hàng">
                                                    Chi tiết đơn hàng
                                                </a>
                                            </td>
                                            <td>
                                                @if($value->user)
                                                    {{$value->user->full_name}}<br>
                                                    {{$value->user->phone}}
                                                @else
                                                    Không tìm thấy thông tin người mua.
                                                @endif
                                            </td>
                                            <td>
                                                {{number_format($value->total_price)}} đ
                                            </td>
                                            <td style="color: @if($value->status == 0) #FF9900 @elseif($value->status == 1) #0099FF @elseif($value->status == 2) #0066FF @elseif($value->status == 3) #00FF00 @elseif($value->status == 4) #FF3333 @endif; font-weight: 600">
                                                {{$value->status_name}}
                                            </td>
                                            <td>{{$value->created_at}}</td>
                                            <td style="border-top: 1px solid #cccccc">
                                                @if($value->status == 0)
                                                    <a href="{{url('admin/order/status/'.$value->id.'/1')}}"
                                                       class="btn-zalo-send ">
                                                        <button type="submit" class="btn btn-primary mb-2">Xác nhận
                                                            đơn
                                                        </button>
                                                    </a>
                                                    <a href="{{url('admin/order/status/'.$value->id.'/2')}}">
                                                        <button type="submit" class="btn btn-danger">Huỷ đơn hàng
                                                        </button>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center">
                                    {{ $listData->appends(request()->all())->links('admin.pagination_custom.index') }}
                                </div>
                            @else
                                <h5 class="card-title">Không có dữ liệu</h5>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
@section('script')
    <script>
        $('a.btn-delete').confirm({
            title: 'Xác nhận!',
            content: 'Bạn có chắc chắn muốn xóa bản ghi này?',
            buttons: {
                ok: {
                    text: 'Xóa',
                    btnClass: 'btn-danger',
                    action: function () {
                        location.href = this.$target.attr('href');
                    }
                },
                close: {
                    text: 'Hủy',
                    action: function () {
                    }
                }
            }
        });
    </script>
@endsection
