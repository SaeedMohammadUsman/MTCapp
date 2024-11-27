document.addEventListener('DOMContentLoaded', function() {
    const itemsContainer = document.getElementById('items-container');
    const itemsTableBody = document.querySelector('#items-table tbody');
    const itemTemplate = document.querySelector('.item-row-template .item-row');
    const addItemBtn = document.getElementById('add-item-btn');
    const saveItemsBtn = document.getElementById('save-items-btn');

    // Function to reset form inputs
    function resetForm() {
        itemsContainer.querySelectorAll('.item-row').forEach(row => row.remove());
        const newItemRow = itemTemplate.cloneNode(true);
        newItemRow.classList.remove('d-none');
        itemsContainer.appendChild(newItemRow);
    }

    // Add initial empty form row
    resetForm();

    // Add item to table
    addItemBtn.addEventListener('click', function() {
        const currentRow = itemsContainer.querySelector('.item-row');
        const tradeNameEn = currentRow.querySelector('[name*="[trade_name_en]"]').value;
        const tradeNameFa = currentRow.querySelector('[name*="[trade_name_fa]"]').value;
        const unitPrice = currentRow.querySelector('[name*="[unit_price]"]').value;
        const quantity = currentRow.querySelector('[name*="[quantity]"]').value;
        const remarks = currentRow.querySelector('[name*="[remarks]"]').value;

        // Validate inputs
        if (!tradeNameEn || !tradeNameFa || !unitPrice || !quantity) {
            alert('Please fill in all required fields before adding.');
            return;
        }

        const tableRow = document.createElement('tr');
        tableRow.innerHTML = `
            <td>${currentRow.querySelector('.item-select option:checked').text}</td>
            <td>${tradeNameEn}</td>
            <td>${tradeNameFa}</td>
            <td>${unitPrice}</td>
            <td>${quantity}</td>
            <td>
                <button type="button" class="btn btn-danger delete-item-btn">Remove</button>
                <input type="hidden" name="items[][item_id]" value="${currentRow.querySelector('.item-select').value}">
                <input type="hidden" name="items[][trade_name_en]" value="${tradeNameEn}">
                <input type="hidden" name="items[][trade_name_fa]" value="${tradeNameFa}">
                <input type="hidden" name="items[][unit_price]" value="${unitPrice}">
                <input type="hidden" name="items[][quantity]" value="${quantity}">
                <input type="hidden" name="items[][remarks]" value="${remarks}">
            </td>
        `;
        itemsTableBody.appendChild(tableRow);

        // Reset the form for new input
        resetForm();
    });

    // Remove item from the table
    itemsTableBody.addEventListener('click', function(e) {
        if (e.target.classList.contains('delete-item-btn')) {
            e.target.closest('tr').remove();
        }
    });

    // Save items via AJAX
    saveItemsBtn.addEventListener('click', function() {
        const tableRows = itemsTableBody.querySelectorAll('tr');
        if (tableRows.length === 0) {
            alert('No items to save.');
            return;
        }

        const formData = new FormData();
        tableRows.forEach((row, index) => {
            formData.append(`items[${index}][item_id]`, row.querySelector('input[name="items[][item_id]"]').value);
            formData.append(`items[${index}][trade_name_en]`, row.querySelector('input[name="items[][trade_name_en]"]').value);
            formData.append(`items[${index}][trade_name_fa]`, row.querySelector('input[name="items[][trade_name_fa]"]').value);
            formData.append(`items[${index}][used_for_en]`, row.querySelector('input[name="items[][used_for_en]"]')?.value || '');
            formData.append(`items[${index}][used_for_fa]`, row.querySelector('input[name="items[][used_for_fa]"]')?.value || '');
            formData.append(`items[${index}][size]`, row.querySelector('input[name="items[][size]"]')?.value || '');
            formData.append(`items[${index}][c_size]`, row.querySelector('input[name="items[][c_size]"]')?.value || '');
            formData.append(`items[${index}][unit_price]`, row.querySelector('input[name="items[][unit_price]"]').value);
            formData.append(`items[${index}][quantity]`, row.querySelector('input[name="items[][quantity]"]').value);
            formData.append(`items[${index}][remarks]`, row.querySelector('input[name="items[][remarks]"]')?.value || '');
        });

        fetch(window.routes.saveItems, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response:', data);
            if (data.success) {
                // alert('Items saved successfully!');
                window.location.href = window.routes.index;
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving items.');
        });
    });

});



