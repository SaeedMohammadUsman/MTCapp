@extends('adminlte::page')
@section('title', 'Add Price Package Details')

@section('content_header')
    <h1>Add Items to Price Package #{{ $pricePackage->id }}</h1>
@stop
@section('content')
    <div class="card">
        <div class="card-body">
            <form id="pricePackageDetailsForm" method="POST" action="{{ route('packages.details.store', ['package' => $pricePackage->id]) }}">
                @csrf
                <div id="item-form-container">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="item_id">Item</label>
                            <select name="items[0][item_id]" id="item_id" class="form-control">
                                <option value="">Select Item</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}" data-arrival-price="{{ $item->fifo_arrival_price }}">
                                        {{ $item->trade_name_en }} ({{ $item->trade_name_fa }})
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="items[0][quantity]" value="1"> 
                        </div>
                        <div class="form-group col-md-3">
                            <label for="arrival_price">Arrival Price</label>
                            <input type="number" id="arrival_price" class="form-control" readonly>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="discount">Discount (%)</label>
                            <input type="number" id="discount" class="form-control" step="0.01" min="0" max="100">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="final_price">Final Price</label>
                            <input type="number" id="final_price" class="form-control" readonly>
                        </div>
                    </div>
                    <button type="button" id="add-item-btn" class="btn btn-primary">Add Item</button>
                </div>

                <h3 class="mt-4">Items to Add</h3>
                <table class="table table-bordered" id="items-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Arrival Price</th>
                            <th>Discount (%)</th>
                            <th>Final Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamically added rows will appear here -->
                    </tbody>
                </table>

                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('packages.index') }}" class="btn btn-secondary">Back to Packages</a>
            </form>
        </div>
    </div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const itemSelect = document.getElementById("item_id");
    const arrivalPriceInput = document.getElementById("arrival_price");
    const discountInput = document.getElementById("discount");
    const finalPriceInput = document.getElementById("final_price");
    const addItemBtn = document.getElementById("add-item-btn");
    const itemsTableBody = document.querySelector("#items-table tbody");

    let addedItems = [];

    // Update arrival price when item changes
    itemSelect.addEventListener("change", function () {
    const selectedOption = itemSelect.options[itemSelect.selectedIndex];
    
    const arrivalPrice = selectedOption.getAttribute("data-arrival-price") || 0;
    arrivalPriceInput.value = parseFloat(arrivalPrice).toFixed(2);
    calculateFinalPrice();
});
    
   

    // Calculate final price
    discountInput.addEventListener("input", calculateFinalPrice);

    function calculateFinalPrice() {
        const arrivalPrice = parseFloat(arrivalPriceInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        const finalPrice = arrivalPrice - (arrivalPrice * discount / 100);
        finalPriceInput.value = finalPrice.toFixed(2);
    }

    addItemBtn.addEventListener("click", function () {
    const selectedOption = itemSelect.options[itemSelect.selectedIndex];
    const itemId = selectedOption.value;
    const itemName = selectedOption.textContent;
    const arrivalPrice = selectedOption.getAttribute("data-arrival-price"); // Use the data attribute for FIFO
    const discount = discountInput.value;
    const finalPrice = (parseFloat(arrivalPrice) - (parseFloat(arrivalPrice) * (parseFloat(discount) || 0) / 100)).toFixed(2); // Calculate final price based on FIFO arrival price

    if (!itemId || !arrivalPrice || !finalPrice) {
        Swal.fire({
            title: 'Error!',
            text: 'Please fill out all fields before adding an item.',
            icon: 'error',
            confirmButtonText: 'Okay',
            toast: true,
            position: 'top-end',
            timer: 3000
        });
        return;
    }

    if (addedItems.includes(itemId)) {
        Swal.fire({
            title: 'Warning!',
            text: 'This item is already added.',
            icon: 'warning',
            confirmButtonText: 'Okay',
            toast: true,
            position: 'top-end',
            timer: 3000
        });
        return;
    }

    const tableRow = document.createElement("tr");
    tableRow.innerHTML = `
        <td>${itemName}</td>
        <td>${arrivalPrice}</td>
        <td>${discount || "0"}</td>
        <td>${finalPrice}</td>
        <td>
            <button type="button" class="btn btn-danger btn-sm remove-item-btn">Remove</button>
            <input type="hidden" name="items[${addedItems.length}][item_id]" value="${itemId}">
            <input type="hidden" name="items[${addedItems.length}][arrival_price]" value="${arrivalPrice}">
            <input type="hidden" name="items[${addedItems.length}][discount]" value="${discount}">
            <input type="hidden" name="items[${addedItems.length}][price]" value="${finalPrice}">
        </td>
    `;

    itemsTableBody.appendChild(tableRow);
    addedItems.push(itemId);

    // Reset form fields
    resetForm();
});

    // Remove item from table
    itemsTableBody.addEventListener("click", function (e) {
        if (e.target.classList.contains("remove-item-btn")) {
            const row = e.target.closest("tr");
            const itemId = row.querySelector('input[name*="[item_id]"]').value;
            addedItems = addedItems.filter(id => id !== itemId);
            row.remove();
        }
    });

    function resetForm() {
        itemSelect.value = "";
        arrivalPriceInput.value = "";
        discountInput.value = "";
        finalPriceInput.value = "";
    }

    @if (session('success'))
        Swal.fire({
            title: 'Success!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'Okay',
            timer: 3000,
            toast: true,
            position: 'top-end'
        });
    @endif

    @if (session('error'))
        Swal.fire({
            title: 'Error!',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonText: 'Okay',
            timer: 3000,
            toast: true,
            position: 'top-end'
        });
    @endif

    @if (session('warning'))
        Swal.fire({
            title: 'Warning!',
            text: "{{ session('warning') }}",
            icon: 'warning',
            confirmButtonText: 'Okay',
            timer: 3000,
            toast: true,
            position: 'top-end'
        });
    @endif

    @if (session('info'))
        Swal.fire({
            title: 'Info!',
            text: "{{ session('info') }}",
            icon: 'info',
            confirmButtonText: 'Okay',
            timer: 3000,
            toast: true,
            position: 'top-end'
        });
    @endif
    
    
    
});
</script>
@stop




