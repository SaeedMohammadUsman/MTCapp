@extends('adminlte::page')

@section('title', 'Received Goods')

@section('content_header')
    <h1>Received Goods</h1>
@stop

@section('content')
    {{-- Search and Filter Section --}}
    <div class="card">
        <div class="card-header">
            <form action="{{ route('received_goods.index') }}" method="GET" class="form-inline">
                <div class="input-group input-group-sm mr-2">
                    <input type="text" name="batch_number" value="{{ request('batch_number') }}" class="form-control"
                        placeholder="Batch Number">
                </div>

                <div class="input-group input-group-sm mr-2">
                    <select name="vendor_id" class="form-control">
                        <option value="">All Vendors</option>
                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                {{ $vendor->company_name_en }} ({{ $vendor->company_name_fa }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="input-group input-group-sm mr-2">
                    <select name="is_finalized" class="form-control">
                        <option value="">Status</option>
                        <option value="1" {{ request('is_finalized') == '1' ? 'selected' : '' }}>Completed</option>
                        <option value="0" {{ request('is_finalized') == '0' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>

                <div class="input-group input-group-sm mr-2">
                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    <a href="{{ route('received_goods.index') }}" class="btn btn-secondary btn-sm ml-2">Clear</a>
                </div>

                {{-- Filter for Active/Trashed --}}
                <div class="input-group input-group-sm ml-2">
                    <select name="filter" class="form-control" onchange="this.form.submit()">
                        <option value="">All Records</option>
                        <option value="active" {{ request('filter') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="trashed" {{ request('filter') === 'trashed' ? 'selected' : '' }}>Trashed</option>
                    </select>
                </div>
            </form>
        </div>

    </div>

    {{-- Table Section --}}
    <div class="card">
        <div class="card-header">
            <a href="{{ route('received_goods.create') }}" class="btn btn-primary">Add</a>
        </div>
        <div class="card-body">
            <table class="table table-sm table-striped table-hover  ">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Batch Number</th>
                        <th>Vendor</th>
                        <th>Remark</th>
                        <th>Received Date</th>
                        <th>Bill Attachment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($receivedGoods as $receivedGood)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $receivedGood->batch_number }}</td>
                            <td>{{ $receivedGood->vendor->company_name_en }}
                                ({{ $receivedGood->vendor->company_name_fa }})
                            </td>
                            <td>{{ $receivedGood->remark }}</td>
                            <td>{{ $receivedGood->date->format('Y-m-d') }}</td>

                            <td>
                                @if ($receivedGood->bill_attachment)
                                    {{-- <a href="{{ Storage::url($receivedGood->bill_attachment) }}" target="_blank">
                                        {{ basename($receivedGood->bill_attachment) }}
                                    </a> --}}
                                    <a href="{{ asset('storage/' . $receivedGood->bill_attachment) }}" target="_blank">
                                        {{ basename($receivedGood->bill_attachment) }}
                                    </a>
                                @else
                                    No Attachment Available
                                @endif
                            </td>
                            <td>
                                @if ($receivedGood->is_finalized)
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>

                            <td>
                                <div class="btn-group">
                                    @if ($receivedGood->trashed())
                                        {{-- Restore button for soft-deleted items --}}
                                        <form action="{{ route('received_goods.restore', $receivedGood->id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Restore</button>
                                        </form>
                                    @else
                                        {{-- View, Edit, Delete buttons for active items --}}
                                        <a href="{{ route('received_goods.show', $receivedGood->id) }}"
                                            class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('received_goods.edit', $receivedGood->id) }}"
                                            class="btn btn-warning btn-sm">Edit</a>

                                        <form action="{{ route('received_goods.destroy', $receivedGood->id) }}"
                                            method="POST" id="delete-form-{{ $receivedGood->id }}"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="confirmDelete(event, 'delete-form-{{ $receivedGood->id }}')"
                                                class="btn btn-danger btn-sm">Delete</button>
                                        </form>

                                        @if ($receivedGood->is_finalized)
                                            <button type="button" class="btn btn-success btn-sm" title="Stock In"
                                                data-id="{{ $receivedGood->id }}" data-toggle="modal"
                                                data-target="#stockInModal">
                                                <i class="fas fa-arrow-down"></i>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No received goods found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{-- Pagination Links --}}
            {{ $receivedGoods->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>

    <!-- Stock In Modal -->
    <div class="modal fade" id="stockInModal" tabindex="-1" role="dialog" aria-labelledby="stockInModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockInModalLabel">Stock In - Add Arrival Prices</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {{-- <form action="{{ route('received_goods.stock_in', ['received_good' => $receivedGood->id]) }}" method="POST"> --}}

                <form action="{{ route('received_goods.stock_in', ['received_good' => $receivedGood->id]) }}"
                    method="POST" id="stockInForm">
                    @csrf
                    <input type="hidden" id="receivedGoodId" value="{{ $receivedGood->id }}">
                    <div class="modal-body">
                        <div id="modal-items-container">
                            <!-- Table to display items dynamically -->
                            <table class="table table-sm table-striped table-hover  ">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item Name</th>
                                        <th>Vendor Price</th>
                                        <th>Arrival Price</th>
                                    </tr>
                                </thead>
                                <tbody id="items-table-body">
                                    <!-- Dynamic rows will be added here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
$('#stockInModal').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget); // Button that triggered the modal
    const receivedGoodId = button.data('id'); // Get the received good ID
    $('#receivedGoodId').val(receivedGoodId);
    const modal = $(this);

    // Fetch the received good details using AJAX
    $.ajax({
        url: `/received-goods/${receivedGoodId}/details`,
        method: 'GET',
        success: function (response) {
            const tableBody = modal.find('#items-table-body');
            tableBody.empty(); // Clear any previous rows

            // Populate the table with details
            response.details.forEach((detail, index) => {
                tableBody.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${detail.item.trade_name_en} ${detail.item.trade_name_fa}</td>
                        <td>${detail.vendor_price}</td>
                        <td>
                            <input type="number" name="arrival_prices[${detail.id}]" 
                                   id="arrival_price_${detail.id}" 
                                   class="form-control" 
                                   placeholder="Enter arrival price" 
                                   required>
                        </td>
                    </tr>
                `);
            });
        },
        error: function () {
            Swal.fire({
                title: 'Error!',
                text: 'Failed to load received good details.',
                icon: 'error',
                confirmButtonText: 'Okay',
                timer: 3000,
                toast: true,
                position: 'top-end'
            });
        },
    });
});


$('#stockInForm').on('submit', function (e) {
    e.preventDefault();

    const form = document.getElementById('stockInForm');
    const formData = new FormData(form);

    const receivedGoodId = $('#receivedGoodId').val(); // Ensure this is populated correctly

    $.ajax({
        url: `/received-goods/${receivedGoodId}/stock-in`,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            // Check if the response indicates the batch is already stocked in
            if (response.message && response.message.includes('already been stocked in')) {
                Swal.fire({
                    title: 'Info!',
                    text: response.message,
                    icon: 'info',
                    confirmButtonText: 'Okay',
                    timer: 3000,
                    toast: true,
                    position: 'top-end'
                });
            } else {
                Swal.fire({
                    title: 'Success!',
                    text: response.message || 'Stock in completed successfully.',
                    icon: 'success',
                    confirmButtonText: 'Okay',
                    timer: 3000,
                    toast: true,
                    position: 'top-end'
                });
                $('#stockInModal').modal('hide');
                location.reload();
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            Swal.fire({
                title: 'Error!',
                text: xhr.responseJSON?.message || 'Failed to submit stock-in.',
                icon: 'error',
                confirmButtonText: 'Okay',
                timer: 3000,
                toast: true,
                position: 'top-end'
            });
        },
    });
});


</script>
@stop
