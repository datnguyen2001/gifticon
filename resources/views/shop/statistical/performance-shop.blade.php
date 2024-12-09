@extends('shop.layout.index')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single,.select2-container--default .select2-selection--single .select2-selection__arrow{
        height: 38px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        line-height: 38px;
    }
</style>
@section('main')
    <main id="main" class="main">

        <!-- Bộ lọc shop -->
        <div class="filter row mb-3">
            <div class="col-12 mb-3">
                <h8 class="card-title" style="color: #f26522">Thống kê theo shop</h8>
            </div>
            <div class="col-3">
                <select id="filter-shop-order" class="js-example-disabled-results form-control">
                    <option value="all">Tất cả các shop</option>
                    @foreach ($shop as $shops)
                        <option value="{{$shops->id}}" @if($shops->id == $id) selected @endif>{{$shops->name}}</option>
                    @endforeach()
                </select>
            </div>

        </div>

        <!-- Bộ lọc thời gian -->
        <form action="{{ route('admin.performance_shop',$id) }}" method="GET" class="filter row mb-3" enctype="multipart/form-data">
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
                    <a href="{{url('admin/performance_shop/all')}}" class="btn btn-secondary mx-2">Làm mới</a>
                </div>
            </div>
        </form>

        <div class="row">
            <div class="col-xxl-3 col-md-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Gian hàng có doanh thu cao nhất</h5>
                        <div class="d-flex align-items-center justify-content-center">
                            <h4 style="font-weight: bold" id="order_all">{{@$highestRevenueShop->shop_name}}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-md-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Gian hàng có doanh thu thấp nhất</h5>
                        <div class="d-flex align-items-center justify-content-center">
                            <h4 style="font-weight: bold" id="order_all">{{@$lowestRevenueShop->shop_name}}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                <div class="card-body">
                    <h5 class="card-title">Doanh thu:  <span id="total-revenue" style="color: red;font-size: 18px;font-weight: 500;margin-left: 10px">{{number_format($currentRevenue)}} VND</span></h5>
                    <div id="areaChart"></div>

                    <script>
                        document.addEventListener("DOMContentLoaded", () => {
                            // Dữ liệu doanh thu từng ngày trong tháng hiện tại
                            const series = {
                                "dailyDataSeries": {
                                    "prices": @json($revenues['revenues']), // Doanh thu theo từng ngày
                                    "dates": @json($revenues['days'])      // Ngày trong tháng
                                }
                            };

                            // Cấu hình biểu đồ
                            var options = {
                                series: [{
                                    name: 'Doanh thu',
                                    data: series.dailyDataSeries.prices
                                }],
                                chart: {
                                    type: 'area', // Biểu đồ diện tích
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
                                            return value.toLocaleString() ; // Định dạng số với dấu phân cách
                                        }
                                    }
                                },
                                tooltip: {
                                    y: {
                                        formatter: function(value) {
                                            return value.toLocaleString() + " VND"; // Hiển thị doanh thu với đơn vị VND
                                        }
                                    }
                                }
                            };

                            // Tạo biểu đồ
                            var chart = new ApexCharts(document.querySelector("#areaChart"), options);
                            chart.render();
                        });
                    </script>

                </div>
            </div>
        </div>

    </main>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        var $disabledResults = $(".js-example-disabled-results");
        $disabledResults.select2();

        document.addEventListener("DOMContentLoaded", () => {
            $('#filter-shop-order').on('change', function() {
                var shopId = $(this).val();

                if(shopId) {
                    window.location.href = "{{ url('admin/performance_shop') }}/" + shopId;
                }
            });
        });

    </script>
@endsection
