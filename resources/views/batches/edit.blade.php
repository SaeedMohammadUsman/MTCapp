@extends('adminlte::page')

@section('title', 'Edit Batch')

@section('content_header')
    <h1>Edit Batch</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('batches.update', $batch->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="batch_number">Batch Number</label>
                    <input type="text" class="form-control" id="batch_number" name="batch_number"
                        value="{{ old('batch_number', $batch->batch_number) }}" required>
                </div>

                <div class="form-group">
                    <label for="remark">Remark</label>
                    <input type="text" class="form-control" id="remark" name="remark" value="{{ old('remark', $batch->remark) }}" required>
                </div>

                <div class="form-group">
                    <label for="created_at">Created At</label>
                    <input type="text" class="form-control" id="created_at" name="created_at" value="{{ $batch->created_at->format('Y-m-d H:i') }}" disabled>
                </div>

                <button type="submit" class="btn btn-success">Update Batch</button>
                <a href="{{ route('batches.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@stop

@section('css')
    {{-- Add any extra stylesheets if needed --}}
@stop

@section('js')
    <script>
        console.log("Editing batch with ID: {{ $batch->id }}");
    </script>
@stop
