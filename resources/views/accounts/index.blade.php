@extends('adminlte::page')

@section('title', 'Manage Accounts')

@section('content_header')
    <h1>Manage Accounts</h1>
@stop

@section('content')
    {{-- Filter Section --}}
    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('accounts.index') }}" class="form-inline">
                <div class="input-group input-group-sm mr-2">
                    <input type="text" name="search" class="form-control" placeholder="Search by name"
                        value="{{ request('search') }}">
                </div>

                <div class="input-group input-group-sm mr-2">
                    <select name="type" class="form-control">
                        <option value="">All Types</option>
                        <option value="sarrafi" {{ request('type') == 'sarrafi' ? 'selected' : '' }}>Sarrafi</option>
                        <option value="cash" {{ request('type') == 'cash' ? 'selected' : '' }}>Cash</option>
                    </select>
                </div>

                <div class="input-group input-group-sm mr-2">
                    <select name="status" class="form-control">
                        <option value="">All Accounts</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="input-group input-group-sm mr-2">
                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    <a href="{{ route('accounts.index') }}" class="btn btn-secondary btn-sm ml-2">Clear</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Add Account Button --}}
    <div class="card mt-3">
        <div class="card-body">
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#accountModal" id="createAccountBtn">
                Add Account
            </button>
        </div>
    </div>

    {{-- Accounts Table --}}
    <table class="table table-sm table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Type</th>
                <th>Balance</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($accounts as $account)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $account->name }}</td>
                    <td>{{ ucfirst($account->type) }}</td>
                    <td>{{ $account->balance }}</td>
                    <td>
                        @if ($account->trashed())
                            <button class="btn btn-success btn-sm restoreAccount" data-id="{{ $account->id }}">Restore</button>
                        @else
                            {{-- Edit and Delete buttons for active accounts --}}
                            <button class="btn btn-sm btn-info editAccount Btn" data-id="{{ $account->id }}">Edit</button>
                            <button class="btn btn-sm btn-danger deleteAccount Btn" data-id="{{ $account->id }}">Delete</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Pagination --}}
    {{ $accounts->links() }}

    {{-- Account Modal --}}
    <div class="modal fade" id="accountModal" tabindex="-1" aria-labelledby="accountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="accountForm">
                @csrf
                <input type="hidden" id="accountId" name="account_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="accountModalLabel">Add/Edit Account</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="sarrafi">Sarrafi</option>
                                <option value="cash">Cash</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="balance">Balance</label>
                            <input type="number" class="form-control" id="balance" name="balance" min="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // CSRF Token for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Create or Edit Account
        $('#accountForm').on('submit', function (e) {
            e.preventDefault();
            const url = $('#accountId').val() ? `/accounts/${$('#accountId').val()}` : `/accounts`;
            const method = $('#accountId').val() ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: $(this).serialize(),
                success: function (response) {
                    $('#accountModal').modal('hide');
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = Object.values(errors).flat().join('\n');

                    Swal.fire({
                        title: 'Error!',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                },
            });
        });

        // Edit Account
        $('.editAccount').click(function () {
            const id = $(this).data('id');
            $.get(`/accounts/${id}/edit`, function (account) {
                $('#accountId').val(account.id);
                $('#name').val(account.name);
                $('#type').val(account.type);
                $('#balance').val(account.balance);
                $('#accountModal').modal('show');
            });
        });

        // Delete Account
        $('.deleteAccount').click(function () {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/accounts/${id}`,
                        method: 'DELETE',
                        success: function (response) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function (xhr) {
                            Swal.fire({
                                title: 'Error!',
                                text: xhr.responseJSON.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        },
                    });
                }
            });
        });

        // Restore Account
        $('.restoreAccount').click(function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            
            Swal.fire({
                title: 'Restore Account',
                text: "Are you sure you want to restore this account?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, restore it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/accounts/${id}/restore`,
                        type: 'POST',
                        success: function(response) {
                            Swal.fire(
                                'Restored!',
                                response.success,
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'Something went wrong!',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>
@stop