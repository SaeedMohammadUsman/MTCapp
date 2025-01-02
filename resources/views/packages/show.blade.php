@extends('adminlte::page')

@section('title', 'Price Package Details')

@section('content_header')
    <h1>Price Package: {{ $package->title }}</h1>
@stop
@section('content')
    <div class="card">
        <div class="card-body">
            <!-- Package Information -->
            <p><strong>Package Title:</strong> {{ $package->title }}</p>
            <p><strong>Customer:</strong>
                {{ $package->customer->customer_name_en }}{{ $package->customer->customer_name_fa }}</p>

            <!-- Price Package Details -->
            <h3>Package Details</h3>
            @if ($package->pricePackageDetails->isEmpty())
                <p>No items available in this package.</p>
            @else
            
            <table class="table table-hover table-striped table-sm">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Item Name</th>
                        <th>Arrival Price</th>
                        <th>Discount (%)</th>
                        <th>Final Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pricePackageDetails as $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $detail['trade_name_en'] }}</td>
                            <td>{{ $detail['arrival_price'] }}</td>
                            <td>{{ $detail['discount'] }}%</td>
                            <td>{{ $detail['final_price'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            
            @endif
            <a href="{{ route('packages.details.create', $package->id) }}" class="btn btn-primary mt-3">
                Add New Item
            </a>

            <!-- Back to List -->
            <a href="{{ route('packages.index') }}" class="btn btn-secondary mt-3">Back to List</a>
        </div>
    </div>
@stop
