
document.addEventListener('DOMContentLoaded', function () {
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

    // Add item to the table
    addItemBtn.addEventListener('click', function () {
        const currentRow = itemsContainer.querySelector('.item-row');
        const itemId = currentRow.querySelector('.item-select').value;
        const itemName = currentRow.querySelector('.item-select option:checked').text;
        const quantity = currentRow.querySelector('[name*="[quantity]"]').value;
        const remarks = currentRow.querySelector('[name*="[remarks]"]').value;

        // Validation checks
        if (!itemId || !quantity) {
            Swal.fire({
                title: 'Error!',
                text: 'Please fill in all required fields before adding.',
                icon: 'error',
                confirmButtonText: 'Okay'
            });
            return;
        }

        if (isNaN(quantity) || quantity <= 0) {
            Swal.fire({
                title: 'Error!',
                text: 'Quantity must be a positive number.',
                icon: 'error',
                confirmButtonText: 'Okay'
            });
            return;
        }

        const existingItem = Array.from(itemsTableBody.querySelectorAll('input[name="items[][item_id]"]'))
            .some(input => input.value === itemId);

        if (existingItem) {
            Swal.fire({
                title: 'Error!',
                text: 'This item is already added.',
                icon: 'error',
                confirmButtonText: 'Okay'
            });
            return;
        }

        // Add item to the table
        const tableRow = document.createElement('tr');
        tableRow.innerHTML = `
            <td>${itemName}</td>
            <td>${quantity}</td>
            <td>${remarks || 'N/A'}</td>
            <td>
                <button type="button" class="btn btn-danger delete-item-btn">Remove</button>
                <input type="hidden" name="items[][item_id]" value="${itemId}">
                <input type="hidden" name="items[][quantity]" value="${quantity}">
                <input type="hidden" name="items[][remarks]" value="${remarks}">
            </td>
        `;

        itemsTableBody.appendChild(tableRow);

        // Reset the form for new input
        currentRow.querySelectorAll('input, select').forEach(input => input.value = '');
    });

    // Remove item from the table
    itemsTableBody.addEventListener('click', function (e) {
        if (e.target.classList.contains('delete-item-btn')) {
            e.target.closest('tr').remove();
        }
    });

    // Save items via AJAX
    saveItemsBtn.addEventListener('click', function () {
        const tableRows = itemsTableBody.querySelectorAll('tr');
        if (tableRows.length === 0) {
            Swal.fire({
                title: 'Warning!',
                text: 'No items to save.',
                icon: 'warning',
                confirmButtonText: 'Okay'
            });
            return;
        }

        const formData = new FormData();
        tableRows.forEach((row, index) => {
            formData.append(`items[${index}][item_id]`, row.querySelector('input[name="items[][item_id]"]').value);
            formData.append(`items[${index}][quantity]`, row.querySelector('input[name="items[][quantity]"]').value);
            formData.append(`items[${index}][remarks]`, row.querySelector('input[name="items[][remarks]"]').value || '');
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
            if (data.success) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Items saved successfully!',
                    icon: 'success',
                    confirmButtonText: 'Okay'
                }).then(() => {
                    window.location.href = window.routes.index;
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message || 'An error occurred while saving items.',
                    icon: 'error',
                    confirmButtonText: 'Okay'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'An unexpected error occurred.',
                icon: 'error',
                confirmButtonText: 'Okay'
            });
        });
    });
});


