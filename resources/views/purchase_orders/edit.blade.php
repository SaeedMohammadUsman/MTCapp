@extends('adminlte::page')

@section('title', 'Edit Purchase Order')

@section('content_header')
    <h1>Edit Purchase Order {{ $purchaseOrder->order_number }} </h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('purchase_orders.update', $purchaseOrder->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Vendor Selection --}}
                <div class="form-group">
                    <label for="vendor_id">Vendor</label>
                    <select name="vendor_id" id="vendor_id" class="form-control" required>
                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}"
                                {{ $vendor->id == $purchaseOrder->vendor_id ? 'selected' : '' }}>
                                {{ $vendor->company_name_en }} ({{ $vendor->company_name_fa }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="status_en">Status(EN)</label>
                    <select name="status_en" id="status_en" class="form-control" required>
                        <option value="Pending" data-fa="در انتظار">Pending</option>
                        <option value="Completed" data-fa="تکمیل شده">Completed</option>
                        <option value="Cancelled" data-fa="لغو شده">Cancelled</option>
                    </select>
                </div>
                
                {{-- Status Selection (Persian) --}}
                <div class="form-group">
                    <label for="status_fa">Status(FA)</label>
                    <select name="status_fa" id="status_fa" class="form-control" required>
                        <option value="در انتظار" data-en="Pending">در انتظار</option>
                        <option value="تکمیل شده" data-en="Completed">تکمیل شده</option>
                        <option value="لغو شده" data-en="Cancelled">لغو شده</option>
                    </select>
                </div>

                {{-- Remarks --}}
                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea name="remarks" id="remarks" class="form-control">{{ $purchaseOrder->remarks }}</textarea>
                </div>

                {{-- Submit and Cancel Buttons --}}
                <button type="submit" class="btn btn-success">Update Order</button>
                <a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusEn = document.getElementById('status_en');
        const statusFa = document.getElementById('status_fa');
        
        // Sync Persian status when the English status is selected
        statusEn.addEventListener('change', function() {
            const selectedStatus = statusEn.options[statusEn.selectedIndex];
            const correspondingFa = selectedStatus.getAttribute('data-fa');
            
            // Set the Persian status dropdown to the corresponding value
            for (let option of statusFa.options) {
                if (option.value === correspondingFa) {
                    option.selected = true;
                    break;
                }
            }
        });

        // Sync English status when the Persian status is selected
        statusFa.addEventListener('change', function() {
            const selectedStatus = statusFa.options[statusFa.selectedIndex];
            const correspondingEn = selectedStatus.getAttribute('data-en');
            
            // Set the English status dropdown to the corresponding value
            for (let option of statusEn.options) {
                if (option.value === correspondingEn) {
                    option.selected = true;
                    break;
                }
            }
        });
    });
</script>
@stop
