@extends('adminlte::page')

@section('title', 'Inventory Item Details')

@section('content_header')
    <h1>Inventory Item Details</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <p><strong>Item Name (EN):</strong> {{ $inventoryItem->item_name_en }}</p>
            <p><strong>Item Code:</strong> {{ $inventoryItem->item_code }}</p>
            <p><strong>Cost Price:</strong> {{ $inventoryItem->cost_price }}</p>
            <p><strong>Selling Price:</strong> {{ $inventoryItem->selling_price }}</p>
            <p><strong>Quantity in Stock:</strong> {{ $inventoryItem->quantity_in_stock }}</p>
            <p><strong>Expiration Date:</strong> {{ $inventoryItem->expiration_date }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('inventory_items.index') }}" class="btn btn-secondary">Back to List</a>
            <a href="{{ route('inventory_items.edit', $inventoryItem->id) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('inventory_items.destroy', $inventoryItem->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
@stop
