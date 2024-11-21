@extends('adminlte::page')

@section('title', 'Purchase Order Details')

@section('content_header')
    <h1>Purchase Order Details</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Purchase Order for Vendor: {{ $purchaseOrder->vendor->company_name_en }} ({{ $purchaseOrder->vendor->company_name_fa }})</h3>
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> {{ $purchaseOrder->id }}</p>
            <p><strong>Order Number:</strong> {{ $purchaseOrder->order_number }}</p>
            <p><strong>Vendor (EN):</strong> {{ $purchaseOrder->vendor->company_name_en }}</p>
            <p><strong>Vendor (FA):</strong> {{ $purchaseOrder->vendor->company_name_fa }}</p>
            <p><strong>Total Price:</strong> {{ number_format($purchaseOrder->total_price, 2) }}</p>
            <p><strong>Status (EN):</strong> {{ $purchaseOrder->status_en }}</p>
            <p><strong>Status (FA):</strong> {{ $purchaseOrder->status_fa }}</p>
            <p><strong>Remarks:</strong> {{ $purchaseOrder->remarks ?? 'No remarks' }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary">Back to List</a>
            <a href="{{ route('purchase_orders.edit', $purchaseOrder->id) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('purchase_orders.destroy', $purchaseOrder->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
@stop
