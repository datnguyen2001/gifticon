@extends('shop.layout.index')
<style>
    .col-content-sp{
        width: 49%;
    }
    @media (max-width: 768px) {
        .col-content-sp{
            width: 100%;
        }
    }
</style>
@section('main')
    <main id="main" class="main">

        <!-- Bộ lọc thời gian -->
        <form action="{{route('shop.product-statistics')}}" method="GET" class="filter row mb-3" enctype="multipart/form-data">
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
                    <a href="{{url('shop/product-statistics')}}" class="btn btn-secondary mx-2">Làm mới</a>
                </div>
            </div>
        </form>

        <div class="d-flex flex-wrap justify-content-between mt-5 flex-wrap">

        <div class="col-content-sp">
            <div class="pagetitle">
                <h8 class="card-title" style="color: #f26522">Top 10 sản phẩm bán chạy nhất</h8>
            </div>

            <div class="col-12">
                <div class="card info-card sales-card">
                    <div class="card-body" style="padding-top: 20px">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Tên sản phẩm</th>
                                <th>Số lượng bán</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($topSellingProducts as $index => $product)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ number_format($product->total_quantity) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
            <div class="col-content-sp">
                <div class="pagetitle">
                    <h8 class="card-title" style="color: #f26522">Danh sách sản phẩm không bán được </h8>
                </div>
                <div class="col-12">
                    <div class="card info-card sales-card">
                        <div class="card-body" style="padding-top: 20px">
                            <!-- Tìm kiếm sản phẩm -->
                            <div class="mb-3">
                                <input type="text" id="searchInputProductNot" class="form-control" placeholder="Tìm sản phẩm...">
                            </div>

                            <!-- Bảng sản phẩm sắp hết hàng  -->
                            <table class="table table-striped table-bordered" id="productsTableStockNot">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Số lượng tồn</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($productsNotSold as $index => $product)
                                    <tr class="product-row" id="row-{{ $product->id }}" style="{{ $index < 10 ? '' : 'display: none;' }}">
                                        <td class="product-index">{{ $index + 1 }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->quantity }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="d-flex flex-wrap justify-content-between mt-5 flex-wrap">
            <div class="col-content-sp">
                <div class="pagetitle">
                    <h8 class="card-title" style="color: #f26522">Danh sách sản phẩm tồn kho </h8>
                </div>
                <div class="col-12">
                    <div class="card info-card sales-card">
                        <div class="card-body" style="padding-top: 20px">
                            <!-- Tìm kiếm sản phẩm -->
                            <div class="mb-3">
                                <input type="text" id="searchInput" class="form-control" placeholder="Tìm sản phẩm...">
                            </div>

                            <!-- Bảng sản phẩm còn tồn kho -->
                            <table class="table table-striped table-bordered" id="productsTable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Số lượng tồn</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($productsInStock as $index => $product)
                                    <tr class="product-row" id="row-{{ $product->id }}" style="{{ $index < 10 ? '' : 'display: none;' }}">
                                        <td class="product-index">{{ $index + 1 }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->quantity }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-content-sp">
                <div class="pagetitle">
                    <h8 class="card-title" style="color: #f26522">Danh sách sản phẩm sắp hết hàng </h8>
                </div>
                <div class="col-12">
                    <div class="card info-card sales-card">
                        <div class="card-body" style="padding-top: 20px">
                            <!-- Tìm kiếm sản phẩm -->
                            <div class="mb-3">
                                <input type="text" id="searchInputProduct" class="form-control" placeholder="Tìm sản phẩm...">
                            </div>

                            <!-- Bảng sản phẩm sắp hết hàng  -->
                            <table class="table table-striped table-bordered" id="productsTableStock">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Số lượng tồn</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($lowStockProducts as $index => $product)
                                    <tr class="product-row" id="row-{{ $product->id }}" style="{{ $index < 10 ? '' : 'display: none;' }}">
                                        <td class="product-index">{{ $index + 1 }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->quantity }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection
