@extends('adminlte::page')

@section('title', 'Create Stock Adjustment')

@section('content_header')
    <h1>Create Stock Adjustment</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('stock_adjustments.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="item_id">Item</label>
                    <select name="item_id" id="item_id" class="form-control" required>
                        <option value="" disabled selected>Select Item</option>
                        @foreach ($inventoryItems as $item)
                            <option value="{{ $item->id }}">{{ $item->item_name_en }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="adjustment_type_en">Adjustment Type</label>
                    <select name="adjustment_type_en" id="adjustment_type_en" class="form-control" required>
                        <option value="damaged">Damaged</option>
                        <option value="returns">Returns</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="adjustment_type_fa">Adjustment Type (Persian)</label>
                    <select name="adjustment_type_fa" id="adjustment_type_fa" class="form-control" required>
                        <option value="خرابی">خرابی</option>
                        <option value="بازگشت">بازگشت</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="reason_en">Reason</label>
                    <textarea name="reason_en" id="reason_en" class="form-control"></textarea>
                </div>
               
                <div class="form-group">
                    <label for="reason_fa">Reason (Persian)</label>
                    <textarea name="reason_fa" id="reason_fa" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-success">Save Item</button>
                <a href="{{ route('stock_adjustments.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
