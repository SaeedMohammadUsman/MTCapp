@extends('adminlte::page')

@section('title', 'Customer Orders')

@section('content_header')
    {{-- <h1>Customer Orders</h1> --}}
    <h1>{{ __('menu.customer_orders') }}</h1>
    
@stop

@section('content')

    {{-- Search and Filter Section --}}
    <div class="card">
        <div class="card-header">
            <form action="{{ route('customer_orders.index') }}" method="GET" class="form-inline">
                {{-- Customer Dropdown Filter --}}
                <div class="input-group input-group-sm mr-2">
                    <select name="customer_id" class="form-control">
                        <option value="">Select Customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}"
                                {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->customer_name_en }} ({{ $customer->customer_name_fa }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status Dropdown Filter --}}
                <div class="input-group input-group-sm mr-2">
                    <select name="status" class="form-control">
                        <option value="">Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                        </option>
                    </select>
                </div>

                {{-- Filter Active/Trashed --}}
                <div class="input-group input-group-sm ml-2">
                    <select name="filter" class="form-control" onchange="this.form.submit()">
                        <option value="">All Records</option>
                        <option value="active" {{ request('filter') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="trashed" {{ request('filter') === 'trashed' ? 'selected' : '' }}>Trashed</option>
                    </select>
                </div>

                {{-- Search Button --}}
                <div class="input-group input-group-sm ml-2">
                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    <a href="{{ route('customer_orders.index') }}" class="btn btn-secondary btn-sm ml-2">Clear</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="card">
        <div class="card-header">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createCustomerOrderModal">
                Create
            </button>

        </div>
        <div class="card-body">
            <table class="table table-sm table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Customer</th>
                        <th>Remarks</th>
                        <th>Order Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customerOrders as $customerOrder)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $customerOrder->customer->customer_name_en }}
                                ({{ $customerOrder->customer->customer_name_fa }})
                            </td>

                            <td>{{ $customerOrder->remarks }}</td>
                            <td>{{ $customerOrder->order_date->format('Y-m-d') }}</td>

                            <td>{{ number_format($customerOrder->total_amount, 2) }}</td>

                            <td>
                                @if ($customerOrder->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif ($customerOrder->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    @if ($customerOrder->trashed())
                                        {{-- Restore button for soft-deleted orders --}}
                                        <form action="{{ route('customer_orders.restore', $customerOrder->id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Restore</button>
                                        </form>
                                    @else
                                        {{-- View, Edit, Delete buttons for active orders --}}
                                        <a href="{{ route('customer_orders.show', $customerOrder->id) }}"
                                            class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('customer_orders.edit', $customerOrder->id) }}"
                                            class="btn btn-warning btn-sm">Edit</a>

                                        <form action="{{ route('customer_orders.destroy', $customerOrder->id) }}"
                                            method="POST" id="delete-form-{{ $customerOrder->id }}"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="confirmDelete(event, 'delete-form-{{ $customerOrder->id }}')"
                                                class="btn btn-danger btn-sm">Delete</button>
                                        </form>

                                        @if ($customerOrder->status == 'completed')
                                            {{-- <form action="{{ route('customer_orders.stockOut', $customerOrder->id) }}" method="POST" style="display: inline-block;"> --}}
                                            <form id="stock-out-form-{{ $customerOrder->id }}"
                                                action="{{ route('customer_orders.stockOut', $customerOrder->id) }}"
                                                method="POST" >
                                                @csrf
                                                {{-- <button type="submit"
                                                class="btn btn-danger btn-sm border-2 border-light bg-gradient shadow-sm"
                                                title="Stock Out"
                                                onclick="confirmStockOut({{ $customerOrder->id }})">
                                                <i class="fa fa-arrow-up"></i> <!-- Stock Out -->
                                            </button> --}}

                                                <button type="button"
                                                    class="btn btn-danger btn-sm border-2 border-light bg-gradient shadow-sm"
                                                    title="Stock Out" onclick="confirmStockOut({{ $customerOrder->id }})">
                                                    <i class="fa fa-arrow-up"></i>
                                                </button>

                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No customer orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{-- Pagination Links --}}
            {{ $customerOrders->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>

    <!-- Modal for Creating Customer Order -->
    <div class="modal fade" id="createCustomerOrderModal" tabindex="-1" role="dialog"
        aria-labelledby="createCustomerOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCustomerOrderModalLabel">Create New Customer Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createCustomerOrderForm" action="{{ route('customer_orders.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">

                        <!-- Customer Dropdown -->
                        <div class="form-group">
                            <label for="customer_id">Customer</label>
                            <select class="form-control" id="customer_id" name="customer_id" required>
                                <option value="">Select a customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->customer_name_en }} ({{ $customer->customer_name_fa }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Package Dropdown (dynamically loaded based on customer selection) -->
                        <div class="form-group">
                            <label for="package_id">Package</label>
                            <select class="form-control" id="package_id" name="package_id" required>
                                <option value="">Select a package</option>
                            </select>
                        </div>

                        <!-- Remarks Field -->
                        <div class="form-group">
                            <label for="remarks">Remarks</label>
                            <textarea class="form-control" id="remarks" name="remarks" placeholder="Enter any remarks"></textarea>
                        </div>





                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        {{-- <button type="submit" class="btn btn-primary">Save</button> --}}
                        <button type="submit" class="btn btn-primary" id="saveCustomerOrderBtn">Save</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


@stop

@section('css')
    {{-- Add any extra stylesheets if needed --}}
@stop

@section('js')
    <script>
        $('#customer_id').on('change', function() {
            var customerId = $(this).val();
            if (customerId) {
                $.ajax({
                    url: '/customer-orders/customer/' + customerId + '/packages',
                    method: 'GET',
                    success: function(data) {
                        var packageSelect = $('#package_id');
                        packageSelect.empty();
                        packageSelect.append('<option value="">Select a package</option>');
                        $.each(data.packages, function(index, package) {
                            packageSelect.append('<option value="' + package.id + '">' + package
                                .title + '</option>');
                        });
                    }
                });
            }
        });

        // Handle form submission
        $('#createCustomerOrderForm').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');
            let data = form.serialize();

            $.post(url, data, function(response) {
                $('#createCustomerOrderModal').modal('hide'); // Hide modal
                Swal.fire({
                    title: 'Success!',
                    text: response.success,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload(); // Reload the page to show new customer order
                });
            }).fail(function(xhr) {
                let errors = xhr.responseJSON ? xhr.responseJSON.errors : null;
                let errorMessage = errors ? Object.values(errors).flat().join('\n') :
                    'An error occurred. Please try again.';

                Swal.fire({
                    title: 'Error!',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        });

        function confirmStockOut(orderId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action will stock out the order!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, stock out!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`stock-out-form-${orderId}`).submit();
                }
            });
        }
    </script>
@stop
