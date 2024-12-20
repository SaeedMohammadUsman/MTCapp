document.addEventListener("DOMContentLoaded", function () {
    const itemsContainer = document.getElementById("received-goods-items-container");
    const itemsTableBody = document.querySelector("#received-goods-items-table tbody");
    const itemTemplate = document.querySelector(
        ".received-good-item-row-template .received-good-item-row"
    );
    const addItemBtn = document.getElementById("add-received-good-item-btn");
    const saveItemsBtn = document.getElementById("save-received-good-items-btn");
    let itemIndex = 0; // To keep track of the item index
    let addedItems = [];
    // Function to reset form inputs
    function resetForm() {
        const currentRow = itemsContainer.querySelector(
            ".received-good-item-row:last-child"
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
            ".received-good-item-row:last-child"
        );
        const itemName = currentRow.querySelector(
            '[name*="[item_id]"]'
        ).value;
        const vendorPrice = currentRow.querySelector(
            '[name*="[vendor_price]"]'
        ).value;
        const quantity = currentRow.querySelector('[name*="[quantity]"]').value;
        const expirationDate = currentRow.querySelector(
            '[name*="[expiration_date]"]'
        ).value;

        // Validate inputs
        if (!itemName || !vendorPrice || !quantity) {
            alert("Please fill in all required fields before adding.");
            return;
        }

        // Extract item_id from item_name
        const [item_id] = itemName.split("|");
        if (addedItems.includes(item_id)) {
            Swal.fire({
                icon: "error",
                title: "Duplicate Item",
                text: "This item has already been added.",
                timer: 3000, // auto-close after 3 seconds
                toast: true,
                position: "top-end",
                showConfirmButton: false,
            });
            return;
        }  // Validate inputs
       
        const tableRow = document.createElement("tr");
        tableRow.innerHTML = `
            <td>${itemName}</td>
            <td>${vendorPrice}</td>
            <td>${quantity}</td>
            <td>${expirationDate ? expirationDate : "N/A"}</td>
            <td>
                <button type="button" class="btn btn-danger delete-received-good-item-btn">Remove</button>
                <input type="hidden" name="items[${itemIndex}][item_id]" value="${item_id}">
                <input type="hidden" name="items[${itemIndex}][vendor_price]" value="${vendorPrice}">
                <input type="hidden" name="items[${itemIndex}][quantity]" value="${quantity}">
                <input type="hidden" name="items[${itemIndex}][expiration_date]" value="${expirationDate}">
            </td>
        `;
        itemsTableBody.appendChild(tableRow);
        itemIndex++; // Increment the item index for the next entry
        addedItems.push(item_id);
        // Reset the form for new input
        resetForm();
    });

    // Remove item from the table
    itemsTableBody.addEventListener("click", function (e) {
        if (e.target.classList.contains("delete-received-good-item-btn")) {
            const row = e.target.closest("tr");
            const itemId = row.querySelector('input[name*="[item_id]"]').value;
            addedItems = addedItems.filter(item => item !== itemId); // Remove item_id from the array
            row.remove();
        }
    });
    
    

    // Save items
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
                `items[${index}][vendor_price]`,
                row.querySelector('input[name^="items["][name$="[vendor_price]"]').value
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

        fetch(window.routes.saveReceivedGoodItems, {
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
