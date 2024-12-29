@extends('adminlte::page')

@section('title', 'Edit Customer')

@section('content_header')
    <h1>Edit Customer</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Customer Name (English) --}}
                <div class="form-group">
                    <label for="customer_name_en">Customer Name (English)</label>
                    <input type="text" name="customer_name_en" id="customer_name_en" class="form-control" 
                           value="{{ old('customer_name_en', $customer->customer_name_en) }}" required>
                </div>

                {{-- Customer Name (Farsi) --}}
                <div class="form-group">
                    <label for="customer_name_fa">Customer Name (Farsi)</label>
                    <input type="text" name="customer_name_fa" id="customer_name_fa" class="form-control" 
                           value="{{ old('customer_name_fa', $customer->customer_name_fa) }}" required>
                </div>

                {{-- District Selection --}}
                <div class="form-group">
                    <label for="district_id">District</label>
                    <select name="district_id" id="district_id" class="form-control" required>
                        <option value="" disabled>Select District</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district->id }}" 
                                {{ $district->id == $customer->district_id ? 'selected' : '' }}>
                                {{ $district->name }} ({{ $district->province->name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Address --}}
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" class="form-control" 
                           value="{{ old('address', $customer->address) }}">
                </div>

                {{-- Phone --}}
                <div class="form-group">
                    <label for="customer_phone">Phone</label>
                    <input type="text" name="customer_phone" id="customer_phone" class="form-control" 
                           value="{{ old('customer_phone', $customer->customer_phone) }}" required>
                </div>

                {{-- Email --}}
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" 
                           value="{{ old('email', $customer->email) }}">
                </div>

            

                {{-- Submit and Cancel Buttons --}}
                <button type="submit" class="btn btn-success">Update</button>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
