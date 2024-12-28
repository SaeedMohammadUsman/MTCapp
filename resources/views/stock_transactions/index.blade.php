@extends('adminlte::page')

@section('title', 'Stock Transactions')

@section('content_header')
    <h1>Stock Transactions</h1>
@stop

@section('content')
    {{-- Search and Filter Section --}}
    <div class="card">
        <div class="card-header">
            <form action="{{ route('stock_transactions.index') }}" method="GET" class="form-inline">
                <div class="input-group input-group-sm mr-2">
                    <select name="item_id" class="form-control">
                        <option value="">All Items</option>
                        @foreach($items as $item)
                            {{-- <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->trade_name_en }}
                            </option> --}}
                        @endforeach
                    </select>
                </div>
                
                <div class="input-group input-group-sm mr-2">
                    <select name="transaction_type" class="form-control">
                        <option value="">All Types</option>
                        <option value="1" {{ request('transaction_type') == '1' ? 'selected' : '' }}>Stock In</option>
                        <option value="2" {{ request('transaction_type') == '2' ? 'selected' : '' }}>Stock Out</option>
                        <option value="3" {{ request('transaction_type') == '3' ? 'selected' : '' }}>Return</option>
                        <option value="4" {{ request('transaction_type') == '4' ? 'selected' : '' }}>Damaged</option>
                    </select>
                </div>
                
                <div class="input-group input-group-sm mr-2">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control" placeholder="Start Date">
                </div>

                <div class="input-group input-group-sm mr-2">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control" placeholder="End Date">
                </div>

                <div class="input-group input-group-sm mr-2">
                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    <a href="{{ route('stock_transactions.index') }}" class="btn btn-secondary btn-sm ml-2">Clear</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="card">
        <div class="card-body">
            <table class="table table-sm table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Batch Number</th>
                        <th>Remarks</th>
                        <th>Transaction Date</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stockTransactions as $stockTransaction)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if ($stockTransaction->receivedGood)
                                    {{ $stockTransaction->receivedGood->batch_number }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $stockTransaction->remarks }}</td>
                            <td>{{ $stockTransaction->transaction_date->format('Y-m-d H:i') }}</td>
                            <td>
                                @switch($stockTransaction->transaction_type)
                                    @case(1)
                                        <span class="badge bg-success"><i class="fa fa-arrow-down"></i> <!-- Stock In -->
                                        </span>
                                        @break
                                    @case(2)
                                        <span class="badge bg-danger">
                                            <i class="fa fa-arrow-up"></i> <!-- Stock Out -->
                                        </span>
                                        @break
                                    @case(3)
                                        <span class="badge bg-info">
                                            <i class="fa fa-undo"></i> <!-- Return -->
                                        </span>
                                        @break
                                    @case(4)
                                        <span class="badge bg-warning">
                                            <i class="fa fa-times-circle"></i> <!-- Damaged -->
                                        </span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">
                                            <i class="fa fa-question-circle"></i> <!-- Unknown -->
                                        </span>
                                @endswitch
                            </td>
                            <td>
                                <div class="btn-group">
                                    {{-- <a href="{{ route('stock_transactions.show', $stockTransaction->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> View
                                    </a> --}}
                                    <a href="{{ route('stock_transactions.show', $stockTransaction->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No stock transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{-- Pagination Links --}}
            {{ $stockTransactions->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@stop

@section('css')
    {{-- Add any extra stylesheets if needed --}}
@stop

@section('js')
    {{-- Add any extra scripts if needed --}}
@stop