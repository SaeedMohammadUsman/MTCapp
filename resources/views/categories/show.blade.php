@extends('adminlte::page')

@section('title', 'Category Details')

@section('content_header')
    <h1>Category Details</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>{{ $category->name_en }} ({{ $category->name_fa }})</h3>
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> {{ $category->id }}</p>
            <p><strong>Name (EN):</strong> {{ $category->name_en }}</p>
            <p><strong>Name (FA):</strong> {{ $category->name_fa }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back to List</a>
            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning">Edit</a>
            
            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" id="delete-form-{{ $category->id }}" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDelete(event, 'delete-form-{{ $category->id }}')" class="btn btn-danger ">Delete</button>
            </form> 
        </div>
    </div>
@stop
