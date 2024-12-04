<!-- Modal -->
<div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="noteModalLabel">Chi tiết Giỏ hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="" class="cart-detail-img" />
                <h5 class="cart-detail-name"></h5>
                <p class="price-sp-cart" id="price-sp-cart"></p>
                <div class="content-sp-cart">
                    <span class="fw-bold">Đơn giá:</span>
                    <span class="cart-detail-price"></span>
                </div>
                <div class="content-sp-cart">
                    <span class="fw-bold">Số lượng: </span>
                    <span class="cart-detail-quantity"></span>
                </div>
                <div class="content-sp-cart">
                    <span class="fw-bold">HSD: </span>
                    Từ <span class="cart-detail-start-date"></span> đến <span class="cart-detail-end-date"></span>
                </div>
                <div class="list-receiver">
                    <span class="buy-for-type fw-bold"></span>
                    <table class="table-bordered w-100 text-center mt-2">
                        <thead>
                        <tr>
                            <th>SĐT Người Nhận</th>
                            <th>Số lượng</th>
                        </tr>
                        </thead>
                        <tbody class="receiver-list"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
