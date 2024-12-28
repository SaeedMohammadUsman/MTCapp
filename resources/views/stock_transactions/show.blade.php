@extends('adminlte::page')

@section('title', 'Stock Transaction Details')

@section('content_header')
    <h1>Stock Transaction #{{ $stockTransaction->id }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <!-- Stock Transaction Details -->
            <p><strong>Transaction Type:</strong> {{ $stockTransaction->transaction_type }}</p>
            {{-- <p><strong>Reference ID:</strong> {{ $stockTransaction->reference_id }}</p> --}}
         
            <p><strong>Remarks:</strong> {{ $stockTransaction->remarks ?? 'No remarks' }}</p>
            <p><strong>Transaction Date:</strong> {{ $stockTransaction->transaction_date->format('Y-m-d H:i') }}</p>

            <!-- Stock Transaction Details (Item-wise) -->
            <h3>Stock Transaction Details</h3>
            @if ($stockTransaction->details->isEmpty())
                <p>No details available for this transaction.</p>
            @else
                <table class="table table-hover table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Item Name (EN)</th>
                            <th>Quantity</th>
                            <th>Arrival Price</th>
                            <th>Expiration Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stockTransaction->details as $detail)
                        {{-- {{ dd($detail) }}   --}}
                        {{-- {{ dd($detail->item) }} --}}
                            <tr>
                                {{-- <td>{{ $detail->item->trade_name_en }}{{ $detail->item->trade_name_fa }}</td>  --}}
                                <td>{{ $detail->receivedGoodDetail->item->trade_name_en }} {{ $detail->receivedGoodDetail->item->trade_name_fa }}</td>
                                {{-- <td>{{ dd($detail->item->trade_name_en) }}</td> --}}
                                <td>{{ $detail->receivedGoodDetail->quantity }}</td> 
                               <td>{{ number_format($detail->arrival_price, 2) }}</td>
                               <td>{{ $detail->receivedGoodDetail->expiration_date ? \Carbon\Carbon::parse($detail->receivedGoodDetail->expiration_date)->format('Y-m-d') : 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <!-- Back to List -->
            <a href="{{ route('stock_transactions.index') }}" class="btn btn-secondary mt-3">Back to List</a>
        </div>
    </div>
@stop
