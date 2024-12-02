@extends('adminlte::page')

@section('title', 'Edit Department')

@section('content_header')
    <h1>Edit Department</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('departments.update', $department->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="title_en">Title (EN)</label>
                    <input type="text" class="form-control" id="title_en" name="title_en" value="{{ old('title_en', $department->title_en) }}" required>
                </div>

                <div class="form-group">
                    <label for="title_fa">Title (FA)</label>
                    <input type="text" class="form-control" id="title_fa" name="title_fa" value="{{ old('title_fa', $department->title_fa) }}" required>
                </div>
                <div class="form-group">
                    <label for="position">Position</label>
                    <select class="form-control" id="position" name="position" required>
                        <option value="salesman" {{ old('position', $department->position) == 'salesman' ? 'selected' : '' }}>Salesman</option>
                        <option value="visitor" {{ old('position', $department->position) == 'visitor' ? 'selected' : '' }}>Visitor</option>
                        <option value="cook" {{ old('position', $department->position) == 'cook' ? 'selected' : '' }}>Cook</option>
                        <option value="manager" {{ old('position', $department->position) == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="administrator" {{ old('position', $department->position) == 'administrator' ? 'selected' : '' }}>Administrator</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="active" {{ old('status', $department->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $department->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="archived" {{ old('status', $department->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Update Department</button>
                <a href="{{ route('departments.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@stop

@section('css')
    {{-- Add any extra stylesheets if needed --}}
@stop

@section('js')
    <script>
        console.log("Editing department with ID: {{ $department->id }}");
    </script>
@stop
