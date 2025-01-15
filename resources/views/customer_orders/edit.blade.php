@extends('adminlte::page')

@section('title', 'Edit Customer Order')

@section('content_header')
    <h1>Edit Customer Order</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('customer_orders.update', $customerOrder->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Customer Selection --}}
                <div class="form-group">
                    <label for="customer_id">Customer</label>
                    <select name="customer_id" id="customer_id" class="form-control" required>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" {{ $customer->id == $customerOrder->customer_id ? 'selected' : '' }}>
                                {{ $customer->customer_name_en }} ({{ $customer->customer_name_fa }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Remarks --}}
                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea name="remarks" id="remarks" class="form-control" required>{{ $customerOrder->remarks }}</textarea>
                </div>

                {{-- Status Selection --}}
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="pending" {{ $customerOrder->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ $customerOrder->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                {{-- Submit and Cancel Buttons --}}
                <button type="submit" class="btn btn-success">Update</button>
                <a href="{{ route('customer_orders.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
