@extends('adminlte::page')

@section('title', 'Customer Order Details')

@section('content_header')
    <h1>Customer Order: #{{ $customerOrder->id }}</h1>
@stop
@section('content')
    <div class="card">
        <div class="card-body">
            <!-- Order Information -->
            <p><strong>Customer:</strong> {{ $customerOrder->customer->customer_name_en }}
                {{ $customerOrder->customer->customer_name_fa }}</p>
            <p><strong>Status:</strong> {{ ucfirst($customerOrder->status) }}</p>
            <p><strong>Total Amount:</strong> {{ number_format($customerOrder->total_amount, 2) }}</p>
            <p><strong>Order Date:</strong> {{ $customerOrder->order_date->format('Y-m-d H:i') }}</p>
            <p><strong>Remarks:</strong> {{ $customerOrder->remarks ?? 'N/A' }}</p>

            <!-- Order Items -->
            <h3>Order Items</h3>
            @if ($customerOrder->orderItems->isEmpty())
                <p>No items in this order.</p>
            @else
                <table class="table table-hover table-striped table-sm">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customerOrder->orderItems  as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->item->trade_name_en }} {{ $item->item->trade_name_fa }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price, 2) }}</td>
                                <td>{{ number_format($item->quantity * $item->price, 2) }}</td>
                                <td>
                                    <form action="{{ route('customer_orders.items.destroy', ['customer_order' => $customerOrder->id, 'customer_order_item' => $item->id]) }}" method="POST" id="delete-item-{{ $item->id }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            onclick="confirmDelete(event, 'delete-item-{{ $item->id }}')"
                                            class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <!-- Add New Item Button -->
            {{-- <a href="{{ route('customer_orders.items.create', $customerOrder->id) }}" class="btn btn-primary mt-3">Add New Item</a> --}}
            <button type="button" id="addNewItemBtn" data-id="{{ $customerOrder->id }}" class="btn btn-primary mt-3">
                Add Item
            </button>


            <!-- Back to Orders List -->
            <a href="{{ route('customer_orders.index') }}" class="btn btn-secondary mt-3">Back to List</a>
        </div>
    </div>





    {{-- !-- Add Item Modal --> --}}
    <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemModalLabel">Add Item to Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addItemForm">
                    @csrf
                    <input type="hidden" name="customer_order_id" id="customerOrderId">
                    <div class="modal-body">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Item Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                <!-- Items will be loaded here via AJAX -->
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Open modal and load items
        $('#addNewItemBtn').click(function() {
            let orderId = $(this).data('id');
            $('#customerOrderId').val(orderId);

            $.ajax({
                url: "{{ url('customer-orders') }}/" + orderId + "/items/create",
                method: "GET",
                success: function(response) {
                    $('#itemsTableBody').html('');
                    if (response.items && response.items.length > 0) {
                        response.items.forEach(function(item) {
                            if (item.price_package_details && item.price_package_details.length > 0) {
                                let priceDetail = item.price_package_details[0];
                                $('#itemsTableBody').append(`
                                    <tr>
                                        <td><input type="checkbox" name="item_ids[]" value="${item.id}"></td>
                                        <td>${item.trade_name_en} ${item.trade_name_fa || ''}</td>
                                        <td>${priceDetail.price}</td>
                                        <td><input type="number" name="quantities[${item.id}]" min="1" value="1" class="form-control form-control-sm"></td>
                                    </tr>
                                `);
                            }
                        });
                        $('#addItemModal').modal('show');
                    } else {
                        Swal.fire({
                            title: 'Info!',
                            text: 'No items available.',
                            icon: 'info',
                            confirmButtonText: 'Okay',
                            timer: 3000,
                            toast: true,
                            position: 'top-end'
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Error loading items: ' + xhr.responseText,
                        icon: 'error',
                        confirmButtonText: 'Okay',
                        timer: 3000,
                        toast: true,
                        position: 'top-end'
                    });
                }
            });
        });

        // Submit the form via AJAX
        $('#addItemForm').submit(function(event) {
            event.preventDefault();

            $.ajax({
                url: "{{ route('customer_orders.items.store', $customerOrder->id) }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    $('#addItemModal').modal('hide');
                    Swal.fire({
                        title: 'Success!',
                        text: response.message || 'Items added successfully.',
                        icon: 'success',
                        confirmButtonText: 'Okay',
                        timer: 3000,
                        toast: true,
                        position: 'top-end'
                    });
                    location.reload(); // Reload to reflect changes
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Error adding items.',
                        icon: 'error',
                        confirmButtonText: 'Okay',
                        timer: 3000,
                        toast: true,
                        position: 'top-end'
                    });
                }
            });
        });
    });
</script>

@stop
