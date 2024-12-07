@extends('adminlte::page')

@section('title', 'Item Details')

@section('content_header')
    <h1>Item Details</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>{{ $item->trade_name_en }} ({{ $item->trade_name_fa }})</h3>
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> {{ $item->id }}</p>
            <p><strong>Item Code:</strong> {{ $item->item_code }}</p>
            <p><strong>Name (EN):</strong> {{ $item->trade_name_en }}</p>
            <p><strong>Name (FA):</strong> {{ $item->trade_name_fa }}</p>
            <p><strong>Used For (EN):</strong> {{ $item->used_for_en }}</p>
            <p><strong>Used For (FA):</strong> {{ $item->used_for_fa }}</p>
            <p><strong>Size:</strong> {{ $item->size }}</p>
            <p><strong>Description (EN):</strong> {{ $item->description_en }}</p>
            <p><strong>Description (FA):</strong> {{ $item->description_fa }}</p>
            <p><strong>Category:</strong> {{ $item->category->name_en ?? 'N/A' }} ({{ $item->category->name_fa ?? 'N/A' }})</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('items.index') }}" class="btn btn-secondary">Back to List</a>
            <a href="{{ route('items.edit', $item->id) }}" class="btn btn-warning">Edit</a>
            
            <form action="{{ route('items.destroy', $item->id) }}" method="POST" id="delete-form-{{ $item->id }}" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDelete(event, 'delete-form-{{ $item->id }}')" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
@stop
