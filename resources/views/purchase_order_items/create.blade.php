@extends('adminlte::page')

@section('title', 'Add Items to Purchase Order')

@section('content_header')
    <h1>Add Items to Purchase Order #{{ $purchaseOrder->order_number }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('purchase_orders.items.store', ['purchase_order' => $purchaseOrder->id]) }}"  method="POST">
                @csrf
                <div id="items-container">
                    <div class="item-row">
                        <div class="form-group">
                            <label for="item_id_0">Select Item</label>
                            <select name="items[0][item_id]" id="item_id_0" class="form-control" required>
                                <option value="" disabled selected>Select an Item</option>
                                @foreach ($inventoryItems as $item)
                                    <option value="{{ $item->id }}">{{ $item->item_name_en }} ({{ $item->item_name_fa }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="trade_name_en_0">Trade Name (English)</label>
                            <input type="text" name="items[0][trade_name_en]" id="trade_name_en_0" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="trade_name_fa_0">Trade Name (Persian)</label>
                            <input type="text" name="items[0][trade_name_fa]" id="trade_name_fa_0" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="used_for_en_0">Used For (English)</label>
                            <input type="text" name="items[0][used_for_en]" id="used_for_en_0" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="used_for_fa_0">Used For (Persian)</label>
                            <input type="text" name="items[0][used_for_fa]" id="used_for_fa_0" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="size_0">Size</label>
                            <input type="text" name="items[0][size]" id="size_0" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="c_size_0">Consumer Size</label>
                            <input type="text" name="items[0][c_size]" id="c_size_0" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="unit_price_0">Unit Price</label>
                            <input type="number" name="items[0][unit_price]" id="unit_price_0" class="form-control" step="0.01" required>
                        </div>

                        <div class="form-group">
                            <label for="quantity_0">Quantity</label>
                            <input type="number" name="items[0][quantity]" id="quantity_0" class="form-control" value="1" min="1" required>
                        </div>

                        <div class="form-group">
                            <label for="remarks_0">Remarks</label>
                            <textarea name="items[0][remarks]" id="remarks_0" class="form-control"></textarea>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-primary" id="add-item-btn">Add Another Item</button>
                <button type="submit" class="btn btn-success">Add Items</button>
                <a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary">Back to Orders</a>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script>
        document.getElementById('add-item-btn').addEventListener('click', function() {
            const itemsContainer = document.getElementById('items-container');
            const itemCount = itemsContainer.getElementsByClassName('item-row').length;
            
            const newItemRow = document.createElement('div');
            newItemRow.classList.add('item-row');
            newItemRow.innerHTML = `
                <div class="form-group">
                    <label for="item_id_${itemCount}">Select Item</label>
                    <select name="items[${itemCount}][item_id]" id="item_id_${itemCount}" class="form-control" required>
                        <option value="" disabled selected>Select an Item</option>
                        @foreach ($inventoryItems as $item)
                            <option value="{{ $item->id }}">{{ $item->item_name_en }} ({{ $item->item_name_fa }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="trade_name_en_${itemCount}">Trade Name (English)</label>
                    <input type="text" name="items[${itemCount}][trade_name_en]" id="trade_name_en_${itemCount}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="trade_name_fa_${itemCount}">Trade Name (Persian)</label>
                    <input type="text" name="items[${itemCount}][trade_name_fa]" id="trade_name_fa_${itemCount}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="used_for_en_${itemCount}">Used For (English)</label>
                    <input type="text" name="items[${itemCount}][used_for_en]" id="used_for_en_${itemCount}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="used_for_fa_${itemCount}">Used For (Persian)</label>
                    <input type="text" name="items[${itemCount}][used_for_fa]" id="used_for_fa_${itemCount}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="size_${itemCount}">Size</label>
                    <input type="text" name="items[${itemCount}][size]" id="size_${itemCount}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="c_size_${itemCount}">Consumer Size</label>
                    <input type="text" name="items[${itemCount}][c_size]" id="c_size_${itemCount}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="unit_price_${itemCount}">Unit Price</label>
                    <input type="number" name="items[${itemCount}][unit_price]" id="unit_price_${itemCount}" class="form-control" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="quantity_${itemCount}">Quantity</label>
                    <input type="number" name="items[${itemCount}][quantity]" id="quantity_${itemCount}" class="form-control" value="1" min="1" required>
                </div>
                <div class="form-group">
                    <label for="remarks_${itemCount}">Remarks</label>
                    <textarea name="items[${itemCount}][remarks]" id="remarks_${itemCount}" class="form-control"></textarea>
                </div>
            `;
            itemsContainer.appendChild(newItemRow);
        });
    </script>
@stop
