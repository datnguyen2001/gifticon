@extends('admin.layout.index')
<style>
    .area-custom-date, .order-custom-date{
        display: flex;
        align-items: center;
    }
</style>
@section('main')
    <main id="main" class="main">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Lượng truy cập website: </h5>
                        <div class="d-flex align-items-center" style="gap: 10px">
                            <div class="area-custom-date">
                                <label class="mx-2">Từ: </label>
                                <input type="date" id="start_date_area" name="start_date" class="form-control"/>
                                <label class="me-2 mx-4">Đến: </label>
                                <input type="date" id="end_date_area" name="end_date" class="form-control"/>
                            </div>
                            <select class="form-control" id="behaviorSort" style="width: fit-content">
                                <option value="30_days_before" selected>30 Ngày Trước</option>
                                <option value="yesterday">Hôm qua</option>
                                <option value="7_days_before">7 Ngày Trước</option>
                                <option value="this_month">Tháng Này</option>
                                <option value="last_month">Tháng Trước</option>
                                <option value="custom">Tuỳ Chỉnh</option>
                            </select>
                        </div>
                    </div>
                    <div id="areaChart"></div>

                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Tỷ lệ đơn hàng/ Lượng truy cập (%): </h5>
                        <div class="d-flex align-items-center" style="gap: 10px">
                            <div class="order-custom-date">
                                <label class="mx-2">Từ: </label>
                                <input type="date" id="start_date_order" name="start_date" class="form-control"/>
                                <label class="me-2 mx-4">Đến: </label>
                                <input type="date" id="end_date_order" name="end_date" class="form-control"/>
                            </div>
                            <select class="form-control" id="orderPercentageSort" style="width: fit-content">
                                <option value="c_30_days_before" selected>30 Ngày Trước</option>
                                <option value="c_yesterday">Hôm qua</option>
                                <option value="c_7_days_before">7 Ngày Trước</option>
                                <option value="c_this_month">Tháng Này</option>
                                <option value="c_last_month">Tháng Trước</option>
                                <option value="c_custom">Tuỳ Chỉnh</option>
                            </select>
                        </div>
                    </div>
                    <div id="rateOrderChart"></div>

                </div>
            </div>
        </div>
    </main>

