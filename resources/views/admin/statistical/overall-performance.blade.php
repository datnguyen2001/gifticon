@extends('admin.layout.index')

@section('main')
    <main id="main" class="main">

        <div class="pagetitle">
            <h8 class="card-title" style="color: #f26522">Thống kê đơn hàng</h8>
        </div>
        <!-- Bộ lọc thời gian -->
        <div class="filter row mb-3">
            <div class="col-3">
                <select id="filter-month-order" class="form-control">
                    <option value="">Theo Tháng</option>
                    @foreach ($months as $month)
                        <option value="{{$month->month}}">Tháng {{$month->month}}</option>
                    @endforeach()
                </select>
            </div>
            <div class="col-3">
                <select id="filter-quarter-order" class="form-control">
                    <option value="">Theo Quý</option>
                    @foreach($quarters as $quarter)
                        <option value="{{$quarter->quarter}}">Quý {{$quarter->quarter}}</option>
                    @endforeach()
                </select>
            </div>
            <div class="col-3">
                <select id="filter-year-order" class="form-control">
                    <option value="">Theo Năm</option>
                    @foreach($years as $year)
                        <option value="{{$year->year}}">Năm {{$year->year}}</option>
                    @endforeach()
                </select>
            </div>
            <div class="col-3">
                <a href="{{route('admin.overall_performance')}}" class="btn btn-success">Làm mới</a>
            </div>

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

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                // Lắng nghe sự kiện thay đổi bộ lọc tháng
                $('#filter-month-order').on('change', function() {
                    const selectedMonth = $(this).val();
                    const selectedYear = $('#filter-year-order').val();

                    updateOrder(selectedMonth, '', selectedYear);
                    $('#filter-quarter-order').val('');
                });

                // Lắng nghe sự kiện thay đổi bộ lọc quý
                $('#filter-quarter-order').on('change', function() {
                    const selectedYear = $('#filter-year-order').val();
                    updateOrder('', $(this).val(), selectedYear);
                    $('#filter-month-order').val('');
                });

                // Lắng nghe sự kiện thay đổi bộ lọc năm
                $('#filter-year-order').on('change', function() {
                    const selectedYear = $(this).val();
                    const selectedMonth = $('#filter-month-order').val();
                    const selectedQuarter = $('#filter-quarter-order').val();
                    updateOrder(selectedMonth, selectedQuarter, selectedYear);
                });

                function updateOrder(month, quarter, year) {
                    if (!month && !quarter && !year) {
                        alert("Vui lòng chọn ít nhất một bộ lọc!");
                        return;
                    }

                    $.ajax({
                        url: '/admin/statistics',
                        method: 'GET',
                        data: {
                            month: month,
                            quarter: quarter,
                            year: year
                        },
                        success: function(response) {
                            renderStatistics(response.data);
                        },
                        error: function(error) {
                            console.log("Có lỗi xảy ra: ", error);
                        }
                    });
                }

                function renderStatistics(data) {
                    document.getElementById('order_all').innerText = data.order_all;
                    document.getElementById('order_all_money').innerText = new Intl.NumberFormat().format(data.order_all_money) + ' VND';

                    document.getElementById('waiting_payment').innerText = data.waiting_payment;
                    document.getElementById('waiting_payment_money').innerText = new Intl.NumberFormat().format(data.waiting_payment_money) + ' VND';

                    document.getElementById('order_paid').innerText = data.order_paid;
                    document.getElementById('order_paid_money').innerText = new Intl.NumberFormat().format(data.order_paid_money) + ' VND';

                    document.getElementById('order_canceled').innerText = data.order_canceled;
                    document.getElementById('order_canceled_money').innerText = new Intl.NumberFormat().format(data.order_canceled_money) + ' VND';
                }
            });

        </script>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Doanh thu toàn sàn:  <span id="total-revenue" style="color: red;font-size: 18px;font-weight: 500;margin-left: 10px">{{number_format($currentRevenue)}} VND</span></h5>
                    <!-- Bộ lọc thời gian -->
                    <div class="filter row mb-3">
                        <div class="col-3">
                            <select id="filter-month" class="form-control">
                                <option value="">Theo Tháng</option>
                                @foreach ($months as $month)
                                    <option value="{{$month->month}}">Tháng {{$month->month}}</option>
                                @endforeach()
                            </select>
                        </div>
                        <div class="col-3">
                            <select id="filter-quarter" class="form-control">
                                <option value="">Theo Quý</option>
                                @foreach($quarters as $quarter)
                                    <option value="{{$quarter->quarter}}">Quý {{$quarter->quarter}}</option>
                                @endforeach()
                            </select>
                        </div>
                        <div class="col-3">
                            <select id="filter-year" class="form-control">
                                <option value="">Theo Năm</option>
                                @foreach($years as $year)
                                    <option value="{{$year->year}}">Năm {{$year->year}}</option>
                                @endforeach()
                            </select>
                        </div>
                        <div class="col-3">
                            <a href="{{route('admin.overall_performance')}}" class="btn btn-success">Làm mới</a>
                        </div>

                    </div>
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
                                        text: 'Doanh thu toàn sàn (VND)'
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

                            // Hàm gửi yêu cầu AJAX để lấy dữ liệu doanh thu mới khi thay đổi bộ lọc
                            function updateChart(month = '', quarter = '', year = '') {
                                $.ajax({
                                    url: '/admin/get-revenue-data', // Đường dẫn API lấy dữ liệu doanh thu
                                    method: 'GET',
                                    data: { month: month, quarter: quarter, year: year },
                                    success: function(response) {
                                        // Cập nhật dữ liệu cho biểu đồ
                                        var newData = response.data;
                                        chart.updateOptions({
                                            series: [{
                                                name: 'Doanh thu',
                                                data: newData.revenues
                                            }],
                                            xaxis: {
                                                categories: newData.dates
                                            }
                                        });
                                        document.getElementById("total-revenue").innerText = newData.totalRevenue.toLocaleString() + " VND";
                                    }
                                });
                            }

                            // Lắng nghe sự kiện thay đổi bộ lọc
                            $('#filter-month').on('change', function() {
                                const selectedMonth = $(this).val();  // Lấy giá trị tháng đã chọn
                                const selectedYear = $('#filter-year').val(); // Lấy giá trị năm đã chọn

                                // Nếu năm đã được chọn, gọi updateChart với cả tháng và năm
                                updateChart(selectedMonth, '', selectedYear); // Gửi tháng và năm
                                $('#filter-quarter').val('');
                            });

                            $('#filter-quarter').on('change', function() {
                                const selectedYear = $('#filter-year').val();
                                updateChart('', $(this).val(),selectedYear); // Cập nhật theo quý
                                $('#filter-month').val('');
                            });

                            $('#filter-year').on('change', function() {
                                const selectedYear = $(this).val();
                                const selectedMonth = $('#filter-month').val();  // Lấy tháng đã chọn
                                const selectedQuarter = $('#filter-quarter').val();
                                updateChart(selectedMonth, selectedQuarter, selectedYear); // Gửi tháng và năm
                            });
                        });
                    </script>

                </div>
            </div>
        </div>

        <div class="pagetitle">
            <h8 class="card-title" style="color: #f26522">Tăng trưởng doanh thu </h8>
        </div>
        <!-- Bộ lọc thời gian -->
        <div class="filter row mb-3">
            <div class="col-3">
                <select id="filter-month-current" class="form-control">
                    <option value="">Theo Tháng</option>
                    @foreach ($months as $month)
                        <option value="{{$month->month}}">Tháng {{$month->month}}</option>
                    @endforeach()
                </select>
            </div>
            <div class="col-3">
                <select id="filter-quarter-current" class="form-control">
                    <option value="">Theo Quý</option>
                    @foreach($quarters as $quarter)
                        <option value="{{$quarter->quarter}}">Quý {{$quarter->quarter}}</option>
                    @endforeach()
                </select>
            </div>
            <div class="col-3">
                <select id="filter-year-current" class="form-control">
                    <option value="">Theo Năm</option>
                    @foreach($years as $year)
                        <option value="{{$year->year}}">Năm {{$year->year}}</option>
                    @endforeach()
                </select>
            </div>
            <div class="col-3">
                <a href="{{route('admin.overall_performance')}}" class="btn btn-success">Làm mới</a>
            </div>

        </div>
        <div class="row">
            <div class="col-xxl-4 col-md-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Doanh thu hiện tại</h5>
                        <div class="d-flex align-items-center">
                            <div
                                class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-cart" style="font-size: 28px;font-weight: bold"></i>
                            </div>
                            <div class="ps-3">
                                <h4 style="font-weight: bold;color: red;margin-bottom: 0" id="currentRevenues">{{number_format($currentRevenues)}} VND</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-md-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Doanh thu của kỳ trước</h5>
                        <div class="d-flex align-items-center">
                            <div
                                class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-cart" style="font-size: 28px;font-weight: bold"></i>
                            </div>
                            <div class="ps-3">
                                <h4 style="font-weight: bold;color: red;margin-bottom: 0" id="previousRevenue">{{number_format($previousRevenue)}} VND</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-md-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Tỷ lệ tăng trưởng</h5>
                        <div class="d-flex align-items-center">
                            <div
                                class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-cart" style="font-size: 28px;font-weight: bold"></i>
                            </div>
                            <div class="ps-3">
                                <h4 style="font-weight: bold;color: red;margin-bottom: 0" id="growthRate">{{ number_format($growthRate, 2) }}%</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                // Lắng nghe sự kiện thay đổi bộ lọc tháng
                $('#filter-month-current').on('change', function() {
                    const selectedMonth = $(this).val();
                    const selectedYear = $('#filter-year-current').val();

                    updateCurrent(selectedMonth, '', selectedYear);
                    $('#filter-quarter-current').val('');
                });

                // Lắng nghe sự kiện thay đổi bộ lọc quý
                $('#filter-quarter-current').on('change', function() {
                    const selectedYear = $('#filter-year-current').val();
                    updateCurrent('', $(this).val(), selectedYear);
                    $('#filter-month-current').val('');
                });

                // Lắng nghe sự kiện thay đổi bộ lọc năm
                $('#filter-year-current').on('change', function() {
                    const selectedYear = $(this).val();
                    const selectedMonth = $('#filter-month-current').val();
                    const selectedQuarter = $('#filter-quarter-current').val();
                    updateCurrent(selectedMonth, selectedQuarter, selectedYear);
                });

                function updateCurrent(month, quarter, year) {
                    if (!month && !quarter && !year) {
                        alert("Vui lòng chọn ít nhất một bộ lọc!");
                        return;
                    }

                    $.ajax({
                        url: '/admin/revenue-growth',
                        method: 'GET',
                        data: {
                            month: month,
                            quarter: quarter,
                            year: year
                        },
                        success: function(response) {
                            $('#currentRevenues').text(response.data.currentRevenue);
                            $('#previousRevenue').text(response.data.previousRevenue);
                            $('#growthRate').text(response.data.growthPercentage);
                        },
                        error: function(error) {
                            console.log("Có lỗi xảy ra: ", error);
                        }
                    });
                }

            });

        </script>

    </main>
@endsection
@section('script')

@endsection
