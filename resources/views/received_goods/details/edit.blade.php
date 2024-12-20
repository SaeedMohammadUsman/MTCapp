@extends('adminlte::page')

@section('title', 'Edit Received Good Detail')

@section('content_header')
    <h1>Edit Received Good Detail for Received Good #{{ $receivedGoodDetail->receivedGood->id }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form
                action="{{ route('received_goods.details.update', ['received_good' => $receivedGoodDetail->received_good_id, 'detail' => $receivedGoodDetail->id]) }}"
                method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Item</label>
                    <select name="item_id" class="form-control" required>
                        <option value="" disabled
                            {{ old('item_id', $receivedGoodDetail->item->id) == '' ? 'selected' : '' }}>
                            Select Item
                        </option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}"
                                {{ old('item_id', $receivedGoodDetail->item_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->trade_name_en }} | {{ $item->trade_name_fa }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Vendor Price</label>
                    <input type="number" name="vendor_price" class="form-control" step="0.01"
                        value="{{ old('vendor_price', $receivedGoodDetail->vendor_price) }}" required>
                </div>

                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="quantity" class="form-control" min="1"
                        value="{{ old('quantity', $receivedGoodDetail->quantity) }}" required>
                </div>

                <div class="form-group">
                    <label>Expiration Date</label>
                    <input type="date" name="expiration_date" class="form-control"
                        value="{{ old('expiration_date', $receivedGoodDetail->expiration_date) }}">
                </div>

                <button type="submit" class="btn btn-success">Update</button>
                <a href="{{ route('received_goods.show', $receivedGoodDetail->received_good_id) }}"
                    class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script>
        console.log("Editing received good detail with ID: {{ $receivedGoodDetail->id }}");
    </script>
@stop
