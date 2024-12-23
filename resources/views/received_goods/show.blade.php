@extends('adminlte::page')

@section('title', 'Received Good Details')

@section('content_header')
    <h1>Received Good #{{ $receivedGood->id }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <!-- Received Good Details -->
            <p><strong>Vendor (EN):</strong> {{ $receivedGood->vendor->company_name_en }}</p>
            <p><strong>Vendor (FA):</strong> {{ $receivedGood->vendor->company_name_fa }}</p>
            <p><strong>Remarks:</strong> {{ $receivedGood->remark ?? 'No remarks' }}</p>
            <p><strong>Status (EN):</strong>
                @if ($receivedGood->is_finalized)
                    <span class="badge bg-success">Completed</span>
                @else
                    <span class="badge bg-warning">Pending</span>
                @endif
            </p>

            <!-- Attachment -->
            <p><strong>Attachment:</strong>
                @if ($receivedGood->bill_attachment)
                    <a href="{{ asset('storage/' . $receivedGood->bill_attachment) }}" target="_blank">View Attachment</a>
                @else
                    No attachment
                @endif
            </p>

            <!-- Received Good Items -->
            <h3>Received Good Items</h3>
            @if ($receivedGood->details->isEmpty())
                <p>No items received for this good yet.</p>
            @else
                <table class="table table-hover table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Used For (EN)</th>
                            <th>Size</th>
                            <th>Vendor Price</th>
                            <th>Quantity</th>
                            <th>Expiration Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($receivedGood->details as $detail)
                            <tr>
                                <td>{{ $detail->item->trade_name_en }} {{ $detail->item->trade_name_fa }} </td>
                                <td>{{ $detail->item->used_for_en }}</td>
                                <td>{{ $detail->item->size }}</td>
                                <td>{{ number_format($detail->vendor_price, 2) }}</td>
                                <td>{{ $detail->quantity }}</td>
                                {{-- <td>{{ $detail->expiration_date ? $detail->expiration_date->format('Y-m-d') : 'N/A' }}</td>
                                <td> --}}
                                <td>{{ $detail->expiration_date ? \Carbon\Carbon::parse($detail->expiration_date)->format('Y-m-d') : 'N/A' }}
                                </td>

                                <td>
                                    <div class="btn-group text-center">
                                        <!-- Edit Action -->
                                        {{-- <a href="{{ route('received_goods.details.edit', [$receivedGood->id, $detail->id]) }}"
                                            class="btn btn-warning btn-sm">
                                            Edit
                                        </a> --}}
                                        <a href="{{ $receivedGood->is_finalized ? '#' : route('received_goods.details.edit', [$receivedGood->id, $detail->id]) }}"
                                            class="btn btn-warning btn-sm {{ $receivedGood->is_finalized ? 'disabled' : '' }}"
                                            @if ($receivedGood->is_finalized) onclick="event.preventDefault();"
                                                style="pointer-events: none; opacity: 0.6;" @endif>
                                             Edit
                                         </a>
                                         
                                        <!-- Delete Action -->
                                        {{-- <form id="delete-form-{{ $detail->id }}"
                                            action="{{ route('received_goods.details.destroy', [$receivedGood->id, $detail->id]) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="confirmDelete(event, 'delete-form-{{ $detail->id }}')">
                                                Delete
                                            </button>
                                        </form> --}}
                                        <form id="delete-form-{{ $detail->id }}"
                                            action="{{ route('received_goods.details.destroy', [$receivedGood->id, $detail->id]) }}"
                                            method="POST" style="display:inline;">
                                          @csrf
                                          @method('DELETE')
                                          <button type="button"
                                                  class="btn btn-danger btn-sm {{ $receivedGood->is_finalized ? 'disabled' : '' }}"
                                                  @if ($receivedGood->is_finalized) onclick="event.preventDefault();"
                                                  style="pointer-events: none; opacity: 0.6;"
                                              @else
                                                  onclick="confirmDelete(event, 'delete-form-{{ $detail->id }}')" @endif>
                                              Delete
                                          </button>
                                      </form>
                                        
                                        
                                    </div>
                                </td>


                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <!-- Add New Item Button -->
            {{-- <a href="{{ route('received_goods.details.create', $receivedGood->id) }}" class="btn btn-primary mt-3">
                Add New Item
            </a> --}}
            
            <a href="{{ $receivedGood->is_finalized ? '#' : route('received_goods.details.create', $receivedGood->id) }}"
                class="btn btn-primary mt-3 {{ $receivedGood->is_finalized ? 'disabled' : '' }}"
                @if ($receivedGood->is_finalized) onclick="event.preventDefault();"
                    style="pointer-events: none; opacity: 0.6;" @endif>
                 Add New Item
             </a>

            <!-- Action Buttons -->
            <a href="{{ route('received_goods.index') }}" class="btn btn-secondary mt-3">Back to List</a>
        </div>
    </div>
@stop
