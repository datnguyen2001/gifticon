<section class="box-trademark">
    <p class="title-trademark">Danh sách yêu thích</p>
    <div class="box-menu-trademark">
        @foreach($categories as $item)
            <div class="item-menu-trademark" data-category-id="{{ $item->id }}">
                <img src="{{asset($item->src)}}">
                <span>{{$item->name}}</span>
            </div>
        @endforeach
    </div>
    <div class="search-wrapper">
        <div class="filter-search">
            <span class="price-range-title">Khoảng giá</span>
            <div class="price-range">
                <input type="text" id="start-price" placeholder="Từ" class="input-price"/>
                <span>-</span>
                <input type="text" id="end-price" placeholder="Đến" class="input-price"/>
            </div>
            <div class="search-container">
                <input type="text" id="product-name" class="search-input" placeholder="Tìm kiếm theo tên sản phẩm"/>
                <button class="search-button" id="search-button">
                    <img src="{{asset('assets/images/search-icon.png')}}" alt="Search icon" class="search-icon">
                </button>
            </div>
            <button class="apply-button" id="apply-button">Áp dụng</button>
        </div>

    </div>
    <div class="line-center-trademark"></div>

    @if(count($productFavorites)>0)
        <div class="content-you-like mt-4" id="product-list">
            @foreach($productFavorites as $val)
                <div class="item-product" data-category-id="{{ $val->category_id }}" data-product-id="{{ $val->id }}"
                     data-price="{{ $val->price }}" data-name="{{ strtolower($val->name) }}" data-link="{{route('product.detail', [$val->slug])}}">
                    <div class="box-img-product">
                        <img src="{{asset($val->src)}}" class="img-product">
                        <i class="fa-solid fa-heart fa-heart-sp fa-heart-sp-active"  data-product-id="{{ $val->id }}"></i>
                    </div>
                    <a href="{{route('product.detail', [$val->slug])}}" class="name-product">{{$val->name}}</a>
                    <span class="price-product">{{ number_format($val->price, 0, ',', '.') }}đ</span>
                </div>
            @endforeach
        </div>
        <div id="pagination" class="d-flex justify-content-center pagination mt-4"></div>
    @endif
