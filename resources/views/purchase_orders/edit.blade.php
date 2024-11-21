@extends('adminlte::page')

@section('title', 'Edit Purchase Order')

@section('content_header')
    <h1>Edit Purchase Order</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('purchase_orders.update', $purchaseOrder->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="vendor_id">Vendor</label>
                    <select name="vendor_id" id="vendor_id" class="form-control" required>
                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ $vendor->id == $purchaseOrder->vendor_id ? 'selected' : '' }}>
                                {{ $vendor->company_name_en }} ({{ $vendor->company_name_fa }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="order_number">Order Number</label>
                    <input type="text" name="order_number" id="order_number" class="form-control" 
                        value="{{ $purchaseOrder->order_number }}" required>
                </div>
                
                <div class="form-group">
                    <label for="total_price">Total Price</label>
                    <input type="number" step="0.01" name="total_price" id="total_price" class="form-control" 
                        value="{{ $purchaseOrder->total_price }}" required>
                </div>

                <div class="form-group">
                    <label for="status_en">Status</label>
                    <select name="status_en" id="status_en" class="form-control" required>
                        <option value="Pending" {{ $purchaseOrder->status_en == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Completed" {{ $purchaseOrder->status_en == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Cancelled" {{ $purchaseOrder->status_en == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status_fa">Status (Persian)</label>
                    <select name="status_fa" id="status_fa" class="form-control" required>
                        <option value="در انتظار" {{ $purchaseOrder->status_fa == 'در انتظار' ? 'selected' : '' }}>در انتظار</option>
                        <option value="تکمیل شده" {{ $purchaseOrder->status_fa == 'تکمیل شده' ? 'selected' : '' }}>تکمیل شده</option>
                        <option value="لغو شده" {{ $purchaseOrder->status_fa == 'لغو شده' ? 'selected' : '' }}>لغو شده</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea name="remarks" id="remarks" class="form-control">{{ $purchaseOrder->remarks }}</textarea>
                </div>
                
                <button type="submit" class="btn btn-success">Update Order</button>
                <a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
