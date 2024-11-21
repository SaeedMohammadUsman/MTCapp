@extends('adminlte::page')

@section('title', 'Inventory Items')

@section('content_header')
    <h1>Inventory Items</h1>
@stop

@section('content')
    {{-- Search Section --}}
    <div class="card">
        <div class="card-header">
            <form action="{{ route('inventory_items.index') }}" method="GET" class="form-inline">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2"
                    placeholder="Search by Item Name, Item Code, Price or Stock">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="{{ route('inventory_items.index') }}" class="btn btn-secondary ml-2">Clear</a>
            </form>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="card">
        <div class="card-header">
            <a href="{{ route('inventory_items.create') }}" class="btn btn-primary">Create New Inventory Item</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover table-striped table-sm">
                <thead>
                    <tr>
                        <th>Item Name (EN)</th>
                        <th>Item Code</th>
                        <th>Cost Price</th>
                        <th>Selling Price</th>
                        <th>Quantity in Stock</th>
                        <th>Expiration Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($inventoryItems as $item)
                        <tr>
                            <td>{{ $item->item_name_en }}</td>
                            <td>{{ $item->item_code }}</td>
                            <td>{{ $item->cost_price }}</td>
                            <td>{{ $item->selling_price }}</td>
                            <td>{{ $item->quantity_in_stock }}</td>
                            <td>{{ $item->expiration_date }}</td>
                            <td>
                                <div class="btn-group">
                                <a href="{{ route('inventory_items.show', $item->id) }}"
                                    class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('inventory_items.edit', $item->id) }}"
                                    class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('inventory_items.destroy', $item->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No inventory items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{-- Pagination Links --}}
            {{ $inventoryItems->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@stop

@section('css')
    {{-- Add any extra stylesheets if needed --}}
@stop

@section('js')
    <script>
        console.log("Welcome to the inventory items management page!");
    </script>
@stop
