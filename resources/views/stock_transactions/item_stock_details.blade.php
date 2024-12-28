@extends('adminlte::page')

@section('title', 'Stock Item Details')
@section('content')
<h1>Item Stock Details</h1>
<p>Total Quantity: {{ $totalQuantity }}</p>

<h2>Batch Details</h2>
<table>
    <thead>
        <tr>
            <th>Batch Number</th>
            <th>Total Quantity</th>
            <th>Arrival Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach($batchDetails as $batchId => $batch)
        <tr>
            <td>{{ $batch['batch_number'] }}</td>
            <td>{{ $batch['total_quantity'] }}</td>
            <td>{{ $batch['arrival_price'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

    <!-- Stock Transaction Details -->
    <h3>Stock Transaction Details</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Transaction Date</th>
                <th>Transaction Type</th>
                <th>Quantity</th>
                <th>Arrival Price</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stockTransactionDetails as $detail)
                <tr>
                    <td>{{ $detail->stockTransaction->transaction_date }}</td>
                    <td>
                        @switch($detail->stockTransaction->transaction_type)
                            @case(1) Stock In @break
                            @case(2) Stock Out @break
                            @case(3) Return @break
                            @case(4) Damaged @break
                        @endswitch
                    </td>
                    <td>{{ $detail->receivedGoodDetail->quantity ?? 0 }}</td>
                    <td>{{ number_format($detail->arrival_price, 2) }}</td>
                    <td>{{ $detail->remarks }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection