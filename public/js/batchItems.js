document.addEventListener('DOMContentLoaded', function() {
    const itemsContainer = document.getElementById('batch-items-container');
    const itemsTableBody = document.querySelector('#batch-items-table tbody');
    const itemTemplate = document.querySelector('.batch-item-row-template .batch-item-row');
    const addItemBtn = document.getElementById('add-batch-item-btn');
    const saveItemsBtn = document.getElementById('save-batch-items-btn');

    // Function to reset form inputs
    function resetForm() {
        itemsContainer.querySelectorAll('.batch-item-row').forEach(row => row.remove());
        const newItemRow = itemTemplate.cloneNode(true);
        newItemRow.classList.remove('d-none');
        itemsContainer.appendChild(newItemRow);
    }

    // Add initial empty form row
    resetForm();

    // Add item to table
    addItemBtn.addEventListener('click', function() {
        const currentRow = itemsContainer.querySelector('.batch-item-row');
        const tradeNameEn = currentRow.querySelector('[name*="[trade_name_en]"]').value;
        const tradeNameFa = currentRow.querySelector('[name*="[trade_name_fa]"]').value;
        const costPrice = currentRow.querySelector('[name*="[cost_price]"]').value;
        const sellingPrice = currentRow.querySelector('[name*="[selling_price]"]').value;
        const quantity = currentRow.querySelector('[name*="[quantity]"]').value;
        const expirationDate = currentRow.querySelector('[name*="[expiration_date]"]').value;

        // Validate inputs
        if (!tradeNameEn || !tradeNameFa || !costPrice || !sellingPrice || !quantity) {
            alert('Please fill in all required fields before adding.');
            return;
        }

        const tableRow = document.createElement('tr');
        tableRow.innerHTML = `
            <td>${tradeNameEn} ${tradeNameFa}</td>
            <td>${costPrice}</td>
            <td>${sellingPrice}</td>
            <td>${quantity}</td>
            <td>${expirationDate ? expirationDate : 'N/A'}</td>
            <td>
                <button type="button" class="btn btn-danger delete-batch-item-btn">Remove</button>
                <input type="hidden" name="items[][trade_name_en]" value="${tradeNameEn}">
                <input type="hidden" name="items[][trade_name_fa]" value="${tradeNameFa}">
                <input type="hidden" name="items[][cost_price]" value="${costPrice}">
                <input type="hidden" name="items[][selling_price]" value="${sellingPrice}">
                <input type="hidden" name="items[][quantity]" value="${quantity}">
                <input type="hidden" name="items[][expiration_date]" value="${expirationDate}">
            </td>
        `;
        itemsTableBody.appendChild(tableRow);

        // Reset the form for new input
        resetForm();
    });

    // Remove item from the table
    itemsTableBody.addEventListener('click', function(e) {
        if (e.target.classList.contains('delete-batch-item-btn')) {
            e.target.closest('tr').remove();
        }
    });

    // Save batch items via AJAX
    saveItemsBtn.addEventListener('click', function() {
        const tableRows = itemsTableBody.querySelectorAll('tr');
        if (tableRows.length === 0) {
            alert('No items to save.');
            return;
        }

        const formData = new FormData();
        tableRows.forEach((row, index) => {
            formData.append(`items[${index}][trade_name_en]`, row.querySelector('input[name="items[][trade_name_en]"]').value);
            formData.append(`items[${index}][trade_name_fa]`, row.querySelector('input[name="items[][trade_name_fa]"]').value);
            formData.append(`items[${index}][cost_price]`, row.querySelector('input[name="items[][cost_price]"]').value);
            formData.append(`items[${index}][selling_price]`, row.querySelector('input[name="items[][selling_price]"]').value);
            formData.append(`items[${index}][quantity]`, row.querySelector('input[name="items[][quantity]"]').value);
            formData.append(`items[${index}][expiration_date]`, row.querySelector('input[name="items[][expiration_date]"]').value);
        });

        fetch(window.routes.saveBatchItems, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Batch items saved successfully!');
                window.location.href = window.routes.index;
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('An error occurred while saving batch items.');
            console.error(error);
        });
    });
});
