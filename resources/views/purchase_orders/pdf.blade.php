@extends('layouts.pdf')

@section('content')
    <div class="container">
        <div class="header">
            <h3>Purchase Order</h3>
        </div>

        <!-- Wrapper Row -->
        <table class="table-wrapper">
            <tr>
                <!-- Left Container -->
                <td>
                    <p><strong>To:</strong> <span class="text-muted">{{ $purchaseOrder->vendor->company_name_en }}</span></p>
                    <p><strong>Address:</strong> <span class="text-muted">{{ $purchaseOrder->vendor->address_en }}</span></p>
                    <p><strong>Email:</strong> <span class="text-muted">{{ $purchaseOrder->vendor->email }}</span></p>
                </td>
        
                <!-- Right Container -->
                <td class="text-right">
                    <p><strong>P.O. Number:</strong> <span class="text-muted">{{ preg_replace('/^[A-Za-z-]+/', '', $purchaseOrder->order_number) }}</span></p>


                    <p><strong>Requested By:</strong> <span class="text-muted">MTC</span></p>
                    <p><strong>Date:</strong> <span class="text-muted">{{ now()->format('d M, Y') }}</span></p>
                </td>
            </tr>
        </table>

        <!-- Items Table Section -->
        <div class="row mt-4">
            <div class="col-12">
                <table class="table  table-sm">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Trade Name</th>
                            <th>Used For</th>
                            <th>Size</th>
                            <th>Quantity</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchaseOrder->items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->item->trade_name_en }}</td>
                                <td>{{ $item->item->used_for_en }}</td>
                                <td>{{ $item->item->size }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->remarks ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer Section -->
        <table class="footer-table">
            <tr>
                <!-- Left Container -->
                <td>
                    <p><strong>Notes:</strong> <span class="text-muted">{{ $purchaseOrder->remarks ?? 'No additional notes' }}</span></p>
                    <p><strong>Authorized By:</strong></p>
                    <div class="signature">
                        <p>Signature</p>
                    </div>
                </td>

                <!-- Right Container -->
                <td class="text-right">
                    <p><strong>Total Items:</strong> <span class="highlight">{{ $purchaseOrder->items->count() }}</span></p>
                </td>
            </tr>
        </table>
    </div>
@endsection
