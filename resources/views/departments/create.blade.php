@extends('adminlte::page')

@section('title', 'Create Department')

@section('content_header')
    <h1>Create Department</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('departments.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="title_en">Title (EN)</label>
                    <input type="text" class="form-control" id="title_en" name="title_en" value="{{ old('title_en') }}" required>
                </div>

                <div class="form-group">
                    <label for="title_fa">Title (FA)</label>
                    <input type="text" class="form-control" id="title_fa" name="title_fa" value="{{ old('title_fa') }}" required>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Create Department</button>
            </form>
        </div>
    </div>
@stop

@section('css')
    {{-- Add any extra stylesheets if needed --}}
@stop

@section('js')
    <script>
        console.log("Creating a new department.");
    </script>
@stop
