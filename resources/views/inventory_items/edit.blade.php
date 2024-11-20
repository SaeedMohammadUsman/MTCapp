{{-- @extends('adminlte::page')

@section('title', 'Edit Inventory Item')

@section('content_header')
    <h1>Edit Inventory Item</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('inventory_items.update', $inventoryItem->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="item_name_en">Item Name (EN)</label>
                    <input type="text" class="form-control" id="item_name_en" name="item_name_en" value="{{ $inventoryItem->item_name_en }}" required>
                </div>
                <div class="form-group">
                    <label for="item_code">Item Code</label>
                    <input type="text" class="form-control" id="item_code" name="item_code" value="{{ $inventoryItem->item_code }}" required>
                </div>
                <div class="form-group">
                    <label for="cost_price">Cost Price</label>
                    <input type="number" class="form-control" id="cost_price" name="cost_price" value="{{ $inventoryItem->cost_price }}" required>
                </div>
                <div class="form-group">
                    <label for="selling_price">Selling Price</label>
                    <input type="number" class="form-control" id="selling_price" name="selling_price" value="{{ $inventoryItem->selling_price }}" required>
                </div>
                <div class="form-group">
                    <label for="quantity_in_stock">Quantity in Stock</label>
                    <input type="number" class="form-control" id="quantity_in_stock" name="quantity_in_stock" value="{{ $inventoryItem->quantity_in_stock }}" required>
                </div>
                <div class="form-group">
                    <label for="expiration_date">Expiration Date</label>
                    <input type="date" class="form-control" id="expiration_date" name="expiration_date" value="{{ $inventoryItem->expiration_date }}" required>
                </div>
                <button type="submit" class="btn btn-success">Update Item</button>
                <a href="{{ route('inventory_items.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@stop --}}




@extends('adminlte::page')

@section('title', 'Edit Inventory Item')

@section('content_header')
    <h1>Edit Inventory Item</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
            <form action="{{ route('inventory_items.update', $inventoryItem->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="item_name_en">Item Name (EN)</label>
                    <input type="text" class="form-control" id="item_name_en" name="item_name_en" value="{{ old('item_name_en', $inventoryItem->item_name_en) }}" required>
                    @error('item_name_en') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="item_name_fa">Item Name (FA)</label>
                    <input type="text" class="form-control" id="item_name_fa" name="item_name_fa" value="{{ old('item_name_fa', $inventoryItem->item_name_fa) }}" required>
                </div>
                
                <div class="form-group">
                    <label for="item_code">Item Code</label>
                    <input type="text" class="form-control" id="item_code" name="item_code" value="{{ old('item_code', $inventoryItem->item_code) }}" required>
                    @error('item_code') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="cost_price">Cost Price</label>
                    <input type="number" class="form-control" id="cost_price" name="cost_price" value="{{ old('cost_price', $inventoryItem->cost_price) }}" required>
                    @error('cost_price') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="selling_price">Selling Price</label>
                    <input type="number" class="form-control" id="selling_price" name="selling_price" value="{{ old('selling_price', $inventoryItem->selling_price) }}" required>
                    @error('selling_price') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="quantity_in_stock">Quantity in Stock</label>
                    <input type="number" class="form-control" id="quantity_in_stock" name="quantity_in_stock" value="{{ old('quantity_in_stock', $inventoryItem->quantity_in_stock) }}" required>
                    @error('quantity_in_stock') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="expiration_date">Expiration Date</label>
                    <input type="date" class="form-control" id="expiration_date" name="expiration_date" value="{{ old('expiration_date', $inventoryItem->expiration_date) }}" required>
                    @error('expiration_date') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <button type="submit" class="btn btn-success">Update Item</button>
                <a href="{{ route('inventory_items.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@stop
