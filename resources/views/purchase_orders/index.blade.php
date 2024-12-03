@extends('adminlte::page')

@section('title', 'Purchase Orders')

@section('content_header')
    <h1>Purchase Orders</h1>
@stop

@section('content')
    {{-- Search Section --}}
    <div class="card">
        <div class="card-header">
            <form action="{{ route('purchase_orders.index') }}" method="GET" class="form-inline">
                <select name="vendor_id" class="form-control mr-2">
                    <option value="">Select Vendor</option>
                    @foreach ($vendors as $vendor)
                        <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                            {{ $vendor->company_name_en }} ({{ $vendor->company_name_fa }})
                        </option>
                    @endforeach
                </select>
                <select name="status" class="form-control mr-2">
                    <option value="">Select Status</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary ml-2">Clear</a>
            </form>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="card">
        <div class="card-header">
            <a href="{{ route('purchase_orders.create') }}" class="btn btn-primary">Create New Order</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover table-striped table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Order Number</th>
                        <th>Vendor</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($purchaseOrders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->order_number }}</td>
                            {{-- <td>{{ $order->vendor->company_name_en }}</td> --}}
                            <td>{{ $order->vendor?->company_name_en ?? 'N/A' }}</td>
                            <td>{{ number_format($order->total_price, 2) }}</td>
                            <td>{{ $order->status_en }}</td>
                            <td>
                                <div class="btn-group">
                                <a href="{{ route('purchase_orders.show', $order->id) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('purchase_orders.edit', $order->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('purchase_orders.destroy', $order->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No purchase orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $purchaseOrders->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@stop
