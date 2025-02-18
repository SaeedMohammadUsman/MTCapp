@extends('adminlte::page')
@section('title', __('menu.stock_transaction_report'))
@section('content_header')
    <h1>{{ __('menu.stock_transaction_report') }}</h1>
@stop
@section('content')
<div class="container">
    
    <div class="card">
        <div class="card-header">
            <form id="filter-form" method="POST" action="{{ route('reports.stock-transactions.filter') }}" class="form-inline">
                @csrf
                <select class="form-control mr-2" name="item_id" id="item">
                    <option value="">Select Item</option>
                    @foreach ($items as $item)
                        <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->trade_name_en }} ({{ $item->trade_name_fa }})
                        </option>
                    @endforeach
                </select>
    
                <div class="input-group mr-2">
                    <input type="date" class="form-control" name="date_from" id="date_from" value="{{ request('date_from') }}">
                    <input type="date" class="form-control ml-2" name="date_to" id="date_to" value="{{ request('date_to') }}">
                </div>
    
                <select class="form-control mr-2" name="transaction_type" id="transaction_type">
                    <option value="">All</option>
                    <option value="in" {{ request('transaction_type') == 'in' ? 'selected' : '' }}>Stock In</option>
                    <option value="out" {{ request('transaction_type') == 'out' ? 'selected' : '' }}>Stock Out</option>
                </select>
    
                <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                
                <a href="{{ route('reports.stock-transactions') }}" class="btn btn-secondary ml-2">Clear</a>

                <button id="export-btn" class="btn btn-success" disabled>
                    <i class="fas fa-download"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Report Table (Initially Hidden) -->
<div id="stock-transactions-table" style="display: none;">
    <table class="table table-hover table-bordered table-sm">
        <thead>
            <tr>
                <th>{{ __('Transaction Date') }}</th>
                <th>{{ __('Item') }}</th>
                <th>{{ __('Type') }}</th>
                
                <th>{{ __('Quantity') }}</th>
            </tr>
        </thead>
        <tbody id="stock-transactions-body">
            {{-- Data will be dynamically inserted here --}}
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-center">
                    <div id="total-summary" class="d-flex justify-content-between px-3">
                        <small class="text-success">{{ __('Total Stock In') }}: <strong id="total-in">0</strong></small>
                        <small class="text-danger">{{ __('Total Stock Out') }}: <strong id="total-out">0</strong></small>
                        <small>{{ __('Current Available Stock') }}: <strong id="current-stock">0</strong></small>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>
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
            url: '{{ route("reports.stock-transactions.filter") }}',
            method: 'POST',
            data: $('#filter-form').serialize(),
            success: function(response) {
                if (response.transactions && response.transactions.length > 0) {
                    // Show the table
                    $('#stock-transactions-table').show();
                    
                    // Clear the table body first
                    $('#stock-transactions-body').empty();
                    
                    // Populate the table with data
                    response.transactions.forEach(function(transaction) {
                        transaction.details.forEach(function(detail) {
                            let type = transaction.transaction_type === "1" ? 
                                '<span class="badge badge-success">Stock In</span>' : 
                                '<span class="badge badge-danger">Stock Out</span>';
                            
                            $('#stock-transactions-body').append(`
                                <tr>
                                    <td>${new Date(transaction.transaction_date).toLocaleDateString()}</td>
                                   <td>${detail.item.trade_name_en} / ${detail.item.trade_name_fa}</td>
<td>${type}</td>

                                    <td>
                                       
                                        ${detail.quantity}
                                    </td>
                                </tr>
                            `);
                        });
                    });

                    // Update totals
                    $('#total-in').text(response.total_in);
                    $('#total-out').text(response.total_out);
                    $('#current-stock').text(response.current_stock);

                    // Enable export button if data is present
                    $('#export-btn').prop('disabled', false);
                } else {
                    $('#stock-transactions-table').hide();
                    $('#export-btn').prop('disabled', true);
                    alert('No data found for the selected filters.');
                }
            },
            error: function(xhr, status, error) {
                alert("An error occurred while fetching stock transactions: " + error);
            }
        });
    }

    // Handle form submission
    $('#filter-form').on('submit', function(e) {
        e.preventDefault();
        filter();
    });
    
    $('#export-btn').on('click', function () {
    // Create a new workbook
    const wb = XLSX.utils.book_new();
    
    // Prepare data for the Excel sheet
    const data = [];
    
    // Add headers
    data.push(['Transaction Date', 'Item', 'Transaction Type', 'Quantity']);
    
    // Loop through the table rows and gather data
    $('#stock-transactions-body tr').each(function () {
        const transactionDate = $(this).find('td').eq(0).text();
        const itemName = $(this).find('td').eq(1).text();
        const transactionType = $(this).find('td').eq(2).text();
        const quantity = $(this).find('td').eq(3).text();
        
        data.push([transactionDate, itemName, transactionType, quantity]);
    });
    
    // Add footer data
    const totalIn = $('#total-in').text();
    const totalOut = $('#total-out').text();
    const currentStock = $('#current-stock').text();
    
    data.push(['', '', 'Total Stock In:', totalIn]);
    data.push(['', '', 'Total Stock Out:', totalOut]);
    data.push(['', '', 'Current Available Stock:', currentStock]);

    // Convert data to a worksheet
    const ws = XLSX.utils.aoa_to_sheet(data);
    
    // Set column widths for better readability
    const wscols = [
        { wch: 20 }, // Transaction Date
        { wch: 30 }, // Item
        { wch: 20 }, // Transaction Type
        { wch: 15 }  // Quantity
    ];
    ws['!cols'] = wscols;

    // Append the worksheet to the workbook
    XLSX.utils.book_append_sheet(wb, ws, 'Stock Transactions');
    
    // Export the workbook to a file
    XLSX.writeFile(wb, 'stock_tracking.xlsx');
});

    // Show table if filters are already applied
    if ("{{ request('item_id') }}" || "{{ request('date_from') }}" || "{{ request('transaction_type') }}") {
        filter();
    }
});
</script>
@stop