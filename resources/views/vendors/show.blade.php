@extends('adminlte::page')

@section('title', 'Vendor Details')

@section('content_header')
    <h1>Vendor Details</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>{{ $vendor->company_name_en }} ({{ $vendor->company_name_fa }})</h3>
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> {{ $vendor->id }}</p>
            <p><strong>Company Name (EN):</strong> {{ $vendor->company_name_en }}</p>
            <p><strong>Company Name (FA):</strong> {{ $vendor->company_name_fa }}</p>
            <p><strong>Email:</strong> {{ $vendor->email }}</p>
            <p><strong>Phone Number:</strong> {{ $vendor->phone_number }}</p>
            <p><strong>Address (EN):</strong> {{ $vendor->address_en }}</p>
            <p><strong>Address (FA):</strong> {{ $vendor->address_fa }}</p>
            <p><strong>Country:</strong> {{ $vendor->country_name }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('vendors.index') }}" class="btn btn-secondary">Back to List</a>
            <a href="{{ route('vendors.edit', $vendor->id) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('vendors.destroy', $vendor->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
@stop
