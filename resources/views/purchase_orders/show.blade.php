@extends('adminlte::page')

@section('title', 'Purchase Order Details')

@section('content_header')
    <h1>Purchase Order #{{ $purchaseOrder->order_number }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <!-- Purchase Order Details -->
            <p><strong>Order Number:</strong> {{ $purchaseOrder->order_number }}</p>
            <p><strong>Vendor (EN):</strong> {{ $purchaseOrder->vendor->company_name_en }}</p>
            <p><strong>Vendor (FA):</strong> {{ $purchaseOrder->vendor->company_name_fa }}</p>
            <p><strong>Total Price:</strong> {{ number_format($purchaseOrder->total_price, 2) }}</p>
            <p><strong>Status (EN):</strong> {{ $purchaseOrder->status_en }}</p>
            <p><strong>Status (FA):</strong> {{ $purchaseOrder->status_fa }}</p>
            <p><strong>Remarks:</strong> {{ $purchaseOrder->remarks ?? 'No remarks' }}</p>

            <!-- Purchase Order Items -->
            <h3>Purchase Order Items</h3>
            @if ($purchaseOrder->items->isEmpty())
                <p>No items added to this purchase order yet.</p>
            @else
                <table class="table table-bordered table-hover table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Trade Name (EN)</th>
                            <th>Trade Name (Fa)</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchaseOrder->items as $item)
                            <tr>
                                <td>{{ $item->inventoryItem->item_name_en }} {{ $item->inventoryItem->item_name_fa }}</td>
                                {{-- <td>{{  }}</td> --}}

                                <td>{{ $item->trade_name_en }}</td>
                                <td>{{ $item->trade_name_fa }}</td>
                                <td>{{ number_format($item->unit_price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                                <td>
                                    <div class="btn-group">
                                        {{-- <a href="{{ route('purchase_orders.items.show', [$purchaseOrder->id, $item->id]) }}"
                                            class="btn btn-info btn-sm">View</a> --}}
                                        
                                      

                                        
                                        <a href="{{ route('purchase_orders.items.edit', [$purchaseOrder->id, $item->id]) }}"
                                            class="btn btn-warning btn-sm">Edit</a>
                                        <form
                                            action="{{ route('purchase_orders.items.destroy', [$purchaseOrder->id, $item->id]) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                            >
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <!-- Action Buttons -->
            <a href="{{ route('purchase_orders.items.create', $purchaseOrder->id) }}" class="btn btn-primary mt-3">Add New
                Item</a>
            <a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary mt-3">Back to List</a>
        </div>
    </div>
@stop
