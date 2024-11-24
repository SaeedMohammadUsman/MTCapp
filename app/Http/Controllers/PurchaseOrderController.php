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
        ->paginate(10);

        return view('purchase_orders.index', compact('vendors', 'purchaseOrders'));
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
            'order_number' => 'required|unique:purchase_orders,order_number|max:50',
            'vendor_id' => 'required|exists:vendors,id',
            'status_en' => 'required',
            'status_fa' => 'required',
        ]);

        $purchaseOrder = PurchaseOrder::create([
            'order_number' => $request->order_number,
            'vendor_id' => $request->vendor_id,
            'total_price' => 0, // Default to 0
            'status_en' => $request->status_en,
            'status_fa' => $request->status_fa,
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
        $purchaseOrder = PurchaseOrder::with('items.inventoryItem')->findOrFail($id);
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
            'order_number' => "required|max:50|unique:purchase_orders,order_number,$id",
            'vendor_id' => 'required|exists:vendors,id',
            'total_price' => 'required|numeric|min:0',
            'status_en' => 'required',
            'status_fa' => 'required',
        ]);

        $purchaseOrder->update($request->only([
            'order_number', 'vendor_id', 'total_price', 'status_en', 'status_fa', 'remarks'
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
}
