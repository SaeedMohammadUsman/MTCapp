@extends('adminlte::page')

@section('title', 'Manage Transactions')

@section('content_header')
    {{-- <h1>Manage Transactions</h1> --}}
    <h1>{{ __('menu.transactions') }}</h1>

@stop

@section('content')
    {{-- Filter Section --}}
    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('transactions.index') }}" class="form-inline">
                <div class="input-group input-group-sm mr-2">
                    <input type="text" name="search" class="form-control" placeholder="Search by description"
                        value="{{ request('search') }}">
                </div>

                <div class="input-group input-group-sm mr-2">
                    <select name="transaction_type" class="form-control">
                        <option value="">All Types</option>
                        <option value="income" {{ request('transaction_type') == 'income' ? 'selected' : '' }}>Income
                        </option>
                        <option value="expense" {{ request('transaction_type') == 'expense' ? 'selected' : '' }}>Expense
                        </option>
                        <option value="transfer" {{ request('transaction_type') == 'transfer' ? 'selected' : '' }}>Transfer
                        </option>
                    </select>
                </div>

                <div class="input-group input-group-sm mr-2">
                    <select name="source" class="form-control">
                        <option value="">All Sources</option>
                        @foreach (['payment_to_vendor', 'salary_payment', 'daily_expense', 'payment_to_distributors', 'customer_payment_received', 'advance_payment_for_purchasing', 'transfer_to_sarrafi', 'transfer_to_cash', 'miscellaneous_income', 'other_expense'] as $source)
                            <option value="{{ $source }}" {{ request('source') == $source ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $source)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="input-group input-group-sm mr-2">
                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary btn-sm ml-2">Clear</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Add Transaction Button --}}
    <div class="card mt-3">
        <div class="card-body">
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#transactionModal"
                id="createTransactionBtn">
                Add Transaction
            </button>
        </div>
    </div>

    {{-- Transactions Table --}}
    <div class="table-responsive">
    <table class="table table-sm table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Account</th>
                <th>Customer</th>
                <th>Vendor</th>
                <th>Amount</th>
                <th>Type</th>
                <th>Source</th>
                <th>Description</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $transaction->account->name }}</td>
                    <td>
                        {{ $transaction->customer->customer_name_en ?? 'N/A' }} 
                        ({{ $transaction->customer->customer_name_fa ?? 'N/A' }})
                    </td>
                    <td>
                        {{ $transaction->vendor->company_name_en ?? 'N/A' }} 
                        ({{ $transaction->vendor->company_name_fa ?? 'N/A' }})
                    </td>
                    <td>{{ $transaction->amount }}</td>
                    <td>{{ ucfirst($transaction->transaction_type) }}</td>
                    <td>{{ ucwords(str_replace('_', ' ', $transaction->source)) }}</td>
                    <td>{{ $transaction->description }}</td>
                    <td>{{ $transaction->transaction_date->format('Y-m-d') }}</td>
                    <td>
                        @if ($transaction->trashed())
                            <button class="btn btn-success btn-sm restoreTransaction"
                                data-id="{{ $transaction->id }}">Restore</button>
                        @else
                            {{-- Edit and Delete buttons for active transactions --}}
                            <button class="btn btn-sm btn-info editTransaction Btn"
                                data-id="{{ $transaction->id }}">Edit</button>
                            <button class="btn btn-sm btn-danger deleteTransaction Btn"
                                data-id="{{ $transaction->id }}">Delete</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
    {{-- Pagination --}}
    {{ $transactions->links() }}

    {{-- Transaction Modal --}}
    <div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="transactionForm">
                @csrf
                <input type="hidden" id="transactionId" name="transaction_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="transactionModalLabel">Add/Edit Transaction</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="account_id">Account</label>
                            <select class="form-control" id="account_id" name="account_id" required>
                                <option value="">Select Account</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="customer_id">Customer</label>
                            <select class="form-control" id="customer_id" name="customer_id">
                                <option value="">Select Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->customer_name_en }}{{ $customer->customer_name_fa }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="vendor_id">Vendor</label>
                            <select class="form-control" id="vendor_id" name="vendor_id">
                                <option value="">Select Vendor</option>
                                @foreach ($vendors as $vendor)
                                    <option value="{{ $vendor->id }}">
                                        {{ $vendor->company_name_en }}{{ $vendor->company_name_fa }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" min="0"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="transaction_type">Transaction Type</label>
                            <select class="form-control" id="transaction_type" name="transaction_type" required>
                                <option value="">Select Type</option>
                                <option value="income">Income</option>
                                <option value="expense">Expense</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="source">Source</label>
                            <select class="form-control" id="source" name="source" required>
                                <option value="">Select Source</option>
                                @foreach (['payment_to_vendor', 'salary_payment', 'daily_expense', 'payment_to_distributors', 'customer_payment_received', 'advance_payment_for_purchasing', 'transfer_to_sarrafi', 'transfer_to_cash', 'miscellaneous_income', 'other_expense'] as $source)
                                    <option value="{{ $source }}">{{ ucwords(str_replace('_', ' ', $source)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="transaction_date">Transaction Date</label>
                            <input type="date" class="form-control" id="transaction_date" name="transaction_date"
                                required>
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

        // Create or Edit Transaction
        $('#transactionForm').on('submit', function(e) {
            e.preventDefault();
            const url = $('#transactionId').val() ? `/transactions/${$('#transactionId').val()}` : `/transactions`;
            const method = $('#transactionId').val() ? 'PUT' : 'POST';
            // Disable the submit button to prevent multiple submissions
            const submitButton = $(this).find('button[type="submit"]');
            submitButton.prop('disabled', true);

            $.ajax({
                url: url,
                method: method,
                data: $(this).serialize(),
                success: function(response) {
                    $('#transactionModal').modal('hide');
                    $('#transactionForm')[0].reset();
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = Object.values(errors).flat().join('\n');

                    Swal.fire({
                        title: 'Error!',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    submitButton.prop('disabled', false);
                },
            });
        });

        // Edit Transaction
        $('.editTransaction').click(function() {
            const id = $(this).data('id');
            $.get(`/transactions/${id}/edit`, function(transaction) {
                $('#transactionId').val(transaction.id);
                $('#account_id').val(transaction.account_id);
                $('#customer_id').val(transaction.customer_id);
                $('#vendor_id').val(transaction.vendor_id);
                $('#amount').val(transaction.amount);
                $('#transaction_type').val(transaction.transaction_type);
                $('#source').val(transaction.source);
                $('#description').val(transaction.description);
                $('#transaction_date').val(transaction.transaction_date);
                $('#transactionModal').modal('show');
            });
        });

        // Delete Transaction
        $('.deleteTransaction').click(function() {
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
                        url: `/transactions/${id}`,
                        method: 'DELETE',
                        success: function(response) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
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

        // Restore Transaction
        $('.restoreTransaction').click(function(e) {
            e.preventDefault();
            const id = $(this).data('id');

            Swal.fire({
                title: 'Restore Transaction',
                text: "Are you sure you want to restore this transaction?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, restore it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/transactions/${id}/restore`,
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
