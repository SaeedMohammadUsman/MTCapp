@extends('adminlte::page')

@section('title', 'Create Customer')

@section('content_header')
    <h1>Create Customer</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('customers.store') }}" method="POST">
                @csrf

                <!-- Customer Name (English) -->
                <div class="form-group">
                    <label for="customer_name_en">Name (English)</label>
                    <input type="text" name="customer_name_en" id="customer_name_en" class="form-control" value="{{ old('customer_name_en') }}" required>
                </div>

                <!-- Customer Name (Farsi) -->
                <div class="form-group">
                    <label for="customer_name_fa">Name (Farsi)</label>
                    <input type="text" name="customer_name_fa" id="customer_name_fa" class="form-control" value="{{ old('customer_name_fa') }}" required>
                </div>

        

                <!-- District Selection -->
                <div class="form-group">
                    <label for="district_id">District</label>
                    <select name="district_id" id="district_id" class="form-control" required>
                        @foreach ($districts as $district)
                        <option value="{{ $district->id }}">
                            {{ $district->en_name }} ({{ $district->name }})
                        </option>
                    @endforeach
                    </select>
                </div>

                <!-- Address -->
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}">
                </div>

                <!-- Customer Phone -->
                <div class="form-group">
                    <label for="customer_phone">Phone</label>
                    <input type="text" name="customer_phone" id="customer_phone" class="form-control" value="{{ old('customer_phone') }}" required>
                </div>

                <!-- Customer Email -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
                </div>

                <!-- Submit and Cancel Buttons -->
                <button type="submit" class="btn btn-success">Create</button>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script>
        // Fetch districts based on selected province
        $('#province_id').change(function() {
            let provinceId = $(this).val();
            if (provinceId) {
                $.ajax({
                    url: '/api/districts/' + provinceId,
                    method: 'GET',
                    success: function(data) {
                        let districts = data.districts;
                        $('#district_id').empty();
                        $('#district_id').append('<option value="" disabled selected>Select District</option>');
                        $.each(districts, function(key, value) {
                            $('#district_id').append('<option value="' + value.id + '">' + value.en_name + ' (' + value.name + ')</option>');
                        });
                    }
                });
            }
        });
    </script>
@stop
