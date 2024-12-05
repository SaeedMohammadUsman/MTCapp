@extends('adminlte::page')

@section('title', 'Create Item')

@section('content_header')
    <h1>Create Item</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('items.store') }}" method="POST">
                @csrf

                <!-- Dropdown for Categories -->
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select class="form-control" id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name_en }} ({{ $category->name_fa }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Name in English -->
                <div class="form-group">
                    <label for="name_en">Name (EN)</label>
                    <input type="text" class="form-control" id="name_en" name="name_en" value="{{ old('name_en') }}" required>
                </div>

                <!-- Name in Persian -->
                <div class="form-group">
                    <label for="name_fa">Name (FA)</label>
                    <input type="text" class="form-control" id="name_fa" name="name_fa" value="{{ old('name_fa') }}" required>
                </div>

                <!-- Item Code (Read-Only, Auto-Generated) -->
                <div class="form-group">
                    <label for="item_code">Item Code</label>
                    <input type="text" class="form-control" id="item_code" name="item_code" value="{{ old('item_code', 'Auto-generated on save') }}" readonly>
                </div>

                <!-- Additional Fields -->
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" required>
                </div>

                <div class="form-group">
                    <label for="stock">Stock Quantity</label>
                    <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock') }}" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success">Save Item</button>
                <a href="{{ route('items.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@stop

@section('css')
    {{-- Add any extra stylesheets if needed --}}
@stop

@section('js')
    <script>
        console.log("Creating a new item.");
    </script>
@stop
