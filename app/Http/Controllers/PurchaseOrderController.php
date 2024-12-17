<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Vendor;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of purchase orders with optional filtering.
     */
    public function index(Request $request)
    {
        $vendors = Vendor::all();

        $purchaseOrders = PurchaseOrder::when($request->vendor_id, function ($query) use ($request) {
            $query->where('vendor_id', $request->vendor_id);
        })
            ->when($request->status, function ($query) use ($request) {
                $query->where('status_en', $request->status);
            })
            ->when($request->filter === 'trashed', function ($query) {
                $query->onlyTrashed(); // Show trashed records when 'trashed' is selected
            })
            ->when($request->filter === 'active', function ($query) {
                $query->whereNull('deleted_at'); // Only active records
            })
            ->paginate(10);

        // Add a flag to check if there are trashed records
        $hasTrashed = PurchaseOrder::onlyTrashed()->exists();

        return view('purchase_orders.index', compact('vendors', 'purchaseOrders', 'hasTrashed'));
    }



    /**
     * Show the form for creating a new purchase order.
     */
    public function create()
    {
        $vendors = Vendor::all();
        return view('purchase_orders.create', compact('vendors'));
    }

    /**
     * Store a newly created purchase order in storage.
     */
    public function store(Request $request)
    {
        $request->validate([

            'vendor_id' => 'required|exists:vendors,id',
            'remarks' => 'nullable|string',
        ]);

        $purchaseOrder = PurchaseOrder::create([
            'vendor_id' => $request->vendor_id,
            'status_en' => 'Pending', // Status is always Pending when a new order is created
            'status_fa' => 'در انتظار', // Persian equivalent of "Pending"
            'remarks' => $request->remarks,
        ]);

        return redirect()
            ->route('purchase_orders.items.create', $purchaseOrder->id)
            ->with('success', 'Purchase order created successfully. Now add items.');
    }

    /**
     * Display the specified purchase order and its items.
     */
    public function show($id)
    {
        $purchaseOrder = PurchaseOrder::with('items.item')->findOrFail($id);
        return view('purchase_orders.show', compact('purchaseOrder'));
    }

    /**
     * Show the form for editing the specified purchase order.
     */
    public function edit($id)
    {
        $purchaseOrder = PurchaseOrder::with('items')->findOrFail($id);
        $vendors = Vendor::all();
        return view('purchase_orders.edit', compact('purchaseOrder', 'vendors'));
    }

    /**
     * Update the specified purchase order in storage.
     */
    public function update(Request $request, $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        $request->validate([

            'vendor_id' => 'required|exists:vendors,id',

            'status_en' => 'required',
            'status_fa' => 'required',
        ]);

        $purchaseOrder->update($request->only([
            'vendor_id',
            'status_en',
            'status_fa',
            'remarks'
        ]));

        return redirect()->route('purchase_orders.index')->with('success', 'Purchase order updated successfully.');
    }

    /**
     * Remove the specified purchase order from storage.
     */
    public function destroy($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->delete();

        return redirect()->route('purchase_orders.index')->with('success', 'Purchase order deleted successfully.');
    }

    public function restore($id)
    {
        $order = PurchaseOrder::withTrashed()->findOrFail($id);
        $order->restore();

        return redirect()->route('purchase_orders.index')->with('success', 'Purchase order restored successfully.');
    }
}
