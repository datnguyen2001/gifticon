@extends('admin.layout.index')
@section('main')

@endsection
@section('script')
    <main id="main" class="main">
        <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Lượng truy cập website trong 30 ngày:  </h5>
                <div id="areaChart"></div>

                <script>
                    document.addEventListener("DOMContentLoaded", () => {
                        // Extract dates and screenPageViews from the $ga4Data array
                        const ga4Data = @json($ga4Data);
                        const dates = ga4Data.map(item => item.date);
                        const screenPageViews = ga4Data.map(item => parseInt(item.screenPageViews));

                        // Cấu hình biểu đồ
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
                                    formatter: function(value) {
                                        return value.toLocaleString(); // Định dạng số với dấu phân cách
                                    }
                                }
                            },
                            tooltip: {
                                y: {
                                    formatter: function(value) {
                                        return value.toLocaleString();
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
