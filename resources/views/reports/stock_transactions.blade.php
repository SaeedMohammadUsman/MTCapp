{{-- 
@extends('adminlte::page')

@section('title', __('menu.stock_transactions'))

@section('content_header')
    <h1>{{ __('menu.stock_transactions') }}</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card"> 
            <div class="card-body">
                <form method="GET" action="{{ route('reports.stock-transactions') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Item</label>
                            <select name="item_id" class="form-control">
                                <option value="">All Items</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->trade_name_en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Transaction Type</label>
                            <select name="transaction_type" class="form-control">
                                <option value="">All Types</option>
                                <option value="1" {{ request('transaction_type') == '1' ? 'selected' : '' }}>Stock In</option>
                                <option value="2" {{ request('transaction_type') == '2' ? 'selected' : '' }}>Stock Out</option>
                                <option value="3" {{ request('transaction_type') == '3' ? 'selected' : '' }}>Return</option>
                                <option value="4" {{ request('transaction_type') == '4' ? 'selected' : '' }}>Damaged</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('reports.stock-transactions') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th>Date</th>
                                <th>Item</th>
                                <th>Transaction Type</th>
                                <th>Quantity</th>
                                <th>Available Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($query as $transaction)
                                @foreach($transaction->details as $detail)
                                    <tr>
                                        <td>{{ $transaction->transaction_date }}</td>
                                        <td>{{ $detail->item->trade_name_en }}</td>
                                        <td>{{ $transaction->transaction_type }}</td>
                                        <td>{{ $detail->quantity }}</td>
                                        <td>{{ $detail->item->stock_quantity ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No transactions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop --}}


@extends('adminlte::page')

@section('title', __('menu.stock_transactions'))

@section('content_header')
    <h1>{{ __('menu.stock_transactions') }}</h1>
@stop

@section('content')
    {{-- Search and Filter Section --}}
    <div class="card">
        <div class="card-header">
            <form action="{{ route('reports.stock-transactions') }}" method="GET" class="form-inline">
                <div class="input-group input-group-sm mr-2">
                    <select name="item_id" class="form-control">
                        <option value="">All Items</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->trade_name_en }}
                            </option>
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
                    <a href="{{ route('reports.stock-transactions') }}" class="btn btn-secondary btn-sm ml-2">Reset</a>
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
                        <th>Date</th>
                        <th>Item</th>
                        <th>Transaction Type</th>
                        <th>Quantity</th>
                        <th>Available Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($query as $transaction)
                        @foreach($transaction->details as $detail)
                            <tr>
                                <td>{{ $transaction->transaction_date }}</td>
                                <td>{{ $detail->item->trade_name_en }}</td>
                                <td>
                                    @switch($transaction->transaction_type)
                                        @case(1)
                                            <span class="badge bg-success"><i class="fa fa-arrow-down"></i> Stock In</span>
                                            @break
                                        @case(2)
                                            <span class="badge bg-danger"><i class="fa fa-arrow-up"></i> Stock Out</span>
                                            @break
                                        @case(3)
                                            <span class="badge bg-info"><i class="fa fa-undo"></i> Return</span>
                                            @break
                                        @case(4)
                                            <span class="badge bg-warning"><i class="fa fa-times-circle"></i> Damaged</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary"><i class="fa fa-question-circle"></i> Unknown</span>
                                    @endswitch
                                </td>
                                <td>{{ $detail->quantity }}</td>
                                <td>{{ $detail->item->stock_quantity ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{-- Pagination Links --}}
            {{-- {{ $query->links('vendor.pagination.bootstrap-4') }} --}}
        </div>
    </div>
@stop

@section('css')
    {{-- Add any extra stylesheets if needed --}}
@stop

@section('js')
    {{-- Add any extra scripts if needed --}}
@stop
