@extends('adminlte::page')

@section('title', __('menu.account_report'))

@section('content_header')
    <h1>{{ __('menu.account_report') }}</h1>
@stop

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <form id="filter-form" method="POST" action="{{ route('reports.finance.filter') }}" class="form-inline">
                @csrf
                <select class="form-control mr-2" name="account_id" id="account">
                    <option value="">Select Account</option>
                    @foreach ($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                    @endforeach
                </select>

                <div class="input-group mr-2">
                    <input type="date" class="form-control" name="date_from" id="date_from">
                    <input type="date" class="form-control ml-2" name="date_to" id="date_to">
                </div>

                <select class="form-control mr-2" name="source" id="source">
                    <option value="">All Sources</option>
                    <option value="payment_to_vendor">Payment to Vendor</option>
                    <option value="salary_payment">Salary Payment</option>
                    <option value="daily_expense">Daily Expense</option>
                    <option value="payment_to_distributors">Payment to Distributors</option>
                    <option value="customer_payment_received">Customer Payment Received</option>
                    <option value="advance_payment_for_purchasing">Advance Payment for Purchasing</option>
                    <option value="transfer_to_sarrafi">Transfer to Sarrafi</option>
                    <option value="transfer_to_cash">Transfer to Cash</option>
                    <option value="miscellaneous_income">Miscellaneous Income</option>
                    <option value="other_expense">Other Expense</option>
                </select>

                <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                <a href="{{ route('reports.finance') }}" class="btn btn-secondary ml-2">Clear</a>
                <button id="export-btn" class="btn btn-success" disabled>
                    <i class="fas fa-download"></i>
                </button>
            </form>
        </div>
    </div>

    <div id="finance-transactions-table" style="display: none;">
        <table class="table table-hover table-bordered table-sm">
            <thead>
                <tr>
                    <th>{{ __('Transaction Date') }}</th>
                    <th>{{ __('Account') }}</th>
                    <th>{{ __('Transaction Type') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Source') }}</th>
                    <th>{{ __('Description') }}</th>
                </tr>
            </thead>
            <tbody id="finance-transactions-body">
                {{-- Data will be dynamically inserted here --}}
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right">{{ __('Total Income') }}:</td>
                    <td><strong id="total-income">0</strong></td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right">{{ __('Total Expense') }}:</td>
                    <td><strong id="total-expense">0</strong></td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<script>
$(document).ready(function () {
    // Set up CSRF token for AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Filter function
    function filter() {
        $.ajax({
            url: '{{ route("reports.finance.filter") }}',
            method: 'POST',
            data: $('#filter-form').serialize(),
            success: function(response) {
                if (response.transactions && response.transactions.length > 0) {
                    $('#finance-transactions-table').show();
                    $('#finance-transactions-body').empty();

                    response.transactions.forEach(function(transaction) {
                        $('#finance-transactions-body').append(`
                            <tr>
                                <td>${new Date(transaction.transaction_date).toLocaleDateString()}</td>
                                <td>${transaction.account.name}</td>
                                <td>${transaction.transaction_type}</td>
                                <td>${transaction.amount}</td>
                                <td>${transaction.source}</td>
                                <td>${transaction.description || ''}</td>
                            </tr>
                        `);
                    });

                    $('#total-income').text(response.total_income);
                    $('#total-expense').text(response.total_expense);
                    
                    $('#export-btn').prop('disabled', false); 
                } else {
                    $('#finance-transactions-table').hide();
                    $('#total-income').text('0');
                    $('#total-expense').text('0');
                    alert('No data found for the selected filters.');
                }
            },
            error: function(xhr, status, error) {
                alert("An error occurred while fetching finance transactions: " + error);
            }
        });
    }

    // Handle form submission
    $('#filter-form').on('submit', function(e) {
        e.preventDefault();
        filter();
        
    });



// Export button click event
$('#export-btn').on('click', function () {
    // Create a new workbook
    const wb = XLSX.utils.book_new();
    
    // Prepare data for the Excel sheet
    const data = [];
    
    // Add headers
    data.push(['Transaction Date', 'Account', 'Transaction Type', 'Amount', 'Source', 'Description']);
    
    // Loop through the table rows and gather data
    $('#finance-transactions-body tr').each(function () {
        const transactionDate = $(this).find('td').eq(0).text();
        const accountName = $(this).find('td').eq(1).text();
        const transactionType = $(this).find('td').eq(2).text();
        const amount = $(this).find('td').eq(3).text();
        const source = $(this).find('td').eq(4).text();
        const description = $(this).find('td').eq(5).text();
        
        data.push([transactionDate, accountName, transactionType, amount, source, description]);
    });
    
    // Add footer data
    const totalIncome = $('#total-income').text();
    const totalExpense = $('#total-expense').text();
    
    data.push(['', '', 'Total Income:', totalIncome, '', '']);
    data.push(['', '', 'Total Expense:', totalExpense, '', '']);

    // Convert data to a worksheet
    const ws = XLSX.utils.aoa_to_sheet(data);
    
    // Set column widths for better readability
    const wscols = [
        { wch: 20 }, // Transaction Date
        { wch: 30 }, // Account
        { wch: 20 }, // Transaction Type
        { wch: 15 }, // Amount
        { wch: 20 }, // Source
        { wch: 30 }  // Description
    ];
    ws['!cols'] = wscols;

    // Append the worksheet to the workbook
    XLSX.utils.book_append_sheet(wb, ws, 'Finance Transactions');
    
    // Export the workbook to a file
    XLSX.writeFile(wb, 'finance_report.xlsx');
});
    // Show table if filters are already applied
    if ("{{ request('account_id') }}" || "{{ request('date_from') }}" || "{{ request('date_to') }}" || "{{ request('source') }}") {
        filter();
    }
});
</script>
@stop