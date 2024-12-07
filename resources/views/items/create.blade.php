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


                <div class="form-group">
                    <label for="trade_name_en">Trade Name (EN)</label>
                    <input type="text" name="trade_name_en" class="form-control" value="{{ old('trade_name_en') }}" required>
                </div>

                <div class="form-group">
                    <label for="trade_name_fa">Trade Name (FA)</label>
                    <input type="text" name="trade_name_fa" class="form-control" value="{{ old('trade_name_fa') }}" required>
                </div>

                <div class="form-group">
                    <label for="used_for_en">Used For (EN)</label>
                    <input type="text" name="used_for_en" class="form-control" value="{{ old('used_for_en') }}" required>
                </div>

                <div class="form-group">
                    <label for="used_for_fa">Used For (FA)</label>
                    <input type="text" name="used_for_fa" class="form-control" value="{{ old('used_for_fa') }}" required>
                </div>

                <div class="form-group">
                    <label for="size">Size</label>
                    <input type="text" name="size" class="form-control" value="{{ old('size') }}" required>
                </div>

                <div class="form-group">
                    <label for="description_en">Description (EN)</label>
                    <textarea name="description_en" class="form-control" required>{{ old('description_en') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="description_fa">Description (FA)</label>
                    <textarea name="description_fa" class="form-control" required>{{ old('description_fa') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select name="category_id" class="form-control" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name_en }} ({{ $category->name_fa }})
                            </option>
                        @endforeach
                    </select>
                </div>

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
