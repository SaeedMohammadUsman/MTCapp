@extends('adminlte::page')

@section('title', 'Edit Item')

@section('content_header')
    <h1>Edit Item</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('items.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="item_code">Item Code</label>
                    <input type="text" class="form-control" id="item_code" name="item_code" value="{{ old('item_code', $item->item_code) }}" required>
                </div>

                <div class="form-group">
                    <label for="trade_name_en">Name (EN)</label>
                    <input type="text" class="form-control" id="trade_name_en" name="trade_name_en" value="{{ old('trade_name_en', $item->trade_name_en) }}" required>
                </div>

                <div class="form-group">
                    <label for="trade_name_fa">Name (FA)</label>
                    <input type="text" class="form-control" id="trade_name_fa" name="trade_name_fa" value="{{ old('trade_name_fa', $item->trade_name_fa) }}" required>
                </div>

                <div class="form-group">
                    <label for="used_for_en">Used For (EN)</label>
                    <input type="text" class="form-control" id="used_for_en" name="used_for_en" value="{{ old('used_for_en', $item->used_for_en) }}" required>
                </div>

                <div class="form-group">
                    <label for="used_for_fa">Used For (FA)</label>
                    <input type="text" class="form-control" id="used_for_fa" name="used_for_fa" value="{{ old('used_for_fa', $item->used_for_fa) }}" required>
                </div>

                <div class="form-group">
                    <label for="size">Size</label>
                    <input type="text" class="form-control" id="size" name="size" value="{{ old('size', $item->size) }}" required>
                </div>

                <div class="form-group">
                    <label for="description_en">Description (EN)</label>
                    <textarea class="form-control" id="description_en" name="description_en" required>{{ old('description_en', $item->description_en) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="description_fa">Description (FA)</label>
                    <textarea class="form-control" id="description_fa" name="description_fa" required>{{ old('description_fa', $item->description_fa) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select class="form-control" id="category_id" name="category_id" required>
                        <option value="" disabled>Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name_en }} ({{ $category->name_fa }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Update Item</button>
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
        console.log("Editing item with ID: {{ $item->id }}");
    </script>
@stop
