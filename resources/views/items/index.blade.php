@extends('adminlte::page')

@section('title', 'Items')

@section('content_header')
    <h1>Items</h1>
@stop

@section('content')
    {{-- Search and Filter Section --}}
    <div class="card">
        <div class="card-header">
            <form action="{{ route('items.index') }}" method="GET" class="form-inline">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2"
                    placeholder="Search by Name (EN/FA)">
                <select name="category_id" class="form-control mr-2">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name_en }} ({{ $category->name_fa }})
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('items.index') }}" class="btn btn-secondary ml-2">Clear</a>

                {{-- Filter for Active/Trashed --}}
                <select name="filter" class="form-control ml-2" onchange="this.form.submit()">
                    <option value="">All Items</option>
                    <option value="active" {{ request('filter') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="trashed" {{ request('filter') === 'trashed' ? 'selected' : '' }}>Trashed</option>
                </select>
            </form>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="card">
       

        {{-- Table Section --}}
        <div class="card">
            <div class="card-header">
                <a href="{{ route('items.create') }}" class="btn btn-primary">Create</a>
            </div>
            <div class="card-body">
                <table class="table table-sm table-striped table-hover table-borderless py-3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item Code</th>
                            <th>Name (EN)</th>
                            <th>Name (FA)</th>
                            <th>Used For (EN)</th>
                            <th>Used For (FA)</th>
                            <th>Description (EN)</th>
                            <th>Description (FA)</th>
                            <th>Size</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->item_code }}</td>
                                <td>{{ $item->trade_name_en }}</td>
                                <td>{{ $item->trade_name_fa }}</td>
                             
                                <td>{{ $item->used_for_en }}</td>
                                <td>{{ $item->used_for_fa }}</td>
                                <td>{{ $item->description_en }}</td>
                                <td>{{ $item->description_fa }}</td>
                                <td>{{ $item->size }}</td>
                                <td>{{ $item->category->name_en ?? 'N/A' }} ({{ $item->category->name_fa ?? 'N/A' }})</td>
                                <td>
                                    <div class="btn-group">
                                        @if ($item->trashed())
                                            {{-- Restore button for soft-deleted items --}}
                                            <form action="{{ route('items.restore', $item->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">Restore</button>
                                            </form>
                                        @else
                                            {{-- View, Edit, Delete buttons for active items --}}
                                            <a href="{{ route('items.show', $item->id) }}"
                                                class="btn btn-info btn-sm">View</a>
                                            <a href="{{ route('items.edit', $item->id) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('items.destroy', $item->id) }}" method="POST"
                                                id="delete-form-{{ $item->id }}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    onclick="confirmDelete(event, 'delete-form-{{ $item->id }}')"
                                                    class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center">No items found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                {{-- Pagination Links --}}
                {{ $items->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    @stop

    @section('css')
        {{-- Add any extra stylesheets if needed --}}
    @stop

    @section('js')
        <script>
            console.log("Welcome to the items management page!");
        </script>
    @stop
