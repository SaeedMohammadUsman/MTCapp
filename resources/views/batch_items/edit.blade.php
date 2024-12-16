@extends('adminlte::page')

@section('title', 'Edit Batch Item')

@section('content_header')
    <h1>Edit Item for Batch #{{ $batchItem->inventoryBatch->batch_number }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form
                action="{{ route('batches.items.update', ['batch' => $batchItem->inventory_batch_id, 'batch_item' => $batchItem->id]) }}"
                method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Trade Name</label>
                    <select name="items[0][trade_name]" class="form-control" required>
                        <option value="" disabled
                            {{ old('items.0.trade_name', $batchItem->item->trade_name_en . '|' . $batchItem->item->trade_name_fa) == '' ? 'selected' : '' }}>
                            Select Trade Name
                        </option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}|{{ $item->trade_name_en }}|{{ $item->trade_name_fa }}"
                                {{ old('items.0.trade_name', $batchItem->item->id . '|' . $batchItem->item->trade_name_en . '|' . $batchItem->item->trade_name_fa) == $item->id . '|' . $item->trade_name_en . '|' . $item->trade_name_fa ? 'selected' : '' }}>
                                {{ $item->trade_name_en }} | {{ $item->trade_name_fa }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Cost Price</label>
                    <input type="number" name="items[0][cost_price]" class="form-control" step="0.01"
                        value="{{ old('items.0.cost_price', $batchItem->cost_price) }}" required>
                </div>

                <div class="form-group">
                    <label>Selling Price</label>
                    <input type="number" name="items[0][selling_price]" class="form-control" step="0.01"
                        value="{{ old('items.0.selling_price', $batchItem->selling_price) }}" required>
                </div>

                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="items[0][quantity]" class="form-control" min="1"
                        value="{{ old('items.0.quantity', $batchItem->quantity) }}" required>
                </div>

                <div class="form-group">
                    <label>Expiration Date</label>
                    <input type="date" name="items[0][expiration_date]" class="form-control"
                        value="{{ old('items.0.expiration_date', $batchItem->expiration_date) }}">
                </div>

                <button type="submit" class="btn btn-success">Update Item</button>
                <a href="{{ route('batches.index') }}" class="btn btn-secondary">Back to Batches</a>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script>
        console.log("Editing batch item with ID: {{ $batchItem->id }}");
    </script>
@stop
