var mySwiperPromotion = new Swiper(".mySwiperPromotion", {
    slidesPerView: 3,
    spaceBetween: 16,
    loop: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false
    },
});

document.addEventListener('DOMContentLoaded', function() {
    var content = document.querySelector('.brand-content');
    var button = document.querySelector('.brand-toggle-button');

    function isContentOverflowing(element) {
        return element.scrollHeight > element.clientHeight;
    }

    if (!isContentOverflowing(content)) {
        button.style.display = 'none';
    }

    button.addEventListener('click', function() {
        if (content.classList.contains('brand-truncated')) {
            content.classList.remove('brand-truncated');
            button.innerText = 'Thu gọn';
        } else {
            content.classList.add('brand-truncated');
            button.innerText = 'Xem thêm';
        }
    });
});

$(document).ready(function() {
    function filterProducts() {
        var startPriceInput = $('#start-price').val();
        var endPriceInput = $('#end-price').val();
        var productName = $('#product-name').val().toLowerCase().trim();

        var startPrice = parseFloat(startPriceInput);
        var endPrice = parseFloat(endPriceInput);

        if (isNaN(startPrice)) startPrice = 0;
        if (isNaN(endPrice)) endPrice = Infinity;

        var anyVisible = false;

        $('.content-you-like .item-product').each(function() {
            var productPrice = parseFloat($(this).data('price'));
            var productNameData = $(this).data('name') || '';

            productNameData = productNameData.toString().toLowerCase();

            var priceMatch = productPrice >= startPrice && productPrice <= endPrice;
            var nameMatch = productNameData.includes(productName);

            if (priceMatch && nameMatch) {
                $(this).show();
                anyVisible = true;
            } else {
                $(this).hide();
            }
        });

        if (!anyVisible) {
            if (!$('.no-products-message').length) {
                $('.box-you-like').append('<p class="no-products-message">Không tìm thấy sản phẩm nào phù hợp.</p>');
            }
        } else {
            $('.no-products-message').remove();
        }
    }

    // Event listeners for the buttons
    $('#apply-button').on('click', function(e) {
        e.preventDefault();
        filterProducts();
    });

    $('#search-button').on('click', function(e) {
        e.preventDefault();
        filterProducts();
    });


    $('#start-price, #end-price, #product-name').on('keypress', function(e) {
        if (e.which == 13) { // Enter key
            e.preventDefault();
            filterProducts();
        }
    });
});

