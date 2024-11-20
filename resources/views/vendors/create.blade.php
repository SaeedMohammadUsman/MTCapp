@extends('adminlte::page')

@section('title', 'Create Vendor')

@section('content_header')
    <h1>Create New Vendor</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('vendors.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="company_name_en">Company Name (EN)</label>
                    <input type="text" name="company_name_en" class="form-control" id="company_name_en" required>
                </div>
                <div class="form-group">
                    <label for="company_name_fa">Company Name (FA)</label>
                    <input type="text" name="company_name_fa" class="form-control" id="company_name_fa" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" id="email" required>
                </div>
                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="text" name="phone_number" class="form-control" id="phone_number" required>
                </div>
                <div class="form-group">
                    <label for="address_en">Address (EN)</label>
                    <input type="text" name="address_en" class="form-control" id="address_en" required>
                </div>
                <div class="form-group">
                    <label for="address_fa">Address (FA)</label>
                    <input type="text" name="address_fa" class="form-control" id="address_fa" required>
                </div>
                <div class="form-group">
                    <label for="country_name">Country</label>
                    <select name="country_name" class="form-control" id="country_name" required>
                        <option value="Pakistan">Pakistan</option>
                        <option value="India">India</option>
                        <option value="Iran">Iran</option>
                    </select>
                </div>
               
                <button type="submit" class="btn btn-success">Save Item</button>
                <a href="{{ route('vendors.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@stop
