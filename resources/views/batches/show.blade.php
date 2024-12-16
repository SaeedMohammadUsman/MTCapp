@extends('adminlte::page')

@section('title', 'Batch Details')

@section('content_header')
    <h1>Batch #{{ $batch->batch_number }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <!-- Batch Details -->
            <p><strong>Batch Number:</strong> {{ $batch->batch_number }}</p>
            <p><strong>Remark:</strong> {{ $batch->remark ?? 'No remarks' }}</p>
            <p><strong>Created At:</strong> {{ $batch->created_at->format('Y-m-d H:i') }}</p>
            <p><strong>Updated At:</strong> {{ $batch->updated_at->format('Y-m-d H:i') }}</p>

            <!-- Batch Items -->
            <h3>Batch Items</h3>
            @if ($batch->items->isEmpty())
                <p>No items added to this batch yet.</p>
            @else
                <table class="table table-sm table-striped table-hover table-borderless py-3">
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
                        @foreach ($batch->items as $batchItem)
                            <tr>
                                <td>{{ $batchItem->trade_name_en }} {{ $batchItem->trade_name_fa }}</td>

                                <td>{{ number_format($batchItem->pivot->cost_price, 2) }}</td>
                                <td>{{ number_format($batchItem->pivot->selling_price, 2) }}</td>
                                <td>{{ $batchItem->pivot->quantity }}</td>
                                <td>{{ $batchItem->pivot->expiration_date ? \Carbon\Carbon::parse($batchItem->pivot->expiration_date)->format('Y-m-d') : 'N/A' }}
                                </td>
                                <td>
                                
                                    <a href="{{ route('batches.items.edit', ['batch' => $batch->id, 'batch_item' => $batchItem->pivot->id]) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    
                                    <form action="{{ route('batches.items.destroy', ['batch' => $batch->id, 'batch_item' => $batchItem->pivot->id]) }}" method="POST"
                                        style="display:inline;" id="delete-form-{{ $batchItem->pivot->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            onclick="confirmDelete(event, 'delete-form-{{ $batchItem->pivot->id }}')"
                                            class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                    
                                
                                
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <!-- Action Buttons -->
            <a href="{{ route('batches.items.create', ['batch' => $batch->id]) }}" class="btn btn-primary mt-3">Add New
                Item</a>

            <a href="{{ route('batches.index') }}" class="btn btn-secondary mt-3">Back to List</a>
        </div>
    </div>
@stop

@section('css')
    {{-- Add any additional CSS styles if needed --}}
@stop

@section('js')
    <script>
        console.log("Batch details page loaded!");
    </script>
@stop
