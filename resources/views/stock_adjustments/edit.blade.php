@extends('adminlte::page')

@section('title', 'Edit Stock Adjustment')

@section('content_header')
    <h1>Edit Stock Adjustment</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('stock_adjustments.update', $stockAdjustment->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="item_id">Item</label>
                    <select name="item_id" id="item_id" class="form-control" required>
                        @foreach ($inventoryItems as $item)
                            <option value="{{ $item->id }}" {{ $item->id == $stockAdjustment->item_id ? 'selected' : '' }}>
                                {{ $item->item_name_en }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="adjustment_type_en">Adjustment Type</label>
                    <select name="adjustment_type_en" id="adjustment_type_en" class="form-control" required>
                        <option value="damaged" {{ $stockAdjustment->adjustment_type_en == 'damaged' ? 'selected' : '' }}>
                            Damaged
                        </option>
                        <option value="returns" {{ $stockAdjustment->adjustment_type_en == 'returns' ? 'selected' : '' }}>
                            Returns
                        </option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="adjustment_type_fa">Adjustment Type (Persian)</label>
                    <select name="adjustment_type_fa" id="adjustment_type_fa" class="form-control" required>
                        <option value="خرابی" {{ $stockAdjustment->adjustment_type_fa == 'خرابی' ? 'selected' : '' }}>خرابی</option>
                        <option value="بازگشت" {{ $stockAdjustment->adjustment_type_fa == 'بازگشت' ? 'selected' : '' }}>بازگشت</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" 
                        value="{{ $stockAdjustment->quantity }}" required>
                </div>
                <div class="form-group">
                    <label for="reason_en">Reason</label>
                    <textarea name="reason_en" id="reason_en" class="form-control">{{ $stockAdjustment->reason_en }}</textarea>
                </div>
                <div class="form-group">
                    <label for="reason_fa">Reason (Persian)</label>
                    <textarea name="reason_fa" id="reason_fa" class="form-control">{{ $stockAdjustment->reason_fa }}</textarea>
                </div>
                <button type="submit" class="btn btn-success">Update Item</button>
                <a href="{{ route('stock_adjustments.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
