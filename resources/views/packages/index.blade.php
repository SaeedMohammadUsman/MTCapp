@extends('adminlte::page')

@section('title', 'Price Packages')

@section('content_header')
    <h1>Price Packages</h1>
@stop
@section('content')
    {{-- Search and Filter Section --}}
    <div class="card">
        <div class="card-header">
            <form action="{{ route('packages.index') }}" method="GET" class="form-inline">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2"
                    placeholder="Search by Title">

                {{-- Customer Filter Dropdown --}}
                <select name="customer_id" class="form-control ml-2" onchange="this.form.submit()">
                    <option value="">All Customers</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->customer_name_en }}  {{ $customer->customer_name_fa }}
                        </option>
                    @endforeach
                </select>

                {{-- Filter for Active/Inactive --}}
                <select name="status" class="form-control ml-2" onchange="this.form.submit()">
                    <option value="">All Packages</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Trashed</option>
                </select>
                <button type="submit" class="btn btn-primary ml-2">Filter</button>
                <a href="{{ route('packages.index') }}" class="btn btn-secondary ml-2">Clear</a>
            </form>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="card">
        <div class="card-header">
            {{-- <a href="{{ route('packages.create') }}" class="btn btn-primary">Create</a> --}}

            <button class="btn btn-primary" data-toggle="modal" data-target="#createPackageModal">Create</button>

        </div>
        <div class="card-body">
            <table class="table table-sm table-striped table-hover table-borderless py-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Customer</th>
                        <th>Title</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($packages as $package)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $package->customer->customer_name_en }} {{ $package->customer->customer_name_fa }}</td>

                            
                            <td>{{ $package->title }}</td>
                            <td>
                                <div class="btn-group">
                                    @if ($package->deleted_at)
                                        <!-- Check if the package is soft-deleted -->
                                        <form action="{{ route('packages.restore', $package->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Restore</button>
                                        </form>
                                    @else
                                        <a href="{{ route('packages.show', $package->id) }}"
                                            class="btn btn-info btn-sm">View</a>
                                        <form action="{{ route('packages.destroy', $package->id) }}" method="POST"
                                            id="delete-form-{{ $package->id }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="confirmDelete(event, 'delete-form-{{ $package->id }}')"
                                                class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No packages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{-- Pagination Links --}}
            {{ $packages->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>


    <!-- Modal for Creating Package -->
    <div class="modal fade" id="createPackageModal" tabindex="-1" role="dialog" aria-labelledby="createPackageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createPackageModalLabel">Create New Package</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createPackageForm" action="{{ route('packages.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                    
                    <div class="form-group">
                        <label for="customer_id">Customer</label>
                        <select class="form-control" id="customer_id" name="customer_id" required>
                            <option value="">Select a customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}"> {{ $customer->customer_name_en }} {{ $customer->customer_name_fa }} </option>
                            @endforeach
                        </select>
                    </div>
                    
                        <div class="form-group">
                            <label for="packageTitle">Title</label>
                            <input type="text" class="form-control" id="packageTitle" name="title"
                                placeholder="Enter package title" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>




    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Handle form submission
            $('#createPackageForm').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);
                let url = form.attr('action');
                let data = form.serialize();

                $.post(url, data, function(response) {
                    $('#createPackageModal').modal('hide'); // Hide modal
                    Swal.fire({
                        title: 'Success!',
                        text: response.success,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload(); // Reload the page to show new package
                    });
                }).fail(function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = Object.values(errors).flat().join('\n');

                    Swal.fire({
                        title: 'Error!',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            });
        </script>
    @stop


@stop
