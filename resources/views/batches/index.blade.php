@extends('adminlte::page')

@section('title', 'Inventory Batches')

@section('content_header')
    <h1>Inventory Batches</h1>
@stop

@section('content')
    {{-- Search and Filter Section --}}
    <div class="card">
        <div class="card-header">
            <form action="{{ route('batches.index') }}" method="GET" class="form-inline">
                <input type="text" name="batch_number" value="{{ request('batch_number') }}" class="form-control mr-2"
                    placeholder="Search by Batch Number">
                <input type="text" name="remark" value="{{ request('remark') }}" class="form-control mr-2"
                    placeholder="Search by Remark">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('batches.index') }}" class="btn btn-secondary ml-2">Clear</a>
            </form>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="card">
        <div class="card-header">
            <a href="{{ route('batches.create') }}" class="btn btn-primary">Create New Batch</a>
        </div>
        <div class="card-body">
            <table class="table table-sm table-striped table-hover table-borderless py-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Batch Number</th>
                        <th>Remark</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($batches as $batch)
                        <tr>
                            <td>{{ $batch->id }}</td>
                            <td>{{ $batch->batch_number }}</td>
                            <td>{{ $batch->remark }}</td>
                            <td>{{ $batch->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('batches.show', $batch->id) }}" class="btn btn-info btn-sm">View</a>
                                    <a href="{{ route('batches.edit', $batch->id) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    {{-- <form action="{{ route('batches.destroy', $batch->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            onclick="confirmDelete(event, 'delete-form-{{ $batch->id }}')"
                                            class="btn btn-danger btn-sm">Delete</button>
                                    </form> --}}
                                    <form action="{{ route('batches.destroy', $batch->id) }}" method="POST" style="display:inline;" id="delete-form-{{ $batch->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete(event, 'delete-form-{{ $batch->id }}')" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                    

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No batches found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{-- Pagination Links --}}
            {{ $batches->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@stop

@section('css')
    {{-- Add any extra stylesheets if needed --}}
@stop

@section('js')
    <script>
        console.log("Welcome to the inventory batch management page!");
    </script>
@stop
