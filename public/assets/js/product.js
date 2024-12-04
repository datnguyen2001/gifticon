function setActiveTab(tab) {
    document.querySelectorAll('.product-wrapper, .store-wrapper').forEach((el) => {
        el.classList.remove('active');
    });

    if (tab === 'detail') {
        document.querySelector('.product-wrapper').classList.add('active');
        document.querySelector('.detail-tab').style.display = 'block';
        document.querySelector('.guide-tab').style.display = 'none';
    } else if (tab === 'guide') {
        document.querySelector('.store-wrapper').classList.add('active');
        document.querySelector('.guide-tab').style.display = 'block';
        document.querySelector('.detail-tab').style.display = 'none';
    }
}

$(document).ready(function() {
    $('.buy-now').on('click', function(e) {
        var quantity = $(this).data('quantity');
        var route = $(this).data('route');

        if (parseInt(quantity) === 0) {
            e.preventDefault();
            hideLoading();

            $(".failed-message").text("Sản phẩm đã hết hàng!");
            $(".failed-alert").fadeIn();
            $(".failed-overlay").fadeIn();
        } else if (parseInt(quantity) > 0) {
            window.location.href = route;
        }
    });

    $('.add-to-cart').on('click', function(e) {
        e.preventDefault();
        hideLoading();
        var quantity = $(this).data('quantity');
        if(!userLogin){
            $(".warning-message").text("Vui lòng đăng nhập để thực hiện chức năng này!");
            $(".warning-alert").fadeIn();
            $(".warning-overlay").fadeIn();
            return;
        }
        if (parseInt(quantity) === 0) {
            $(".failed-message").text("Sản phẩm đã hết hàng!");
            $(".failed-alert").fadeIn();
            $(".failed-overlay").fadeIn();
        } else if (parseInt(quantity) > 0) {
            $("#addtoCartModel").modal('show');
        }
    });
});
