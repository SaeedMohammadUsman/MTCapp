@extends('adminlte::page')

@section('title', 'Create Batch')

@section('content_header')
    <h1>Create Batch</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('batches.store') }}" method="POST">
                @csrf

                {{-- Batch Number (Read-Only) --}}
                <div class="form-group">
                    <label for="batch_number">Batch Number</label>
                    <input type="text" name="batch_number" class="form-control" value="{{ old('batch_number', 'BATCH' . str_pad(($latestBatchNumber ?? 0) + 1, 3, '0', STR_PAD_LEFT)) }}" >
                </div>
                {{-- Remark --}}
                <div class="form-group">
                    <label for="remark">Remark</label>
                    <input type="text" name="remark" class="form-control" value="{{ old('remark') }}">
                </div>

                <button type="submit" class="btn btn-success">Save Batch</button>
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
        console.log("Creating a new inventory batch.");
    </script>
@stop
