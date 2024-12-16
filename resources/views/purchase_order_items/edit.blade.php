
@extends('adminlte::page')

@section('title', 'Edit Purchase Order Item')

@section('content_header')
    <h1>Edit Purchase Order Item</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            {{-- <form action="{{ route('purchase_orders.items.update', ['purchase_order' => $purchaseOrderItem->purchase_order_id, 'item' => $purchaseOrderItem->id]) }}" method="POST"> --}}
                
                <form action="{{ route('purchase_orders.items.update', ['purchase_order' => $purchaseOrder->id, 'item' => $purchaseOrderItem->id]) }}" method="POST">

                @csrf
                @method('PUT')

                <!-- Inventory Item Selection -->
                <div class="form-group">
                    <label for="item_id">Inventory Item</label>
                    <select name="item_id" id="item_id" class="form-control" required>
                        <option value="" disabled>Select an item</option>
                        @foreach ($inventoryItems as $item)
                            <option value="{{ $item->id }}" {{ $item->id == $purchaseOrderItem->item_id ? 'selected' : '' }}>
                                {{ $item->item_name_en }} {{   $item->item_name_fa}}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Trade Name (EN & FA) -->
                <div class="form-group">
                    <label for="trade_name_en">Trade Name (EN)</label>
                    <input type="text" name="trade_name_en" id="trade_name_en" class="form-control" 
                        value="{{ $purchaseOrderItem->trade_name_en }}" required>
                </div>
                <div class="form-group">
                    <label for="trade_name_fa">Trade Name (FA)</label>
                    <input type="text" name="trade_name_fa" id="trade_name_fa" class="form-control" 
                        value="{{ $purchaseOrderItem->trade_name_fa }}" required>
                </div>

                <!-- Used For (EN & FA) -->
                <div class="form-group">
                    <label for="used_for_en">Used For (EN)</label>
                    <input type="text" name="used_for_en" id="used_for_en" class="form-control" 
                        value="{{ $purchaseOrderItem->used_for_en }}">
                </div>
                <div class="form-group">
                    <label for="used_for_fa">Used For (FA)</label>
                    <input type="text" name="used_for_fa" id="used_for_fa" class="form-control" 
                        value="{{ $purchaseOrderItem->used_for_fa }}">
                </div>

                <!-- Size and Container Size -->
                <div class="form-group">
                    <label for="size">Size</label>
                    <input type="text" name="size" id="size" class="form-control" 
                        value="{{ $purchaseOrderItem->size }}">
                </div>
                <div class="form-group">
                    <label for="c_size">Container Size</label>
                    <input type="text" name="c_size" id="c_size" class="form-control" 
                        value="{{ $purchaseOrderItem->c_size }}">
                </div>

                <!-- Unit Price and Quantity -->
                <div class="form-group">
                    <label for="unit_price">Unit Price</label>
                    <input type="number" step="0.01" name="unit_price" id="unit_price" class="form-control" 
                        value="{{ $purchaseOrderItem->unit_price }}" required>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" 
                        value="{{ $purchaseOrderItem->quantity }}" required>
                </div>

                <!-- Remarks -->
                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea name="remarks" id="remarks" class="form-control">{{ $purchaseOrderItem->remarks }}</textarea>
                </div>

                <!-- Action Buttons -->
                <button type="submit" class="btn btn-success">Update Item</button>
                <a href="{{ route('purchase_orders.show', $purchaseOrderItem->purchase_order_id) }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
