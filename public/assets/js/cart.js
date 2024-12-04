document.addEventListener("DOMContentLoaded", function () {
    const tickBoxes = document.querySelectorAll(".tick-box");
    const selectedCartInput = document.getElementById("selected_cart_id");
    const totalPriceElement = document.querySelector(".content-total-price");

    // Function to update the selected_cart_id input and total price
    function updateSelectedCartIds() {
        const selectedCartIds = [];
        let totalPrice = 0;

        tickBoxes.forEach((checkbox) => {
            if (checkbox.checked) {
                selectedCartIds.push(checkbox.closest('.item-cart').querySelector('form [name="cart_id"]').value);
                totalPrice += parseFloat(checkbox.getAttribute("data-total-price")) || 0;
            }
        });

        // Update the hidden input with the selected cart IDs
        selectedCartInput.value = selectedCartIds.join(',');

        // Update the total price in the UI
        totalPriceElement.textContent = `${totalPrice.toLocaleString()} VNĐ`;
    }

    // Attach event listeners to checkboxes
    tickBoxes.forEach((checkbox) => {
        checkbox.addEventListener("change", updateSelectedCartIds);
    });

    // Initialize selected cart IDs and total price on page load
    updateSelectedCartIds();
});

$(document).ready(function() {
    $(".note-icon").on("click", function() {
        var cart = $(this).data('cart');
        var product = cart.product;

        // Populate the modal with dynamic content
        $(".cart-detail-img").attr("src", imgSrc + product.src);
        $(".cart-detail-name").text(product.name);
        $("#price-sp-cart").text(cart.total_price.toLocaleString() + ' VNĐ');
        $(".cart-detail-price").text((cart.total_price / cart.quantity).toLocaleString() + ' VNĐ');
        $(".cart-detail-quantity").text(cart.quantity);
        $(".cart-detail-start-date").text(new Date(product.start_date).toLocaleDateString('en-GB'));
        $(".cart-detail-end-date").text(new Date(product.end_date).toLocaleDateString('en-GB'));

        if (cart.buy_for == 1) {
            $(".buy-for-type").text("Mua cho bản thân");
            $(".table-bordered").hide(); // Hide the table for 'Mua cho bản thân'
        } else if (cart.buy_for == 2) {
            $(".buy-for-type").text("Mua cho người khác");
            $(".table-bordered").show(); // Show the table for 'Mua cho người khác'
        }

        // Clear any previous rows in the receiver list
        $(".receiver-list").empty();

        // Add rows for each receiver
        if (cart.receivers && cart.receivers.length > 0) {
            cart.receivers.forEach(function(receiver) {
                var row = '<tr>' +
                    '<td>' + receiver.phone + '</td>' +
                    '<td>' + receiver.quantity + '</td>' +
                    '</tr>';
                $(".receiver-list").append(row);
            });
        } else {
            var row = '<tr><td colspan="2">Chưa có người nhận</td></tr>';
            $(".receiver-list").append(row);
        }

        $("#noteModal").modal('show');
    });
});

