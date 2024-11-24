@extends('adminlte::page')

@section('title', 'Purchase Order Item Details')

@section('content_header')
    <h1>Purchase Order Item Details</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Item Details for: {{ $purchaseOrderItem->trade_name_en }} ({{ $purchaseOrderItem->trade_name_fa }})</h3>
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> {{ $purchaseOrderItem->id }}</p>
            <p><strong>Item (EN):</strong> {{ $purchaseOrderItem->trade_name_en }}</p>
            <p><strong>Item (FA):</strong> {{ $purchaseOrderItem->trade_name_fa }}</p>
            <p><strong>Used For (EN):</strong> {{ $purchaseOrderItem->used_for_en ?? 'N/A' }}</p>
            <p><strong>Used For (FA):</strong> {{ $purchaseOrderItem->used_for_fa ?? 'N/A' }}</p>
            <p><strong>Size:</strong> {{ $purchaseOrderItem->size ?? 'N/A' }}</p>
            <p><strong>Container Size:</strong> {{ $purchaseOrderItem->c_size ?? 'N/A' }}</p>
            <p><strong>Unit Price:</strong> {{ number_format($purchaseOrderItem->unit_price, 2) }}</p>
            <p><strong>Quantity:</strong> {{ $purchaseOrderItem->quantity }}</p>
            <p><strong>Total Price:</strong> {{ number_format($purchaseOrderItem->total_price, 2) }}</p>
            <p><strong>Remarks:</strong> {{ $purchaseOrderItem->remarks ?? 'No remarks' }}</p>
        </div>
        <div class="card-footer">
            <!-- Back to Purchase Order Button -->
            <a href="{{ route('purchase_orders.show', $purchaseOrderItem->purchase_order_id) }}" class="btn btn-secondary">Back to Order</a>
            
            <!-- Edit Purchase Order Item Button -->
            <a href="{{ route('purchase_orders.items.edit', ['purchase_order' => $purchaseOrderItem->purchase_order_id, 'item' => $purchaseOrderItem->id]) }}" class="btn btn-warning">Edit</a>
            
            <!-- Delete Purchase Order Item Form -->
            <form action="{{ route('purchase_orders.items.destroy', ['purchase_order' => $purchaseOrderItem->purchase_order_id, 'purchase_order_item' => $purchaseOrderItem->id]) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
@stop
