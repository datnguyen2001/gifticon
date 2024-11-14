document.addEventListener("DOMContentLoaded", function () {
    const toggleCheckbox = document.getElementById("toggleReceiverInfo");
    const receiverInfo = document.querySelector(".receiver-info");
    const receiverNote = document.querySelector(".buy-note");
    const ownQuantity = document.querySelector(".buy-for-me-quantity");
    const quantityInput = document.querySelector(".for-me-quantity");
    const paymentTotal = document.querySelector(".total-money");
    const receiverQuantities = document.querySelectorAll(".receiver-quantity");

    // Function to update payment total
    function updatePaymentTotal() {
        let total;

        if (toggleCheckbox.checked) {
            // Calculate total for "Mua cho người khác" (sum of all receiver-quantity inputs)
            total = 0;
            receiverQuantities.forEach(input => {
                const quantity = parseInt(input.value) || 0;
                total += quantity;
            });
            total = total * price;
        } else {
            // Calculate total for "Mua cho bản thân"
            const quantity = parseInt(quantityInput.value) || 0;
            total = price * quantity;
        }

        paymentTotal.textContent = `${total.toLocaleString()} VNĐ`;
    }

    // Event listener for toggle checkbox
    toggleCheckbox.addEventListener("change", function () {
        if (toggleCheckbox.checked) {
            receiverInfo.style.display = "block";
            receiverNote.style.display = "block";
            ownQuantity.style.display = "none";
        } else {
            receiverInfo.style.display = "none";
            receiverNote.style.display = "none";
            ownQuantity.style.display = "block";
        }
        updatePaymentTotal(); // Update total when switching between modes
    });

    quantityInput.addEventListener("input", updatePaymentTotal);

    receiverQuantities.forEach(input => {
        input.addEventListener("input", updatePaymentTotal);
    });

    updatePaymentTotal();

    const receiverContainer = document.querySelector(".list-receiver");
    receiverContainer.addEventListener("click", function(event) {
        if (event.target.classList.contains("trash-icon")) {
            const receiverItem = event.target.closest(".receiver-item");

            // Get the quantity of the receiver being deleted
            const deletedQuantity = parseInt(receiverItem.querySelector(".receiver-quantity").value) || 0;

            // Subtract this receiver's total from the current total
            let currentTotal = parseInt(paymentTotal.textContent.replace(/\D/g, '')); // Remove non-numeric characters
            currentTotal -= deletedQuantity * price;

            // Update the displayed total
            paymentTotal.textContent = `${currentTotal.toLocaleString()} VNĐ`;

            // Remove the item from the DOM
            receiverItem.remove();

            updateOrdinalNumbers();
        }
    });

    function updateOrdinalNumbers() {
        const receiverItems = document.querySelectorAll(".receiver-item .ordinal-number");
        receiverItems.forEach((item, index) => {
            item.textContent = index + 1;
        });
    }
});
