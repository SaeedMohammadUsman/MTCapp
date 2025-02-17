
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
                <th>{{ __('Item') }} ({{ __('EN') }})</th>
                <th>{{ __('Item') }} ({{ __('FA') }})</th>
                <th>{{ __('Quantity') }}</th>
            </tr>
        </thead>
        <tbody id="stock-transactions-body">
            {{-- Data will be dynamically inserted here --}}
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">
                    <div id="total-summary" class="row">
                        <div class="col-md-4">
                            <h5 class="text-success">{{ __('Total Stock In') }}: <span id="total-in">0</span></h5>
                        </div>
                        <div class="col-md-4">
                            <h5 class="text-danger">{{ __('Total Stock Out') }}: <span id="total-out">0</span></h5>
                        </div>
                        <div class="col-md-4">
                            <h5>{{ __('Current Available Stock') }}: <span id="current-stock">0</span></h5>
                        </div>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>
</div>

@stop

{{-- Include JavaScript for filtering and data population --}}
<script>
    // Filter function
    function filter() {
        $.ajax({
            url: '{{ route("reports.stock-transactions.filter") }}',
            method: 'POST',
            data: $('#filter-form').serialize(),
            success: function(response) {
                if (response.success) {
                    // Show the table if there are results
                    $('#stock-transactions-table').show();
                    
                    // Clear the table body first
                    $('#stock-transactions-body').empty();
                    
                    // Populate the table with data
                    response.transactions.forEach(function(transaction) {
                        $('#stock-transactions-body').append(`
                            <tr>
                                <td>${transaction.transaction_date}</td>
                                <td>${transaction.item.trade_name_en}</td>
                                <td>${transaction.item.trade_name_fa}</td>
                                <td>${transaction.quantity}</td>
                            </tr>
                        `);
                    });
                    
                    // Update total summary
                    $('#total-in').text(response.total_in);
                    $('#total-out').text(response.total_out);
                    $('#current-stock').text(response.current_stock);
                }
            }
        });
    }

    // When the filter is applied or cleared, trigger the filter function
    $('#filter-form').on('submit', function(e) {
        e.preventDefault();
        filter();
    });

    // Clear filters and hide the table
    $('#clear-btn').on('click', function() {
    // Reset the filter form
    $('#filter-form')[0].reset();
    
    // Submit the form (it will trigger a POST request)
    $('#filter-form').submit();
});
</script>
