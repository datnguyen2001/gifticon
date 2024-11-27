@extends('admin.layout.index')
@section('main')
    <main id="main" class="main">
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Mã đơn hàng: {{$listData->order_code}}</h5>
                            </div>
                            <h8 class="card-title" style="color: #f26522">1. Thông tin chi tiết đơn hàng</h8>
                            <div class="card-body">

                                <table class="table table-borderless ">
                                    <thead>
                                    <tr>
                                        <th scope="col">Stt</th>
                                        <th scope="col">SĐT người nhận</th>
                                        <th scope="col">Hình ảnh</th>
                                        <th scope="col">Tên sản phẩm</th>
                                        <th scope="col">Số lượng</th>
                                        <th scope="col">Đơn giá</th>
                                        <th scope="col">Tổng tiền</th>
                                    </tr>
                                    </thead>
                                    <tbody class="table_data">
                                    @foreach($listData['order_item'] as $k => $order_item)
                                        <tr>
                                            <td>{{$k+=1}}</td>
                                            <td>{{$order_item->receiver_phone}}</td>
                                            <td><img class="image-preview" style="width: 40px; height: auto"
                                                     src="{{$order_item->product_image}}"></td>
                                            <td>{{$order_item->product_name}}</td>
                                            <td>{{$order_item->quantity}}</td>
                                            <td>{{number_format($order_item->unit_price)}}đ</td>
                                            <td>{{number_format($order_item->quantity*$order_item->unit_price)}}đ</td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <h8 class="card-title" style="color: #f26522">2. Tổng giá trị đơn hàng</h8>
                            <br>
                            <br>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-3 col-form-label">Trạng thái đơn hàng</label>
                                <div class="col-sm-9">
                                    <input
                                        style="color: @if($listData->status_id == 1) #FF9900 @elseif($listData->status_id == 2) #0050ff @elseif($listData->status == 3) #FF3333 @endif; font-weight: 600"
                                        disabled type="text" name="status" required class="form-control"
                                        value="{{$listData->status_name}}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-3 col-form-label">Tổng tiền đơn hàng</label>
                                <div class="col-sm-9">
                                    <input disabled type="text" name="order_code_transport" required
                                           class="form-control"
                                           value="{{number_format($listData->total_price)}}đ">
                                </div>
                            </div>
                            <div class="row mb-3 mt-5">
                                <div class="col-sm-12 d-flex gap-3">
                                    @if($listData->status_id == 1)
                                        <a href="{{url('admin/order/status/'.$listData->id.'/2')}}">
                                            <button type="submit" class="btn btn-primary">Xác nhận đơn hàng</button>
                                        </a>
                                        <a href="{{url('admin/order/status/'.$listData->id.'/3')}}">
                                            <button type="submit" class="btn btn-danger">Huỷ đơn hàng</button>
                                        </a>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </section>
    </main>
@endsection
@section('script')
    <script src="assets/admin/order.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(".btn-print").click(function () {
            $.ajax({
                url: window.location.origin + '/admin/order/label-print-order',
                data: {'order_id': $(this).val()},
                type: 'post',
                dataType: 'json',
                success: function (data) {
                    if (data.status) {
                        var newWin = window.open("", "_blank");
                        newWin.document.write(data.html);
                        newWin.onload = function () {
                            setTimeout(function () {
                                newWin.print();
                            }, 2000);
                        };
                        newWin.onafterprint = function () {
                            newWin.close();
                        };
                        newWin.print();
                    }
                }
            });
        })
    </script>
@endsection

