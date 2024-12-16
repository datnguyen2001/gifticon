@extends('admin.layout.index')

@section('main')
    <style>
        .commission_shop{
            flex-wrap: nowrap !important;
            overflow: auto;
            .card{
                margin-bottom: 10px !important;
            }
        }
    </style>
    <main id="main" class="main">

        <div class="pagetitle">
            <h8 class="card-title" style="color: #f26522">Thống kê hoa hồng</h8>
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
                <a href="{{route('admin.commission')}}" class="btn btn-success">Làm mới</a>
            </div>
            <div class="col-6 mt-3">
                <input type="text" class="form-control" name="shop_name" placeholder="Tìm kiếm theo shop" />
            </div>
        </div>
        <div class="row commission_shop">
            @foreach($countCommissionByShop as $commissionByShop)
                <div class="col-xxl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title" id="shop_name">{{$commissionByShop->shop_name}}</h5>
                            <div class="d-flex align-items-center">
                                <div
                                    class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cart" style="font-size: 32px;font-weight: bold"></i>
                                </div>
                                <div class="ps-3">
                                    <p class="mb-0" style="color: red" id="shop_commission">{{number_format($commissionByShop->total_commission)}} VND</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="col-lg-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tổng hoa hồng theo tháng: </h5>
                    </div>
                    <div id="commisionChart"></div>

                </div>
            </div>
        </div>
    </main>
@endsection
@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            $('#filter-month-order').on('change', function() {
                const selectedMonth = $(this).val();
                const selectedYear = $('#filter-year-order').val();
                const shopName = $('input[name="shop_name"]').val();

                updateOrder(selectedMonth, '', selectedYear, shopName);
                $('#filter-quarter-order').val('');
            });

            $('#filter-quarter-order').on('change', function() {
                const selectedYear = $('#filter-year-order').val();
                const shopName = $('input[name="shop_name"]').val();

                updateOrder('', $(this).val(), selectedYear, shopName);
                $('#filter-month-order').val('');
            });

            $('#filter-year-order').on('change', function() {
                const selectedYear = $(this).val();
                const selectedMonth = $('#filter-month-order').val();
                const selectedQuarter = $('#filter-quarter-order').val();
                const shopName = $('input[name="shop_name"]').val();

                updateOrder(selectedMonth, selectedQuarter, selectedYear, shopName);
            });

            let typingTimer;
            $('input[name="shop_name"]').on('input', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    const shopName = $(this).val();
                    const selectedMonth = $('#filter-month-order').val();
                    const selectedQuarter = $('#filter-quarter-order').val();
                    const selectedYear = $('#filter-year-order').val();

                    updateOrder(selectedMonth, selectedQuarter, selectedYear, shopName);
                }, 500);
            });

            function updateOrder(month, quarter, year, shopName) {
                if (!month && !quarter && !year && !shopName) {
                    alert("Vui lòng chọn ít nhất một bộ lọc!");
                    return;
                }

                $.ajax({
                    url: '/admin/commission-range',
                    method: 'GET',
                    data: {
                        month: month,
                        quarter: quarter,
                        year: year,
                        shop_name: shopName
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
                let html = '';
                data.forEach(item => {
                    html += `
                <div class="col-xxl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">${item.shop_name}</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cart" style="font-size: 32px;font-weight: bold"></i>
                                </div>
                                <div class="ps-3">
                                    <p class="mb-0" style="color: red">${(item.total_commission ?? 0).toLocaleString('vi-VN')} VND</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
                });
                $('.commission_shop').html(html);
            }

            const commissionData = Object.values(@json($totalCommissionByMonth));

            const months = commissionData.map(item => `Tháng ${item.month}`);
            const totalCommissionByMonth = commissionData.map(item => parseInt(item.total_commission));
            // Chart configuration
            var options = {
                series: [{
                    name: 'Tổng hoa hồng',
                    data: totalCommissionByMonth
                }],
                chart: {
                    type: 'area',
                    height: 350
                },
                xaxis: {
                    categories: months,
                },
                yaxis: {
                    title: {
                        text: 'Tổng hoa hồng'
                    },
                    labels: {
                        formatter: function (value) {
                            return value.toLocaleString();
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function (value) {
                            return value.toLocaleString();
                        }
                    }
                }
            };

            // Create the chart
            var chart = new ApexCharts($("#commisionChart")[0], options);
            chart.render();
        });
    </script>
@endsection
