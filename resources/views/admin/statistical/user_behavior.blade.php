@extends('admin.layout.index')
@section('main')
    <main id="main" class="main">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Lượng truy cập website: </h5>
                        <select class="form-control" id="behaviorSort" style="width: fit-content">
                            <option value="30_days_before" selected>30 Ngày Trước</option>
                            <option value="yesterday">Hôm qua</option>
                            <option value="7_days_before">7 Ngày Trước</option>
                            <option value="this_month">Tháng Này</option>
                            <option value="last_month">Tháng Trước</option>
                        </select>
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
                        <select class="form-control" id="orderPercentageSort" style="width: fit-content">
                            <option value="c_30_days_before" selected>30 Ngày Trước</option>
                            <option value="c_yesterday">Hôm qua</option>
                            <option value="c_7_days_before">7 Ngày Trước</option>
                            <option value="c_this_month">Tháng Này</option>
                            <option value="c_last_month">Tháng Trước</option>
                        </select>
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
            $('#behaviorSort').on('change', function () {
                const selectedRange = $(this).val();

                // Make an AJAX request to fetch the data for the selected range
                $.ajax({
                    url: `/admin/user_behavior/${selectedRange}`,
                    method: 'GET',
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

            // Handle the change in the select dropdown (for range selection)
            $('#orderPercentageSort').on('change', function () {
                const selectedRange = $(this).val();
                $.ajax({
                    url: `/admin/user_behavior_percentage/${selectedRange}`,
                    method: 'GET',
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
