@extends('adminlte::page')

@section('title', 'Create Received Good')

@section('content_header')
    <h1>Create Received Good</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('received_goods.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Vendor Selection -->
                <div class="form-group">
                    <label for="vendor_id">Vendor</label>
                    <select name="vendor_id" id="vendor_id" class="form-control" required>
                        <option value="" disabled selected>Select Vendor</option>
                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}">
                                {{ $vendor->company_name_en }} ({{ $vendor->company_name_fa }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Remarks -->
                <div class="form-group">
                    <label for="remark">Remarks</label>
                    <textarea name="remark" id="remark" class="form-control">{{ old('remark') }}</textarea>
                </div>

                <!-- Bill Attachment -->
                <div class="form-group">
                    <label for="bill_attachment">Bill Attachment (PDF/JPG/PNG)</label>
                    <input type="file" name="bill_attachment" id="bill_attachment" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png">
                </div>

                <!-- Submit and Cancel Buttons -->
                <button type="submit" class="btn btn-success">Create</button>
                <a href="{{ route('received_goods.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
