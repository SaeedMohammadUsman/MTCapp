          @extends('adminlte::page')
@section('title', __('menu.customer_report'))
@section('content_header')
    <h1>{{ __('menu.customer_report') }}</h1>
@stop

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <form id="filter-form" method="POST" action="{{ route('reports.customer.filter') }}" class="form-inline">
                @csrf
                <select class="form-control mr-2" name="customer_id" id="customer">
                    <option value="">Select Customer</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->customer_name_en }}{{ $customer->customer_name_fa }}</option>
                    @endforeach
                </select>

                <div class="input-group mr-2">
                    <input type="date" class="form-control" name="date_from" id="date_from">
                    <input type="date" class="form-control ml-2" name="date_to" id="date_to">
                </div>

                <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                <a href="{{ route('reports.customer') }}" class="btn btn-secondary ml-2">Clear</a>
                <button id="export-btn" class="btn btn-success" disabled>
                    <i class="fas fa-download"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Report Table (Initially Hidden) -->
<div id="customer-orders-table" style="display: none;">
    {{-- <h3 id="customer-name"></h3> --}}
    <div class="row ">
        <div class="col-md-6">
            <h4>Orders</h4>
            <table class="table table-hover table-bordered table-sm">
                <thead>
                    <tr>
                        <th>{{ __('Order Date') }}</th>
                        <th>{{ __('Order Total Amount') }}</th>
                    </tr>
                </thead>
                <tbody id="customer-orders-body">
                    {{-- Data will be dynamically inserted here --}}
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-right">{{ __('Total Amount') }}:</td>
                        <td><strong id="total-amount">0</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="col-md-6">
            <h4>Transactions</h4>
            <table class="table table-hover table-bordered table-sm">
                <thead>
                    <tr>
                        <th>{{ __('Transaction Date') }}</th>
                        <th>{{ __('Transaction Amount') }}</th>
                    </tr>
                </thead>
                <tbody id="customer-transactions-body">
                    {{-- Data will be dynamically inserted here --}}
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-right">{{ __('Total Paid') }}:</td>
                        <td><strong id="total-paid">0</strong></td>
                    </tr>
                    <tr>
                        <td class="text-right">{{ __('Remaining Balance') }}:</td>
                        <td><strong id="remaining-balance">0</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
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
            url: '{{ route("reports.customer.filter") }}',
            method: 'POST',
            data: $('#filter-form').serialize(),
            success: function(response) {
                if (response.orders && response.orders.length > 0) {
                    // Show the table
                    $('#customer-orders-table').show();
                    
                    // Set customer name
                    $('#customer-name').text(response.orders[0].customer.customer_name_en + ' ' + response.orders[0].customer.customer_name_fa);
                    
                    // Clear the orders and transactions body first
                    $('#customer-orders-body').empty();
                    $('#customer-transactions-body').empty();
                    
                    // Populate the orders table with data
                    response.orders.forEach(function(order) {
                        $('#customer-orders-body').append(`
                            <tr>
                                <td>${new Date(order.order_date).toLocaleDateString()}</td>
                                <td>${order.total_amount}</td>
                            </tr>
                        `);
                    });

                    // Populate the transactions table with data
                    response.transactions.forEach(function(transaction) {
                        $('#customer-transactions-body').append(`
                            <tr>
                                <td>${new Date(transaction.transaction_date).toLocaleDateString()}</td>
                                <td>${transaction.amount}</td>
                            </tr>
                        `);
                    });

                    // Update total amounts
                    $('#total-amount').text(response.total_amount);
                    $('#total-paid').text(response.total_paid);
                    $('#remaining-balance').text(response.remaining_balance);
                } else {
                    $('#customer-orders-table').hide();
                    $('#total-amount').text('0');
                    $('#total-paid').text('0');
                    $('#remaining-balance').text('0');
                    alert('No data found for the selected filters.');
                }
            },
            error: function(xhr, status, error) {
                alert("An error occurred while fetching customer data: " + error);
            }
        });
    }

    // Handle form submission
    $('#filter-form').on('submit', function(e) {
        e.preventDefault();
        filter();
        $('#export-btn').prop('disabled', false);
    });

    // Export button click event
$('#export-btn').on('click', function () {
    // Create a new workbook
    const wb = XLSX.utils.book_new();
    
    // Prepare data for the Excel sheet
    const data = [];
    
    // Add headers for Orders
    data.push(['Order Date', 'Order Total Amount']);
    
    // Loop through the orders table rows and gather data
    $('#customer-orders-body tr').each(function () {
        const orderDate = $(this).find('td').eq(0).text();
        const orderTotal = $(this).find('td').eq(1).text();
        
        data.push([orderDate, orderTotal]);
    });

    // Add a new sheet for Orders
    const wsOrders = XLSX.utils.aoa_to_sheet(data);
    XLSX.utils.book_append_sheet(wb, wsOrders, 'Orders');

    // Prepare data for Transactions
    const transactionData = [];
    
    // Add headers for Transactions
    transactionData.push(['Transaction Date', 'Transaction Amount']);
    
    // Loop through the transactions table rows and gather data
    $('#customer-transactions-body tr').each(function () {
        const transactionDate = $(this).find('td').eq(0).text();
        const transactionAmount = $(this).find('td').eq(1).text();
        
        transactionData.push([transactionDate, transactionAmount]);
    });

    // Add a new sheet for Transactions
    const wsTransactions = XLSX.utils.aoa_to_sheet(transactionData);
    XLSX.utils.book_append_sheet(wb, wsTransactions, 'Transactions');

    // Add footer data to the Orders sheet
    const totalAmount = $('#total-amount').text();
    const totalPaid = $('#total-paid').text();
    const remainingBalance = $('#remaining-balance').text();

    // Append footer data to the Orders sheet
    data.push(['', '']);
    data.push(['Total Amount:', totalAmount]);
    data.push(['Total Paid:', totalPaid]);
    data.push(['Remaining Balance:', remainingBalance]);

    // Update the Orders sheet with footer data
    XLSX.utils.sheet_add_aoa(wsOrders, [['', ''], ['Total Amount:', totalAmount], ['Total Paid:', totalPaid], ['Remaining Balance:', remainingBalance]], { origin: -1 });

    // Export the workbook to a file
    XLSX.writeFile(wb, 'customer_report.xlsx');
});

    // Show table if filters are already applied
    if ("{{ request('customer_id') }}" || "{{ request('date_from') }}" || "{{ request('date_to') }}") {
        filter();
    }
});
</script>