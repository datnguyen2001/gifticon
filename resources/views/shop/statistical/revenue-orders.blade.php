@extends('shop.layout.index')

@section('main')
    <main id="main" class="main">

<!-- Bộ lọc thời gian -->
    <div class="filter row mb-3">
        <div class="col-3">
            <select id="filter-month" class="form-control">
                <option value="">Theo Tháng</option>
                @foreach ($months as $month)
                    <option value="{{ $month->month }}" {{ request('month') == $month->month ? 'selected' : '' }}>
                        Tháng {{ $month->month }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <select id="filter-quarter" class="form-control">
                <option value="">Theo Quý</option>
                @foreach($quarters as $quarter)
                    <option value="{{ $quarter->quarter }}" {{ request('quarter') == $quarter->quarter ? 'selected' : '' }}>
                        Quý {{ $quarter->quarter }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <select id="filter-year" class="form-control">
                <option value="">Theo Năm</option>
                @foreach($years as $year)
                    <option value="{{ $year->year }}" {{ request('year') == $year->year ? 'selected' : '' }}>
                        Năm {{ $year->year }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <a href="{{ route('shop.revenue-orders') }}" class="btn btn-success">Làm mới</a>
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
                <div class="card-body" style="padding-top: 20px">
                    <h8 class="card-title" style="color: #f26522" >Tổng doanh thu: {{ number_format($totalRevenue) }} VND</h8>

                    <!-- Biểu đồ -->
                    <div id="areaChart"></div>

                    <script>
                        document.addEventListener("DOMContentLoaded", () => {
                            // Dữ liệu doanh thu ban đầu
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


    </main>
@endsection
@section('script')

@endsection
