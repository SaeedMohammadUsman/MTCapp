@extends('adminlte::page')

@section('title', 'Edit Received Goods')

@section('content_header')
    <h1>Edit Received Goods</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('received_goods.update', $receivedGood->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Vendor Selection --}}
                <div class="form-group">
                    <label for="vendor_id">Vendor</label>
                    <select name="vendor_id" id="vendor_id" class="form-control" required>
                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ $vendor->id == $receivedGood->vendor_id ? 'selected' : '' }}>
                                {{ $vendor->company_name_en }} ({{ $vendor->company_name_fa }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Remark --}}
                <div class="form-group">
                    <label for="remark">Remark</label>
                    <textarea name="remark" id="remark" class="form-control">{{ $receivedGood->remark }}</textarea>
                </div>

                {{-- Bill Attachment --}}
                <div class="form-group">
                    <label for="bill_attachment">Bill Attachment</label>
                    @if ($receivedGood->bill_attachment)
                        <div>
                            <a href="{{ asset('storage/' . $receivedGood->bill_attachment) }}" target="_blank">
                                View Current Attachment
                            </a>
                        </div>
                    @endif
                    <input type="file" name="bill_attachment" id="bill_attachment" class="form-control">
                </div>

                {{-- Status Selection --}}
                <div class="form-group">
                    <label for="is_finalized">Status</label>
                    <select name="is_finalized" id="is_finalized" class="form-control" required>
                        <option value="1" {{ $receivedGood->is_finalized ? 'selected' : '' }}>Completed</option>
                        <option value="0" {{ !$receivedGood->is_finalized ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>

                {{-- Submit and Cancel Buttons --}}
                <button type="submit" class="btn btn-success">Update</button>
                <a href="{{ route('received_goods.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop