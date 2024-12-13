document.addEventListener("DOMContentLoaded", function () {
    const itemsContainer = document.getElementById("batch-items-container");
    const itemsTableBody = document.querySelector("#batch-items-table tbody");
    const itemTemplate = document.querySelector(
        ".batch-item-row-template .batch-item-row"
    );
    const addItemBtn = document.getElementById("add-batch-item-btn");
    const saveItemsBtn = document.getElementById("save-batch-items-btn");
    let itemIndex = 0; // To keep track of the item index

    // Function to reset form inputs
    function resetForm() {
        const currentRow = itemsContainer.querySelector(
            ".batch-item-row:last-child"
        );
        currentRow.querySelectorAll("input, select").forEach((input) => {
            input.value = ""; // Reset input values
        });
        currentRow.querySelector("select").selectedIndex = 0; // Reset select
    }

    // Add initial empty form row
    if (itemTemplate) {
        const initialRow = itemTemplate.cloneNode(true);
        initialRow.classList.remove("d-none");
        itemsContainer.appendChild(initialRow);
    }

    // Add item to table
    addItemBtn.addEventListener("click", function () {
        const currentRow = itemsContainer.querySelector(
            ".batch-item-row:last-child"
        );
        const tradeName = currentRow.querySelector(
            '[name*="[trade_name]"]'
        ).value;
        const costPrice = currentRow.querySelector(
            '[name*="[cost_price]"]'
        ).value;
        const sellingPrice = currentRow.querySelector(
            '[name*="[selling_price]"]'
        ).value;
        const quantity = currentRow.querySelector('[name*="[quantity]"]').value;
        const expirationDate = currentRow.querySelector(
            '[name*="[expiration_date]"]'
        ).value;

        // Validate inputs
        if (!tradeName || !costPrice || !sellingPrice || !quantity) {
            alert("Please fill in all required fields before adding.");
            return;
        }

        // Extract item_id from trade_name
        const [item_id] = tradeName.split("|");
        const tableRow = document.createElement("tr");
        tableRow.innerHTML = `
            <td>${tradeName}</td>
            <td>${costPrice}</td>
            <td>${sellingPrice}</td>
            <td>${quantity}</td>
            <td>${expirationDate ? expirationDate : "N/A"}</td>
            <td>
                <button type="button" class="btn btn-danger delete-batch-item-btn">Remove</button>
                <input type="hidden" name="items[${itemIndex}][item_id]" value="${item_id}">
                <input type="hidden" name="items[${itemIndex}][trade_name]" value="${tradeName}">
                <input type="hidden" name="items[${itemIndex}][cost_price]" value="${costPrice}">
                <input type="hidden" name="items[${itemIndex}][selling_price]" value="${sellingPrice}">
                <input type="hidden" name="items[${itemIndex}][quantity]" value="${quantity}">
                <input type="hidden" name="items[${itemIndex}][expiration_date]" value="${expirationDate}">
            </td>
        `;
        itemsTableBody.appendChild(tableRow);
        itemIndex++; // Increment the item index for the next entry

        // Reset the form for new input
        resetForm();
    });

    // Remove item from the table
    itemsTableBody.addEventListener("click", function (e) {
        if (e.target.classList.contains("delete-batch-item-btn")) {
            e.target.closest("tr").remove();
        }
    });


    saveItemsBtn.addEventListener("click", function () {
        const tableRows = itemsTableBody.querySelectorAll("tr");
        if (tableRows.length === 0) {
            alert("No items to save.");
            return;
        }
        const formData = new FormData();
        tableRows.forEach((row, index) => {
            formData.append(
                `items[${index}][item_id]`,
                row.querySelector('input[name^="items["][name$="[item_id]"]').value
            );
            formData.append(
                `items[${index}][trade_name]`,
                row.querySelector('input[name^="items["][name$="[trade_name]"]').value
            );
            formData.append(
                `items[${index}][cost_price]`,
                row.querySelector('input[name^="items["][name$="[cost_price]"]').value
            );
            formData.append(
                `items[${index}][selling_price]`,
                row.querySelector('input[name^="items["][name$="[selling_price]"]').value
            );
            formData.append(
                `items[${index}][quantity]`,
                row.querySelector('input[name^="items["][name$="[quantity]"]').value
            );
            formData.append(
                `items[${index}][expiration_date]`,
                row.querySelector('input[name^="items["][name$="[expiration_date]"]').value
            );
        });
                                                                               
        fetch(window.routes.saveBatchItems, {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                Accept: "application/json",
            },
        })
            .then((response) => {
                if (!response.ok) {
                    return response.json().then((error) => {
                        throw new Error(error.message || "An error occurred");
                    });
                }
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: data.flash_message,
                        timer: 3000, // auto-close after 3 seconds
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                    }).then(() => {
                        window.location.href = data.redirect_url;
                    });
                }
            })
            .catch((error) => {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: error.message,
                    timer: 3000, // auto-close after 3 seconds
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                });
            });
});                                   
});
