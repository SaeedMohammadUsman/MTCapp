@extends('adminlte::page')

@section('title', 'Department Details')

@section('content_header')
    <h1>Department Details</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>{{ $department->title_en }} ({{ $department->title_fa }})</h3>
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> {{ $department->id }}</p>
            <p><strong>Title (EN):</strong> {{ $department->title_en }}</p>
            <p><strong>Title (FA):</strong> {{ $department->title_fa }}</p>
            <p><strong>Position:</strong> {{ ucfirst($department->position) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($department->status) }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('departments.index') }}" class="btn btn-secondary">Back to List</a>
            <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-warning">Edit</a>
          
            <form action="{{ route('departments.destroy', $department->id) }}" method="POST" id="delete-form-{{ $department->id }}" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDelete(event, 'delete-form-{{ $department->id }}')" class="btn btn-danger ">Delete</button>
            </form> 
            
        </div>
    </div>
@stop
