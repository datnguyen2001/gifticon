document.addEventListener("DOMContentLoaded", function () {
    const toggleCheckbox = document.getElementById("toggleReceiverInfo");
    const receiverInfo = document.querySelector(".receiver-info");
    const receiverNote = document.querySelector(".buy-note");
    const ownQuantity = document.querySelector(".buy-for-me-quantity");
    const quantityInput = document.querySelector(".for-me-quantity");
    const paymentTotal = document.querySelector(".total-money");

    // Function to update payment total
    function updatePaymentTotal() {
        let total;

        if (toggleCheckbox.checked) {
            // Dynamically select all receiver-quantity inputs to ensure we always get the latest elements
            total = 0;
            document.querySelectorAll(".receiver-quantity").forEach(input => {
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

    // Function to add event listeners to all receiver-quantity inputs
    function addReceiverQuantityListeners() {
        document.querySelectorAll(".receiver-quantity").forEach(input => {
            input.addEventListener("input", updatePaymentTotal);
        });
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

    // Initial call to add event listeners to the receiver-quantity inputs and calculate total
    addReceiverQuantityListeners();
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
            updatePaymentTotal(); // Recalculate total after deletion
        }
    });

    function updateOrdinalNumbers() {
        const receiverItems = document.querySelectorAll(".receiver-item .ordinal-number");
        receiverItems.forEach((item, index) => {
            item.textContent = index + 1;
        });
    }

    // Trigger file selection
    document.querySelector('.excel-upload').addEventListener('click', function () {
        document.getElementById('excelFileInput').click();
    });

    // Handle file upload and parsing with loading
    document.getElementById('excelFileInput').addEventListener('change', async function (e) {
        const file = e.target.files[0];
        if (file) {
            showLoading();
            try {
                const data = await file.arrayBuffer();
                const workbook = XLSX.read(data, { type: 'array' });
                const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                const rows = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });

                const listReceiver = document.getElementById('listReceiver');
                listReceiver.innerHTML = ''; // Clear existing content

                // Process each row, starting from the second row if the first row is headers
                rows.slice(1).forEach((row, index) => {
                    const [phoneNumber, quantity] = row;
                    if (phoneNumber && quantity) {
                        const receiverItem = document.createElement('div');
                        receiverItem.className = 'receiver-item';
                        receiverItem.dataset.index = index + 1;
                        receiverItem.innerHTML = `
                            <div class="ordinal-number">${index + 1}</div>
                            <input type="text" value="${phoneNumber}" class="receiver-phone">
                            <input type="number" value="${quantity}" class="receiver-number receiver-quantity" id="receiver-number">
                            <img src="${deleteIcon}" alt="trash icon" class="trash-icon" />
                        `;
                        listReceiver.appendChild(receiverItem);
                    }
                });

                // Add listeners to new receiver-quantity inputs and update the payment total
                addReceiverQuantityListeners();
                updatePaymentTotal();
            } catch (error) {
                console.error("Error processing file:", error);
            } finally {
                hideLoading();
            }
        }
    });
});
