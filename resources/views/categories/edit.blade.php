@extends('adminlte::page')

@section('title', 'Edit Category')

@section('content_header')
    <h1>Edit Category</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name_en">Name (EN)</label>
                    <input type="text" class="form-control" id="name_en" name="name_en" value="{{ old('name_en', $category->name_en) }}" required>
                </div>

                <div class="form-group">
                    <label for="name_fa">Name (FA)</label>
                    <input type="text" class="form-control" id="name_fa" name="name_fa" value="{{ old('name_fa', $category->name_fa) }}" required>
                </div>

                <button type="submit" class="btn btn-success">Update Category</button>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@stop

@section('css')
    {{-- Add any extra stylesheets if needed --}}
@stop

@section('js')
    <script>
        console.log("Editing category with ID: {{ $category->id }}");
    </script>
@stop
