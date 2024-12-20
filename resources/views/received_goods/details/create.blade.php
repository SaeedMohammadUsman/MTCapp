@extends('adminlte::page')
@section('title', 'Add Received Goods Details')

@section('content_header')
    <h1>Add Items to Received Good #{{ $receivedGood->id }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            {{-- <form id="receivedGoodsDetailsForm" method="POST" action="{{ route('received_goods.details.store', ['receivedGood' => $receivedGood->id]) }}"> --}}
                <form id="receivedGoodsDetailsForm" method="POST" action="{{ route('received_goods.details.store', ['received_good' => $receivedGood->id]) }}">
                @csrf

                <div id="received-goods-items-container">
                    <div class="received-good-item-row-template d-none">
                        <div class="received-good-item-row">
                            <div class="form-group">
                                <label>Item</label>
                                <select name="items[0][item_id]" class="form-control" required>
                                    <option value="">Select Item</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }} | {{ $item->trade_name_en }} | {{ $item->trade_name_fa }}">
                                            {{ $item->trade_name_en }} | {{ $item->trade_name_fa }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Vendor Price</label>
                                <input type="number" name="items[0][vendor_price]" class="form-control" step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="number" name="items[0][quantity]" class="form-control" value="1" min="1" required>
                            </div>
                            <div class="form-group">
                                <label>Expiration Date</label>
                                <input type="date" name="items[0][expiration_date]" class="form-control">
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </form>

            <h3 class="mt-4">Items to Add</h3>
            <table class="table table-bordered" id="received-goods-items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Vendor Price</th>
                        <th>Quantity</th>
                        <th>Expiration Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dynamically added items will appear here -->
                </tbody>
            </table>
            <button type="button" class="btn btn-primary" id="add-received-good-item-btn">Add Another Item</button>
            <button type="button" class="btn btn-success" id="save-received-good-items-btn">Save Items</button>
            <a href="{{ route('received_goods.index') }}" class="btn btn-secondary">Back to Received Goods</a>
        </div>
    </div>
@stop

@section('js')
<script>
    window.routes = {
        saveReceivedGoodItems: "{{ route('received_goods.details.store', ['received_good' => $receivedGood->id]) }}",
        index: "{{ route('received_goods.index') }}"
    };
</script>
<script src="{{ asset('js/receivedGoodItems.js') }}"></script>
@stop


{{-- @section('js')
<script>
    window.routes = {
        // saveReceivedGoodItems: "{{ route('received_goods.details.store', ['receivedGood' => $receivedGood->id]) }}",
        saveReceivedGoodItems: "{{ route('received_goods.details.store', ['received_good' => $receivedGood->id]) }}",
        index: "{{ route('received_goods.index') }}"
    };
</script>
<script src="{{ asset('js/receivedGoodItems.js') }}"></script>
@stop --}}