</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const products = Array.from(document.querySelectorAll('.item-product'));
        const paginationContainer = document.getElementById('pagination');
        let currentPage = 1; // Biến để lưu trang hiện tại
        const itemsPerPage = 20; // Số lượng sản phẩm mỗi trang
        let currentCategoryId = null;

        // Lọc sản phẩm
        function filterProducts() {
            const categoryId = currentCategoryId;
            const startPrice = parseFloat(document.getElementById('start-price').value) || 0;
            const endPrice = parseFloat(document.getElementById('end-price').value) || Infinity;
            const searchName = document.getElementById('product-name').value.trim().toLowerCase();

            const filteredProducts = products.filter(product => {
                const productCategory = product.getAttribute('data-category-id');
                const productPrice = parseFloat(product.getAttribute('data-price'));
                const productName = product.getAttribute('data-name').toLowerCase(); // So sánh tên sản phẩm với chữ thường

                return (
                    (!categoryId || productCategory === categoryId) &&
                    productPrice >= startPrice &&
                    productPrice <= endPrice &&
                    productName.includes(searchName)
                );
            });

            renderPagination(filteredProducts, currentPage);
            displayProducts(filteredProducts, currentPage);
        }

        // Hàm hiển thị sản phẩm theo trang
        function displayProducts(filteredProducts, page) {
            const startIndex = (page - 1) * itemsPerPage;
            const endIndex = page * itemsPerPage;
            const productsToDisplay = filteredProducts.slice(startIndex, endIndex);

            // Xóa các sản phẩm cũ
            const productList = document.getElementById('product-list');
            productList.innerHTML = '';

            // Thêm sản phẩm mới vào danh sách
            productsToDisplay.forEach(product => {
                const productItem = document.createElement('div');
                productItem.classList.add('item-product');
                productItem.setAttribute('data-category-id', product.getAttribute('data-category-id'));
                productItem.setAttribute('data-price', product.getAttribute('data-price'));
                productItem.setAttribute('data-name', product.getAttribute('data-name'));

                productItem.innerHTML = `
                <div class="box-img-product">
                    <img src="${product.querySelector('img').src}" class="img-product">
                    <i class="fa-solid fa-heart fa-heart-sp fa-heart-sp-active" data-product-id="${product.getAttribute('data-product-id')}"></i>
                </div>
                <a href="${product.getAttribute('data-link')}" class="name-product">${product.querySelector('.name-product').textContent}</a>
                <span class="price-product">${product.querySelector('.price-product').textContent}</span>
            `;
                productList.appendChild(productItem);
            });

            // Cập nhật phân trang
            renderPagination(filteredProducts, page);
        }

        // Hàm tạo phân trang
        function renderPagination(filteredProducts, currentPage) {
            paginationContainer.innerHTML = ''; // Reset nội dung phân trang

            const totalItems = filteredProducts.length;
            const totalPages = Math.ceil(totalItems / itemsPerPage);

            if (totalPages <= 1) return;

            // Nút quay lại
            const prevLink = document.createElement('a');
            prevLink.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="34" height="31" viewBox="0 0 48 38">
                <g transform="translate(-877.5 -600)">
                    <path d="M-19057.816-16469.5l-5,5,5,5" transform="translate(19961.816 17083)" stroke="#F1641E" stroke-width="1" fill="none"></path>
                </g>
            </svg>`;
            if (currentPage === 1) {
                prevLink.classList.add('disabled'); // Chỉ thêm class 'disabled' khi cần
            }
            prevLink.href = '#';
            prevLink.addEventListener('click', (e) => {
                e.preventDefault();
                if (currentPage > 1) {
                    displayProducts(filteredProducts, currentPage - 1);
                }
            });
            paginationContainer.appendChild(prevLink);

            // Các trang
            const pageList = document.createElement('ol');
            for (let i = 1; i <= totalPages; i++) {
                const pageItem = document.createElement('li');
                const isActive = i === currentPage;
                pageItem.innerHTML = `<a href="#" class="${isActive ? 'this' : ''}">${i}</a>`;
                pageItem.addEventListener('click', (e) => {
                    e.preventDefault();
                    displayProducts(filteredProducts, i);
                });
                pageList.appendChild(pageItem);
            }
            paginationContainer.appendChild(pageList);

            // Nút tiếp theo
            const nextLink = document.createElement('a');
            nextLink.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="34" height="31" viewBox="0 0 48 38">
                <g transform="translate(-540 -859)">
                    <path d="M-19062.814-16469.5l5,5-5,5" transform="translate(19624.314 17342)" stroke="#F1641E" stroke-width="1" fill="none"></path>
                </g>
            </svg>`;
            if (currentPage === totalPages) {
                nextLink.classList.add('disabled'); // Chỉ thêm class 'disabled' khi cần
            }
            nextLink.href = '#';
            nextLink.addEventListener('click', (e) => {
                e.preventDefault();
                if (currentPage < totalPages) {
                    displayProducts(filteredProducts, currentPage + 1);
                }
            });
            paginationContainer.appendChild(nextLink);
        }

        // Sự kiện lọc khi nhấn nút "Áp dụng"
        document.getElementById('apply-button').addEventListener('click', filterProducts);

        // Lọc theo danh mục
        const trademarkItems = document.querySelectorAll('.item-menu-trademark');
        trademarkItems.forEach(item => {
            item.addEventListener('click', function () {
                const clickedCategoryId = this.getAttribute('data-category-id');

                // Nếu danh mục đang chọn đã được click lại, hủy bỏ lọc và hiển thị tất cả sản phẩm
                if (currentCategoryId === clickedCategoryId) {
                    currentCategoryId = null;
                    document.querySelectorAll('.menu-trademark-active').forEach(el => el.classList.remove('menu-trademark-active'));
                } else {
                    currentCategoryId = clickedCategoryId;
                    trademarkItems.forEach(el => el.classList.remove('menu-trademark-active'));
                    this.classList.add('menu-trademark-active');
                }
                filterProducts();
            });
        });

        // Khởi tạo hiển thị ban đầu
        filterProducts();
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.fa-heart').forEach(item => {
            item.addEventListener('click', function (event) {
                let productId = this.getAttribute('data-product-id');
                let heartIcon = this;
                let productItem = this.closest('.item-product');

                fetch('/toggle-favorite/' + productId, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        product_id: productId,
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'added') {
                            heartIcon.style.color = '#F1641E';
                            toastr.success('Sản phẩm đã được thêm vào yêu thích!');
                        } else if (data.status === 'removed') {
                            heartIcon.style.color = '#c3c3c3cc';
                            if (productItem) {
                                productItem.remove();
                            }
                            toastr.success('Sản phẩm đã được xóa khỏi yêu thích.');
                        } else {
                            window.location.href = '/dang-nhap';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });
    });
</script>
