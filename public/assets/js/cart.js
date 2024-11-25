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