@section('script')
    <script>
        document.querySelector('#searchInput').addEventListener('input', function() {
            var searchQuery = this.value.toLowerCase(); // Lấy giá trị tìm kiếm và chuyển thành chữ thường
            var rows = document.querySelectorAll('#productsTable .product-row'); // Lấy tất cả các hàng trong bảng
            var matchedCount = 0;

            // Duyệt qua tất cả các hàng trong bảng và ẩn/hiện tùy theo kết quả tìm kiếm
            rows.forEach(function(row, index) {
                var productName = row.querySelector('td:nth-child(2)').textContent.toLowerCase(); // Lấy tên sản phẩm
                var stockQuantity = row.querySelector('td:nth-child(3)').textContent.toLowerCase(); // Lấy số lượng tồn kho

                // Kiểm tra nếu tên sản phẩm hoặc số lượng tồn kho khớp với từ khóa tìm kiếm
                if (productName.includes(searchQuery) || stockQuantity.includes(searchQuery)) {
                    if (matchedCount < 10) {
                        row.style.display = ''; // Hiện hàng
                        row.querySelector('.product-index').textContent = matchedCount + 1; // Cập nhật lại số thứ tự
                        matchedCount++;
                    } else {
                        row.style.display = 'none'; // Ẩn các kết quả vượt quá 10
                    }
                } else {
                    row.style.display = 'none'; // Ẩn nếu không khớp
                }
            });
        });

        document.querySelector('#searchInputProduct').addEventListener('input', function() {
            var searchQuery = this.value.toLowerCase(); // Lấy giá trị tìm kiếm và chuyển thành chữ thường
            var rows = document.querySelectorAll('#productsTableStock .product-row'); // Lấy tất cả các hàng trong bảng
            var matchedCount = 0;

            // Duyệt qua tất cả các hàng trong bảng và ẩn/hiện tùy theo kết quả tìm kiếm
            rows.forEach(function(row, index) {
                var productName = row.querySelector('td:nth-child(2)').textContent.toLowerCase(); // Lấy tên sản phẩm
                var stockQuantity = row.querySelector('td:nth-child(3)').textContent.toLowerCase(); // Lấy số lượng tồn kho

                // Kiểm tra nếu tên sản phẩm hoặc số lượng tồn kho khớp với từ khóa tìm kiếm
                if (productName.includes(searchQuery) || stockQuantity.includes(searchQuery)) {
                    if (matchedCount < 10) {
                        row.style.display = ''; // Hiện hàng
                        row.querySelector('.product-index').textContent = matchedCount + 1; // Cập nhật lại số thứ tự
                        matchedCount++;
                    } else {
                        row.style.display = 'none'; // Ẩn các kết quả vượt quá 10
                    }
                } else {
                    row.style.display = 'none'; // Ẩn nếu không khớp
                }
            });
        });

        document.querySelector('#searchInputProductNot').addEventListener('input', function() {
            var searchQuery = this.value.toLowerCase(); // Lấy giá trị tìm kiếm và chuyển thành chữ thường
            var rows = document.querySelectorAll('#productsTableStockNot .product-row'); // Lấy tất cả các hàng trong bảng
            var matchedCount = 0;

            // Duyệt qua tất cả các hàng trong bảng và ẩn/hiện tùy theo kết quả tìm kiếm
            rows.forEach(function(row, index) {
                var productName = row.querySelector('td:nth-child(2)').textContent.toLowerCase(); // Lấy tên sản phẩm
                var stockQuantity = row.querySelector('td:nth-child(3)').textContent.toLowerCase(); // Lấy số lượng tồn kho

                // Kiểm tra nếu tên sản phẩm hoặc số lượng tồn kho khớp với từ khóa tìm kiếm
                if (productName.includes(searchQuery) || stockQuantity.includes(searchQuery)) {
                    if (matchedCount < 10) {
                        row.style.display = ''; // Hiện hàng
                        row.querySelector('.product-index').textContent = matchedCount + 1; // Cập nhật lại số thứ tự
                        matchedCount++;
                    } else {
                        row.style.display = 'none'; // Ẩn các kết quả vượt quá 10
                    }
                } else {
                    row.style.display = 'none'; // Ẩn nếu không khớp
                }
            });
        });
    </script>
@endsection
