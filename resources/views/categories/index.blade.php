
@extends('adminlte::page')


@section('title', 'Categories')

@section('content_header')

    {{-- <h1>Categories</h1> --}}
    <h1>{{ __('menu.categories') }}</h1>
    
    
@stop

@section('content')
    {{-- Search Section --}}
    <div class="card">
        <div class="card-header">
            <form action="{{ route('categories.index') }}" method="GET" class="form-inline">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2" placeholder="Search by Name (EN/FA)">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary ml-2">Clear</a>

                {{-- Filter Section for Categories (Active / Trashed) --}}
<select name="filter" class="form-control ml-2" onchange="this.form.submit()">
    <option value="">All Categories</option>
    <option value="active" {{ request()->input('filter') === 'active' ? 'selected' : '' }}>Active</option>
    <option value="trashed" {{ request()->input('filter') === 'trashed' ? 'selected' : '' }}>Trashed</option>
</select>



            </form>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="card">
        <div class="card-header">
            <a href="{{ route('categories.create') }}" class="btn btn-primary">Create New Category</a>
        </div>
        <div class="card-body">
            <table class="table table-sm table-striped table-hover table-borderless">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name (EN)</th>
                        <th>Name (FA)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name_en }}</td>
                            <td>{{ $category->name_fa }}</td>
                            <td>
                                <div class="btn-group">
                                    @if ($category->trashed())
                                        {{-- Only the Restore button for soft-deleted categories --}}
                                        <form action="{{ route('categories.restore', $category->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Restore</button>
                                        </form>
                                    @else
                                        {{-- View, Edit, Delete buttons for active categories --}}
                                        <a href="{{ route('categories.show', $category->id) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        
                                        {{-- Delete button with confirmation --}}
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" id="delete-form-{{ $category->id }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(event, 'delete-form-{{ $category->id }}')" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                            
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{-- Pagination Links --}}
            {{ $categories->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@stop

@section('css')
    {{-- Add any extra stylesheets if needed --}}
@stop

@section('js')
    <script>
        console.log("Welcome to the categories management page!");
    </script>
@stop
