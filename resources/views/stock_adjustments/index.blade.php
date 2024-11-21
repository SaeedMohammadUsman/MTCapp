@extends('adminlte::page')

@section('title', 'Stock Adjustments')

@section('content_header')
    <h1>Stock Adjustments</h1>
@stop

@section('content')
    {{-- Search Section --}}
    <div class="card">
        <div class="card-header">
            <form action="{{ route('stock_adjustments.index') }}" method="GET" class="form-inline">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2"
                    placeholder="Search by Adjustment Type or Reason">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="{{ route('stock_adjustments.index') }}" class="btn btn-secondary ml-2">Clear</a>
            </form>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="card">
        <div class="card-header">
            <a href="{{ route('stock_adjustments.create') }}" class="btn btn-primary">Create New Adjustment</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover table-striped table-sm">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Adjustment Type (EN)</th>
                        <th>Adjustment Type (FA)</th>
                        <th>Quantity</th>
                        <th>Reason</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stockAdjustments as $adjustment)
                        <tr>
                            <td>{{ $adjustment->inventoryItem ? $adjustment->inventoryItem->item_name_en : 'No Item Found' }}</td>
                            <td>{{ $adjustment->adjustment_type_en }}</td>
                            <td>{{ $adjustment->adjustment_type_fa }}</td>
                            <td>{{ $adjustment->quantity }}</td>
                            <td>{{ $adjustment->reason_en }}</td>
                            <td>
                                <div class="btn-group">
                                <a href="{{ route('stock_adjustments.show', $adjustment->id) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('stock_adjustments.edit', $adjustment->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('stock_adjustments.destroy', $adjustment->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No stock adjustments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $stockAdjustments->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
    <script>
        console.log("Stock Adjustments page loaded.");
    </script>
@stop