@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('.area-custom-date').hide();
            $('#behaviorSort').change(function() {
                if ($(this).val() === 'custom') {
                    $('.area-custom-date').show();
                } else {
                    $('.area-custom-date').hide();
                }
            });

            $('.order-custom-date').hide();
            $('#orderPercentageSort').change(function() {
                if ($(this).val() === 'c_custom') {
                    $('.order-custom-date').show();
                } else {
                    $('.order-custom-date').hide();
                }
            });

            const ga4Data = Object.values(@json($dataPageView));

            ga4Data.sort((a, b) => {
                const dateA = new Date(a.date.split('/').reverse().join('-'));
                const dateB = new Date(b.date.split('/').reverse().join('-'));
                return dateA - dateB;
            });

            ga4Data.forEach(item => {
                const date = new Date(item.date.split('/').reverse().join('-'));
                const formattedDate = date.toLocaleDateString('en-GB');
                item.date = formattedDate;
            });

            const dates = ga4Data.map(item => item.date);
            const screenPageViews = ga4Data.map(item => parseInt(item.page_view));
            // Chart configuration
            var options = {
                series: [{
                    name: 'Lượng truy cập',
                    data: screenPageViews
                }],
                chart: {
                    type: 'area',
                    height: 350
                },
                xaxis: {
                    categories: dates,
                },
                yaxis: {
                    title: {
                        text: 'Lượng truy cập'
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
            var chart = new ApexCharts($("#areaChart")[0], options);
            chart.render();

            // Handle the change in the select dropdown (for range selection)
            $('#behaviorSort, #start_date_area, #end_date_area').on('change', function () {
                const selectedRange = $('#behaviorSort').val();
                let startDate = $('#start_date_area').val();
                let endDate = $('#end_date_area').val();

                if (selectedRange !== 'custom') {
                    startDate = null;
                    endDate = null;
                }

                if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
                    alert('Vui lòng chọn ngày kết thúc sau ngày bắt đầu');
                    return;
                }

                if (selectedRange === 'custom' && (!startDate || !endDate)) {
                    return;
                }
                // Make an AJAX request to fetch the data for the selected range
                $.ajax({
                    url: `/admin/user_behavior/${selectedRange}`,
                    method: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function (data) {
                        const newGa4Data = Object.values(data);
                        newGa4Data.sort((a, b) => {
                            const dateA = new Date(a.date.split('/').reverse().join('-'));
                            const dateB = new Date(b.date.split('/').reverse().join('-'));
                            return dateA - dateB;
                        });
                        newGa4Data.forEach(item => {
                            const date = new Date(item.date.split('/').reverse().join('-'));
                            const formattedDate = date.toLocaleDateString('en-GB');
                            item.date = formattedDate;
                        });
                        // Update chart with the new data
                        const newDates = newGa4Data.map(item => item.date);
                        const newScreenPageViews = newGa4Data.map(item => parseInt(item.page_view));

                        chart.updateOptions({
                            series: [{
                                name: 'Lượng truy cập',
                                data: newScreenPageViews
                            }],
                            xaxis: {
                                categories: newDates
                            }
                        });
                    },
                    error: function (error) {
                        console.error('Error:', error);
                    }
                });
            });


            const orderResultCalculate = Object.values(@json($orderResultCalculate));

            orderResultCalculate.sort((a, b) => {
                const dateA = new Date(a.date.split('/').reverse().join('-'));
                const dateB = new Date(b.date.split('/').reverse().join('-'));
                return dateA - dateB;
            });

            orderResultCalculate.forEach(item => {
                const date = new Date(item.date.split('/').reverse().join('-'));
                const formattedDate = date.toLocaleDateString('en-GB');
                item.date = formattedDate;
            });

            const orderResultCalculateDate = orderResultCalculate.map(item => item.date);
            const orderResultCalculatePercent = orderResultCalculate.map(item => parseInt(item.percentage));

            var percentOptions = {
                series: [{
                    name: 'Tỷ lệ đơn hàng/ Lượng truy cập',
                    data: orderResultCalculatePercent
                }],
                chart: {
                    type: 'area',
                    height: 350
                },
                xaxis: {
                    categories: orderResultCalculateDate,
                },
                yaxis: [{
                    title: {
                        text: 'Tỷ lệ đơn hàng/ Lượng truy cập'
                    },
                    labels: {
                        formatter: function (value) {
                            return value.toLocaleString() + ' %';
                        }
                    }
                }, {
                    opposite: true,
                    title: {
                        text: 'Số lượng'
                    },
                    labels: {
                        formatter: function (value) {
                            return value.toLocaleString();
                        }
                    }
                }],
                tooltip: {
                    y: {
                        formatter: function (value) {
                            return value.toLocaleString()  + ' %';
                        }
                    }
                },
            };

            // Create the chart
            var chartCalculate = new ApexCharts($("#rateOrderChart")[0], percentOptions);
            chartCalculate.render();

            $('#orderPercentageSort, #start_date_order, #end_date_order').on('change', function () {
                const selectedRange = $('#orderPercentageSort').val();
                let startDate = $('#start_date_order').val();
                let endDate = $('#end_date_order').val();

                if (selectedRange !== 'c_custom') {
                    startDate = null;
                    endDate = null;
                }

                if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
                    alert('Vui lòng chọn ngày kết thúc sau ngày bắt đầu');
                    return;
                }

                if (selectedRange === 'c_custom' && (!startDate || !endDate)) {
                    return;
                }

                $.ajax({
                    url: `/admin/user_behavior_percentage/${selectedRange}`,
                    method: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function (data) {
                        const newOrderData = Object.values(data);
                        newOrderData.sort((a, b) => {
                            const dateA = new Date(a.date.split('/').reverse().join('-'));
                            const dateB = new Date(b.date.split('/').reverse().join('-'));
                            return dateA - dateB;
                        });

                        newOrderData.forEach(item => {
                            const date = new Date(item.date.split('/').reverse().join('-'));
                            const formattedDate = date.toLocaleDateString('en-GB');
                            item.date = formattedDate;
                        });
                        // Update chart with the new data
                        const newDates = newOrderData.map(item => item.date);
                        const newScreenPageViews = newOrderData.map(item => parseInt(item.percentage));

                        chartCalculate.updateOptions({
                            series: [{
                                name: 'Tỷ lệ đơn hàng/ Lượng truy cập',
                                data: newScreenPageViews
                            }],
                            xaxis: {
                                categories: newDates
                            }
                        });
                    },
                    error: function (error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
@endsection
