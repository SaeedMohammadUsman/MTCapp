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
                                    @foreach ($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->trade_name_en }} ({{ $item->trade_name_fa }})</option>
                                @endforeach
                                </select>
                            </div>
                         
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="number" name="items[0][quantity]" class="form-control" value="1" min="1" required>
                            </div>
                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea name="items[0][remarks]" class="form-control"></textarea>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" id="add-item-btn">Add Another Item</button>
                <button type="button" class="btn btn-success" id="save-items-btn">Save Items</button>
                <a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary">Back to Orders</a>
            </form>

            <h3 class="mt-4">Items to Add</h3>
            <table class="table table-bordered" id="items-table">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dynamically added items will appear here -->
                </tbody>
            </table>
        </div>
    </div>
@stop


@section('js')
<script src="{{ asset('js/custom.js') }}"></script>

<script>
    window.routes = {
        saveItems: "{{ route('purchase_orders.items.store', ['purchase_order' => $purchaseOrder->id]) }}",
        index: "{{ route('purchase_orders.index') }}"
    };

    // console.log(routes.saveItems);
    // console.log(routes.index);
</script>


