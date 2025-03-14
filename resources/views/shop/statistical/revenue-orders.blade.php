@extends('shop.layout.index')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        right: 14px;
    }
</style>
@section('main')
    <main id="main" class="main">

        <!-- Bộ lọc thời gian -->
        <form action="{{route('shop.revenue-orders')}}" method="GET" class="filter row mb-3" enctype="multipart/form-data">
            <div class="col-12 mb-3">
                <h8 class="card-title" style="color: #f26522">Bộ lọc thời gian</h8>
            </div>
            <div class="col-3">
                <label class="mb-2">Ngày bắt đầu</label>
                <input type="date" class="form-control" name="date_start" value="{{ request('date_start') }}">
            </div>
            <div class="col-3">
                <label class="mb-2">Ngày kết thúc</label>
                <input type="date" class="form-control" name="date_end" value="{{ request('date_end') }}">
            </div>
            <div class="col-3 d-flex flex-column">
                <label style="margin-bottom: 7px">Hành động</label>
                <div>
                    <button type="submit" class="btn btn-success">Lọc</button>
                    <a href="{{url('shop/revenue-orders')}}" class="btn btn-secondary mx-2">Làm mới</a>
                </div>
            </div>
        </form>

        <div class="pagetitle">
            <h8 class="card-title" style="color: #f26522">Thống kê đơn hàng</h8>
        </div>

        <div class="row">
            <div class="col-xxl-3 col-md-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Tổng số đơn hàng</h5>
                        <div class="d-flex align-items-center">
                            <div
                                class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-cart" style="font-size: 32px;font-weight: bold"></i>
                            </div>
                            <div class="ps-3">
                                <h4 style="font-weight: bold" id="order_all">{{$order_all}}</h4>
                                <p style="color: red" id="order_all_money">{{number_format($order_all_money)}} VND</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-md-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Tổng số đơn hàng chờ xác nhận</h5>
                        <div class="d-flex align-items-center">
                            <div
                                class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-cart" style="font-size: 32px;font-weight: bold"></i>
                            </div>
                            <div class="ps-3">
                                <h4 style="font-weight: bold" id="waiting_payment">{{$waiting_payment}}</h4>
                                <p style="color: red" id="waiting_payment_money">{{number_format($waiting_payment_money)}} VND</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-md-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Tổng số đơn hàng đã xác nhận</h5>
                        <div class="d-flex align-items-center">
                            <div
                                class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-cart" style="font-size: 32px;font-weight: bold"></i>
                            </div>
                            <div class="ps-3">
                                <h4 style="font-weight: bold" id="order_paid">{{$order_paid}}</h4>
                                <p style="color: red" id="order_paid_money">{{number_format($order_paid_money)}} VND</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-md-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Tổng số đơn hàng đã hủy</h5>
                        <div class="d-flex align-items-center">
                            <div
                                class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-cart" style="font-size: 32px;font-weight: bold"></i>
                            </div>
                            <div class="ps-3">
                                <h4 style="font-weight: bold" id="order_canceled">{{$order_canceled}}</h4>
                                <p style="color: red" id="order_canceled_money">{{number_format($order_canceled_money)}} VND</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body" style="padding-top: 20px">
                    <h8 class="card-title" style="color: #f26522" >Tổng doanh thu: {{ number_format($totalRevenue) }} VND</h8>

                    <!-- Biểu đồ -->
                    <div id="areaChart"></div>

                    <script>
                        document.addEventListener("DOMContentLoaded", () => {
                            const series = {
                                "dailyDataSeries": {
                                    "prices": @json($revenuesData),
                                    "dates": @json($dates)
                                }
                            };

                            // Cấu hình biểu đồ
                            var options = {
                                series: [{
                                    name: 'Doanh thu',
                                    data: series.dailyDataSeries.prices
                                }],
                                chart: {
                                    type: 'area',
                                    height: 350
                                },
                                xaxis: {
                                    categories: series.dailyDataSeries.dates, // Các ngày trong tháng
                                },
                                yaxis: {
                                    title: {
                                        text: 'Doanh thu (VND)'
                                    },
                                    labels: {
                                        formatter: function(value) {
                                            return value.toLocaleString();
                                        }
                                    }
                                },
                                tooltip: {
                                    y: {
                                        formatter: function(value) {
                                            return value.toLocaleString() + " VND";
                                        }
                                    }
                                }
                            };

                            var chart = new ApexCharts(document.querySelector("#areaChart"), options);
                            chart.render();
                        });
                    </script>
                </div>
            </div>
        </div>

        <div class="pagetitle">
            <h8 class="card-title" style="color: #f26522">Doanh thu theo sản phẩm</h8>
        </div>

        <div class="col-lg-12">
            <div class="card info-card sales-card">
                <div class="card-body" style="padding-top: 20px">
                    <div class="col-6">
                        <span style="margin-bottom: 5px;display: inline-block">Chọn sản phẩm</span>
                        <form action="{{route('shop.revenue-orders')}}" method="GET" class="filter row mb-3" enctype="multipart/form-data">
                            <input type="date" class="form-control" name="date_start" value="{{ request('date_start') }}" hidden>
                            <input type="date" class="form-control" name="date_end" value="{{ request('date_end') }}" hidden>
                            <select class="js-example-basic-single form-control" name="product_id" onchange="this.form.submit()">
                            <option value="" >Tên sản phẩm</option>
                            @foreach($listProduct as $item)
                                <option value="{{ $item->id }}" {{ request('product_id') == $item->id ? 'selected' : '' }}>{{$item->name}}</option>
                                @endforeach
                        </select>
                        </form>
                        <div class="d-flex align-items-center mt-3">
                            <p style="font-size: 18px;font-weight: bold;margin-right: 10px">Tổng số tiền thu được của sản phẩm:</p>
                            <p style="font-size: 18px;font-weight: bold">{{number_format($totalRevenueProduct)}}đ</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="pagetitle">
            <h8 class="card-title" style="color: #f26522">Thời gian mua hàng phổ biển</h8>
        </div>

        <div class="col-lg-12">
            <div class="card info-card sales-card">
                <div class="card-body" style="padding-top: 20px">
                    <div class="row">
                        <div class="col-6">
                            <table class="table table-bordered text-center">
                                <tr>
                                    <th>Khung giờ</th>
                                    <th>Đơn hàng</th>
                                </tr>
                                @foreach($morningRanges as $time => $count)
                                    <tr>
                                        <td>{{ $time }}</td>
                                        <td class="{{ $count == $maxOrderCount ? 'text-danger' : '' }}">{{ $count }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="col-6">
                            <table class="table table-bordered text-center">
                                <tr>
                                    <th>Khung giờ</th>
                                    <th>Đơn hàng</th>
                                </tr>
                                @foreach($afternoonRanges as $time => $count)
                                    <tr>
                                        <td>{{ $time }}</td>
                                        <td class="{{ $count == $maxOrderCount ? 'text-danger' : '' }}">{{ $count }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
    </script>
@endsection
