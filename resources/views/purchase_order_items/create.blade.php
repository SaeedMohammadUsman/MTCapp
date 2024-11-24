@extends('adminlte::page')

@section('title', 'Add Items to Purchase Order')

@section('content_header')
    <h1>Add Items to Purchase Order #{{ $purchaseOrder->order_number }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form id="purchaseOrderItemsForm">
                @csrf
                <div id="items-container">
                    <div class="item-row-template d-none">
                        <div class="item-row">
                            <div class="form-group">
                                <label>Select Item</label>
                                <select name="items[0][item_id]" class="form-control item-select" required>
                                    <option value="" disabled selected>Select an Item</option>
                                    @foreach ($inventoryItems as $item)
                                        <option value="{{ $item->id }}">{{ $item->item_name_en }} ({{ $item->item_name_fa }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Trade Name (English)</label>
                                <input type="text" name="items[0][trade_name_en]" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Trade Name (Persian)</label>
                                <input type="text" name="items[0][trade_name_fa]" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Used For (English)</label>
                                <input type="text" name="items[0][used_for_en]" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Used For (Persian)</label>
                                <input type="text" name="items[0][used_for_fa]" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Size</label>
                                <input type="text" name="items[0][size]" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Consumer Size</label>
                                <input type="text" name="items[0][c_size]" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Unit Price</label>
                                <input type="number" name="items[0][unit_price]" class="form-control" step="0.01" required>
                            </div>

                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="number" name="items[0][quantity]" class="form-control" value="1" min="1" required>
                            </div>

                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea name="items[0][remarks]" class="form-control"></textarea>
                            </div>
                            <button type="button" class="btn btn-danger remove-item-btn">Remove</button>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-primary" id="add-item-btn">Add Another Item</button>
                <button type="button" class="btn btn-success" id="save-items-btn">Save Items</button>
                <a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary">Back to Orders</a>
            </form>
            <div id="form-feedback" class="mt-3"></div>
        </div>
    </div>
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const itemsContainer = document.getElementById('items-container');
            const itemTemplate = document.querySelector('.item-row-template .item-row');

            // Add New Item
            document.getElementById('add-item-btn').addEventListener('click', function () {
                const newItemRow = itemTemplate.cloneNode(true);
                itemsContainer.appendChild(newItemRow);
            });

            // Remove Item
            itemsContainer.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-item-btn')) {
                    e.target.closest('.item-row').remove();
                }
            });

            // Save Items via AJAX
            document.getElementById('save-items-btn').addEventListener('click', function () {
                const formData = new FormData(document.getElementById('purchaseOrderItemsForm'));

                fetch('{{ route("purchase_orders.items.store", ["purchase_order" => $purchaseOrder->id]) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const feedbackDiv = document.getElementById('form-feedback');
                    if (data.success) {
                        feedbackDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                        // Redirect after successful submission
                        window.location.href = '{{ route("purchase_orders.index") }}';
                    } else {
                        feedbackDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while saving items.');
                });
            });
        });
    </script>
@stop
