@extends('layout.admin.index')
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
                            <h8 class="card-title" style="color: #f26522">1. Thông tin người mua hàng</h8>
                            <br>
                            <br>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-3 col-form-label">Xưng hô</label>
                                <div class="col-sm-9">
                                    <input type="text" name="vocative" id="vocative" disabled class="form-control"
                                           value="{{$listData->vocative}}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-3 col-form-label">Họ và tên</label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" id="name" disabled class="form-control"
                                           value="{{$listData->name}}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputNumber" class="col-sm-3 col-form-label">Số điện thoại</label>
                                <div class="col-sm-9">
                                    <input type="text" name="phone" id="phone" disabled class="form-control"
                                           value="{{$listData->phone}}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                    <input type="text" name="email" id="email" disabled class="form-control"
                                           value="{{$listData->email}}">
                                </div>
                            </div>
                            @if($listData->delivery_address == 'Giao tận nơi')
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-3 col-form-label">Tỉnh / Thành phố</label>
                                    <div class="col-sm-9">
                                        <select name="province_id" class="form-control" disabled>
                                            <option value="">Chọn Tỉnh / Thành phố</option>
                                            @foreach($province as $_province)
                                                <option value="{{$_province->ProvinceID}}"
                                                        @if($_province->ProvinceID == $listData->province_id) selected @endif>{{$_province->ProvinceName}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-3 col-form-label">Quận / Huyện</label>
                                    <div class="col-sm-9">
                                        <select name="district_id" class="form-control" disabled>
                                            <option value="">Chọn Quận / Huyện</option>
                                            @foreach($district as $_district)
                                                <option value="{{$_district->DistrictID}}"
                                                        @if($_district->DistrictID == $listData->district_id) selected @endif>{{$_district->DistrictName}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-3 col-form-label">Phường / Xã</label>
                                    <div class="col-sm-9">
                                        <select name="ward_id" class="form-control" disabled>
                                            <option value="">Chọn Phường / Xã</option>
                                            @foreach($ward as $_ward)
                                                <option value="{{$_ward->WardCode}}"
                                                        @if($_ward->WardCode == $listData->ward_id) selected @endif>{{$_ward->WardName}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-3 col-form-label">Địa chỉ</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="address" id="address" disabled class="form-control"
                                               value="{{$listData->address_detail}}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-3 col-form-label">Địa chỉ cụ thể</label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled name="full_address" class="form-control"
                                               value="{{$listData->full_address}}">
                                    </div>
                                </div>
                            @else
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-3 col-form-label">Khách lấy tại cửa hàng - Địa
                                        chỉ cửa hàng</label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled name="full_address" required class="form-control"
                                               value="{{$listData->full_address}}">
                                    </div>
                                </div>
                            @endif
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-3 col-form-label">Yêu cầu khác</label>
                                <div class="col-sm-9">
                                    <input disabled type="text" name="full_address" required class="form-control"
                                           value="{{$listData->note ? $listData->note : "Không có" }}">
                                </div>
                            </div>

                            <h8 class="card-title" style="color: #f26522">2. Thông tin xuất hóa đơn công ty (Nếu có)
                            </h8>
                            <br>
                            <br>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-3 col-form-label">Tên Công Ty</label>
                                <div class="col-sm-9">
                                    <input disabled type="text" name="full_address" required class="form-control"
                                           value="{{$listData->name_cty ? $listData->name_cty : "Không có"}}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputNumber" class="col-sm-3 col-form-label">Địa Chỉ Công Ty</label>
                                <div class="col-sm-9">
                                    <input disabled type="text" name="phone" required class="form-control"
                                           value="{{$listData->address_cty ? $listData->address_cty : "Không có"}}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-3 col-form-label">Mã Số Thuế</label>
                                <div class="col-sm-9">
                                    <input disabled type="text" name="name" required class="form-control"
                                           value="{{$listData->tax_code ? $listData->tax_code : "Không có"}}">
                                </div>
                            </div>
                            <h8 class="card-title" style="color: #f26522">3. Thông tin chi tiết đơn hàng</h8>
                            <div class="card-body">

                                <table class="table table-borderless ">
                                    <thead>
                                    <tr>
                                        <th scope="col">Stt</th>
                                        <th scope="col">Hình ảnh</th>
                                        <th scope="col">Tên sản phẩm</th>
                                        <th scope="col">Loại hàng</th>
                                        <th scope="col">Số lượng</th>
                                        <th scope="col">Đơn giá</th>
                                        <th scope="col">Tổng tiền</th>
                                    </tr>
                                    </thead>
                                    <tbody class="table_data">
                                    @foreach($listData['order_item'] as $k => $order_item)
                                        <tr>
                                            <td>{{$k+=1}}</td>
                                            <td><img class="image-preview" style="width: 40px; height: auto"
                                                     src="{{$order_item->product_image->image}}"></td>
                                            <td>{{$order_item->product_name->name}}</td>
                                            <td>{{$order_item->product_attribute->name}}</td>
                                            <td>{{$order_item->quantity}}</td>
                                            <td>{{number_format($order_item->promotional_price)}}đ</td>
                                            <td>{{number_format($order_item->total_money)}}đ</td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <h8 class="card-title" style="color: #f26522">4. Tổng giá trị đơn hàng</h8>
                            <br>
                            <br>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-3 col-form-label">Trạng thái đơn hàng</label>
                                <div class="col-sm-9">
                                    <input
                                        style="color: @if($listData->status == 0) #FF9900 @elseif($listData->status == 1) #0099FF @elseif($listData->status == 2) #0066FF @elseif($listData->status == 3) #00FF00 @elseif($listData->status == 4) #FF3333 @endif; font-weight: 600"
                                        disabled type="text" name="status" required class="form-control"
                                        value="{{$listData->status_name}}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-3 col-form-label">Phương thức thanh toán:</label>
                                <div class="col-sm-9">
                                    <input disabled type="text" name="type_payment" required class="form-control"
                                           value="@if($listData->type_payment == 1) Nhận hàng thanh toán @else Thanh toán qua VNPAY @endif">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-3 col-form-label">Đơn vị vận chuyển</label>
                                <div class="col-sm-9">
                                    <input disabled type="text" name="transport_name" required class="form-control"
                                           value="@if($listData->transport_name == 'GHN') GIAO HÀNG NHANH @else Cửa hàng @endif">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-3 col-form-label">Mã đơn hàng vận chuyển</label>
                                <div class="col-sm-9">
                                    <input disabled type="text" name="order_code_transport" required
                                           class="form-control"
                                           value="@if($listData->order_code_transport ) {{$listData->order_code_transport}} @else Không có @endif">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-3 col-form-label">Phí vận chuyển</label>
                                <div class="col-sm-9">
                                    <input disabled type="text" name="fee_shipping" required class="form-control"
                                           value="{{number_format($listData->fee_shipping)}}đ">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-3 col-form-label">Tổng tiền sản phẩm</label>
                                <div class="col-sm-9">
                                    <input disabled type="text" name="order_code_transport" required
                                           class="form-control"
                                           value="{{number_format($listData->total_money_product)}}đ">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-3 col-form-label">Tổng tiền đơn hàng</label>
                                <div class="col-sm-9">
                                    <input disabled type="text" name="order_code_transport" required
                                           class="form-control"
                                           value="{{number_format($listData->total_money_product + $listData->fee_shipping)}}đ">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    @if($listData->status == 0)
                                        <a href="{{url('admin/order/status/'.$listData->id.'/1')}}">
                                            <button type="submit" class="btn btn-primary">Xác nhận đơn hàng</button>
                                        </a>
                                        <a href="{{url('admin/order/status/'.$listData->id.'/4')}}">
                                            <button type="submit" class="btn btn-danger">Huỷ đơn hàng</button>
                                        </a>
                                    @elseif($listData->status == 1)
                                        <a href="{{url('admin/order/status/'.$listData->id.'/2')}}">
                                            <button type="submit" class="btn btn-primary">Giao hàng</button>
                                        </a>
                                        <a href="{{url('admin/order/status/'.$listData->id.'/4')}}">
                                            <button type="submit" class="btn btn-danger">Huỷ đơn hàng</button>
                                        </a>
                                    @elseif($listData->status == 2)
                                        <a href="{{url('admin/order/status/'.$listData->id.'/3')}}">
                                            <button type="submit" class="btn btn-primary">Hoàn thành đơn hàng</button>
                                        </a>
                                        <a href="{{url('admin/order/status/'.$listData->id.'/4')}}">
                                            <button type="submit" class="btn btn-danger">Hủy đơn hàng
                                            </button>
                                        </a>
                                    @endif
                                        @if($listData->status != 4  && $listData->status != 0 && $listData->status != 5 && $listData->status != 3 && $listData->type_payment == 2)
                                            <a href="{{url('admin/order/status/'.$listData->id.'/5')}}">
                                                <button type="submit" class="btn btn-danger">Trả hàng hoàn tiền
                                                </button>
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

