@extends('adminlte::page')
@section('title', 'Add Batch Items')

@section('content_header')
    <h1>Add Items to Batch #{{ $batch->batch_number }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form id="batchItemsForm" method="POST" action="{{ route('batches.items.store', ['batch' => $batch->id]) }}">

                @csrf
                <input type="hidden" name="batch_id" value="{{ $batch->id }}">
                <div id="batch-items-container">
                    <div class="batch-item-row-template d-none">
                        <div class="batch-item-row">
                            <div class="form-group">
                                <label>Trade Name</label>
                                <select name="items[0][trade_name]" class="form-control" required>
                                    <option value="">Select Trade Name</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->trade_name_en }}|{{ $item->trade_name_fa }}">
                                            {{ $item->trade_name_en }} (EN) / {{ $item->trade_name_fa }} (FA)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                            <div class="form-group">
                                <label>Cost Price</label>
                                <input type="number" name="items[0][cost_price]" class="form-control" step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label>Selling Price</label>
                                <input type="number" name="items[0][selling_price]" class="form-control" step="0.01" required>
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
                <button type="button" class="btn btn-primary" id="add-batch-item-btn">Add Another Item</button>
                <button type="button" class="btn btn-success" id="save-batch-items-btn">Save Items</button>
                <a href="{{ route('batches.index') }}" class="btn btn-secondary">Back to Batches</a>
            </form>

            <h3 class="mt-4">Items to Add</h3>
            <table class="table table-bordered" id="batch-items-table">
                <thead>
                    <tr>
                        <th>Trade Name</th>
                        <th>Cost Price</th>
                        <th>Selling Price</th>
                        <th>Quantity</th>
                        <th>Expiration Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dynamically added batch items will appear here -->
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
<script src="{{ asset('js/batchItems.js') }}"></script>

<script>
    window.routes = {
        saveBatchItems: "{{ route('batches.items.store', ['batch' => $batch->id]) }}",
        index: "{{ route('batches.index') }}"
    };
</script>
@stop
