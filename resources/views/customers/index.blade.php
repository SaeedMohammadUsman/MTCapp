@extends('adminlte::page')

@section('title', 'Customers')

@section('content_header')
    <h1>Customers</h1>
@stop

@section('content')
    {{-- Search and Filter Section --}}
    <div class="card">
        <div class="card-header">
            <form action="{{ route('customers.index') }}" method="GET" class="form-inline">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2"
                       placeholder="Search by Name (EN/FA)">
                
                {{-- Province Dropdown --}}
                <select name="province_id" class="form-control mr-2">
                    <option value="">All Provinces</option>
                    @foreach ($provinces as $province)
                        <option value="{{ $province->id }}" {{ request('province_id') == $province->id ? 'selected' : '' }}>
                            {{ $province->name}} ({{ $province->en_name }})
                        </option>
                    @endforeach
                </select>
                
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary ml-2">Clear</a>
                
                {{-- Filter for Active/Inactive --}}
                <select name="status" class="form-control ml-2" onchange="this.form.submit()">
                    <option value="">All Customers</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Trashed</option>
                </select>
            </form>
        </div>
    </div>
    
    {{-- Table Section --}}
    <div class="card">
        <div class="card-header">
            <a href="{{ route('customers.create') }}" class="btn btn-primary">Create</a>
        </div>
        <div class="card-body">
            <table class="table table-sm table-striped table-hover table-borderless py-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{ $customer->customer_name_en }}<br>
                                {{ $customer->customer_name_fa }}
                            </td>
                            <td>
                                {{ $customer->address }}

                            </td>
                            <td>{{ $customer->customer_phone }}</td>
                            <td>{{ $customer->email ?? 'N/A' }}</td>
                            <td>
                                <div class="btn-group">
                                    @if ($customer->deleted_at) <!-- Check if the customer is soft-deleted -->
                                    <form action="{{ route('customers.restore', $customer->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Restore</button>
                                    </form>
                                    @else
                                        <a href="{{ route('customers.show', $customer->id) }}"
                                           class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('customers.edit', $customer->id) }}"
                                           class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                              id="delete-form-{{ $customer->id }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    onclick="confirmDelete(event, 'delete-form-{{ $customer->id }}')"
                                                    class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    
        <div class="card-footer">
            {{-- Pagination Links --}}
            {{ $customers->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
    
@stop

@section('css')
    {{-- Add any extra stylesheets if needed --}}
@stop

@section('js')
    <script>
        console.log("Welcome to the customer management page!");
    </script>
@stop
