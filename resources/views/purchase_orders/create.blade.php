@extends('adminlte::page')

@section('title', 'Create Purchase Order')

@section('content_header')
    <h1>Create Purchase Order</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('purchase_orders.store') }}" method="POST">
                @csrf
                <!-- Vendor Selection -->
                <div class="form-group">
                    <label for="vendor_id">Vendor</label>
                    <select name="vendor_id" id="vendor_id" class="form-control" required>
                        <option value="" disabled selected>Select Vendor</option>
                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}">
                                {{ $vendor->company_name_en }} ({{ $vendor->company_name_fa }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Remarks -->
                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea name="remarks" id="remarks" class="form-control">{{ old('remarks') }}</textarea>
                </div>

                <!-- Submit and Cancel Buttons -->
                <button type="submit" class="btn btn-success">Create Order</button>
                <a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
