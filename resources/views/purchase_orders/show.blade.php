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
                            <th>Item Code</th>
                            <th>Trade Name (EN)</th>
                            <th>Trade Name (FA)</th>
                            <th>Used For (EN)</th>
                            <th>Used For (FA)</th>
                            <th>Size</th>
                            <th>Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchaseOrder->items as $item)
                            <tr>
                                <td>{{ $item->item->item_code }}</td>
                                <td>{{ $item->item->trade_name_en }}</td>
                                <td>{{ $item->item->trade_name_fa }}</td>
                                <td>{{ $item->item->used_for_en }}</td>
                                <td>{{ $item->item->used_for_fa }}</td>
                                <td>{{ $item->item->size }}</td>
                                <td>{{ $item->quantity }}</td>

                                <td>
                                    <div class="btn-group text-center">
                                        <!-- Delete Action with Icon -->
                                        <form id="delete-form-{{ $item->id }}"
                                            action="{{ route('purchase_orders.items.destroy', [$purchaseOrder->id, $item->id]) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn btn-danger btn-sm {{ $purchaseOrder->status_en === 'Completed' ? 'disabled' : '' }}"
                                                @if ($purchaseOrder->status_en === 'Completed') onclick="event.preventDefault();" 
                                                style="pointer-events: none; opacity: 0.6;" 
                                            @else
                                                onclick="confirmDelete(event, 'delete-form-{{ $item->id }}')" @endif>
                                                <i class="fas fa-trash"></i> <!-- Trash Icon -->
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
            {{-- <a href="{{ route('purchase_orders.items.create', $purchaseOrder->id) }}" class="btn btn-primary mt-3" 
                @if ($purchaseOrder->status_en === 'Completed') disabled @endif>Add New
                Item</a>
                 --}}
            <a href="{{ $purchaseOrder->status_en === 'Completed' ? '#' : route('purchase_orders.items.create', $purchaseOrder->id) }}"
                class="btn btn-primary mt-3 {{ $purchaseOrder->status_en === 'Completed' ? 'disabled' : '' }}"
                @if ($purchaseOrder->status_en === 'Completed') onclick="event.preventDefault();" 
                        style="pointer-events: none; opacity: 0.6;" @endif>
                Add New Item
            </a>


            <a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary mt-3">Back to List</a>
        </div>
    </div>
@stop
