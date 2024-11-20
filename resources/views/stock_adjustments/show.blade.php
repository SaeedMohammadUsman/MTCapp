@extends('adminlte::page')

@section('title', 'Stock Adjustment Details')

@section('content_header')
    <h1>Stock Adjustment Details</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Stock Adjustment for: {{ $stockAdjustment->inventoryItem->item_name_en }} ({{ $stockAdjustment->inventoryItem->item_name_fa }})</h3>
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> {{ $stockAdjustment->id }}</p>
            <p><strong>Item (EN):</strong> {{ $stockAdjustment->inventoryItem->item_name_en }}</p>
            <p><strong>Item (FA):</strong> {{ $stockAdjustment->inventoryItem->item_name_fa }}</p>
            <p><strong>Adjustment Type (EN):</strong> {{ $stockAdjustment->adjustment_type_en }}</p>
            <p><strong>Adjustment Type (FA):</strong> {{ $stockAdjustment->adjustment_type_fa }}</p>
            <p><strong>Quantity:</strong> {{ $stockAdjustment->quantity }}</p>
            <p><strong>Reason (EN):</strong> {{ $stockAdjustment->reason_en }}</p>
            <p><strong>Reason (FA):</strong> {{ $stockAdjustment->reason_fa }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('stock_adjustments.index') }}" class="btn btn-secondary">Back to List</a>
            <a href="{{ route('stock_adjustments.edit', $stockAdjustment->id) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('stock_adjustments.destroy', $stockAdjustment->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
@stop
