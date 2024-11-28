@extends('adminlte::page')

@section('title', 'Vendors')

@section('content_header')
    <h1>Vendors</h1>
@stop

@section('content')
    {{-- Search Section --}}
    <div class="card">
        <div class="card-header">
            <form action="{{ route('vendors.index') }}" method="GET" class="form-inline">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2"
                    placeholder="Search by ID, Name, or Country">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="{{ route('vendors.index') }}" class="btn btn-secondary ml-2">Clear</a>
            </form>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="card">
        <div class="card-header">
            <a href="{{ route('vendors.create') }}" class="btn btn-primary">Create New Vendor</a>
        </div>
        <div class="card-body">
            <table class="table table-hover table-striped table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Company Name (EN)</th>
                        <th>Company Name (FA)</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Country</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vendors as $vendor)
                        <tr>
                            <td>{{ $vendor->id }}</td>
                            <td>{{ $vendor->company_name_en }}</td>
                            <td>{{ $vendor->company_name_fa }}</td>
                            <td>{{ $vendor->email }}</td>
                            <td>{{ $vendor->phone_number }}</td>
                            <td>{{ $vendor->country_name }}</td>
                            <td>
                                <div class="btn-group">
                                <a href="{{ route('vendors.show', $vendor->id) }}"
                                    class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('vendors.edit', $vendor->id) }}"
                                    class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('vendors.destroy', $vendor->id) }}" method="POST"
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
                            <td colspan="7" class="text-center">No vendors found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{-- Pagination Links --}}
            {{ $vendors->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@stop

@section('css')
    {{-- Add any extra stylesheets if needed --}}
@stop

@section('js')
    <script>
        console.log("Welcome to the vendors management page!");
    </script>
@stop
