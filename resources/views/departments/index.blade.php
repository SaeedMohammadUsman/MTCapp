
@extends('adminlte::page')

@section('title', 'Departments')

@section('content_header')
    {{-- <h1>Departments</h1> --}}
    {{-- <h1>{{ __('menu.departments') }}</h1> --}}
    <h1>{{ trans('menu.departments') }}</h1>

    {{-- <h1>((__menu.departemnt))</h1> --}}
    
    
    {{-- {{ app()->getLocale() }} --}}

@stop

@section('content')
    {{-- Search Section --}}
    <div class="card">
        <div class="card-header">
            <form action="{{ route('departments.index') }}" method="GET" class="form-inline">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2"
                    placeholder="Search by ID or Title">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="{{ route('departments.index') }}" class="btn btn-secondary ml-2">Clear</a>
            </form>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="card">
        <div class="card-header">
            <a href="{{ route('departments.create') }}" class="btn btn-primary">Create New Department</a>
        </div>
        <div class="card-body">
            {{-- <table class="table  table-hover table-striped table-sm"> --}}
                <table class="table table-sm table-striped table-hover table-borderless">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title (EN)</th>
                        <th>Title (FA)</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($departments as $department)
                        <tr>
                            <td>{{ $department->id }}</td>
                            <td>{{ $department->title_en }}</td>
                            <td>{{ $department->title_fa }}</td>
                            <td>{{ ucfirst($department->position) }}</td>  
                            <td>{{ ucfirst($department->status) }}</td>
                            <td>
                                <div class="btn-group">
                                <a href="{{ route('departments.show', $department->id) }}"
                                    class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('departments.edit', $department->id) }}"
                                    class="btn btn-warning btn-sm">Edit</a>
                              
                                <form action="{{ route('departments.destroy', $department->id) }}" method="POST" id="delete-form-{{ $department->id }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete(event, 'delete-form-{{ $department->id }}')" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No departments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{-- Pagination Links --}}
            {{ $departments->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@stop

@section('css')
    {{-- Add any extra stylesheets if needed --}}
@stop

@section('js')

@section('js')
   
@stop

    <script>
        console.log("Welcome to the departments management page!");
    </script>
@stop
