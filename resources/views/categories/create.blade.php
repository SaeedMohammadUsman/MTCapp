@extends('adminlte::page')

@section('title', 'Create Category')

@section('content_header')
    <h1>Create Category</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name_en">Name (EN)</label>
                    <input type="text" class="form-control" id="name_en" name="name_en" value="{{ old('name_en') }}" required>
                </div>

                <div class="form-group">
                    <label for="name_fa">Name (FA)</label>
                    <input type="text" class="form-control" id="name_fa" name="name_fa" value="{{ old('name_fa') }}" required>
                </div>

                <button type="submit" class="btn btn-success">Save Category</button>
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
        console.log("Creating a new category.");
    </script>
@stop
