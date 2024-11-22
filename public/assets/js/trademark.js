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

        // Get the active category ID
        var activeCategoryId = $('.item-menu-trademark.menu-trademark-active').data('category-id');

        $('.content-you-like .item-product').each(function() {
            var productCategoryId = $(this).data('category-id');

            // Only consider products in the active category
            if (productCategoryId == activeCategoryId) {
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
            } else {
                // Hide products not in the active category
                $(this).hide();
            }
        });

        if (!anyVisible) {
            if (!$('.no-products-message').length) {
                $('.content-you-like').append('<p class="no-products-message">Không tìm thấy sản phẩm nào phù hợp.</p>');
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
