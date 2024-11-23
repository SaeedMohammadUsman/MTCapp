<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Vendor;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;


class PurchaseOrderController extends Controller
{
    // public function index(Request $request)
    // {
    //     $vendors = Vendor::all();
    //     $query = PurchaseOrder::with('vendor', 'items');

    //     if ($request->vendor_id) {
    //         $query->where('vendor_id', $request->vendor_id);
    //     }

    //     if ($request->status) {
    //         $query->where(function ($q) use ($request) {
    //             $q->where('status_en', $request->status)
    //               ->orWhere('status_fa', $request->status);
    //         });
    //     }

    //     if ($request->start_date && $request->end_date) {
    //         $query->whereBetween('order_date', [$request->start_date, $request->end_date]);
    //     }

    //     $purchaseOrders = $query->paginate(10);

    //     return view('purchase_orders.index', compact('purchaseOrders'));
    // }
   // Make sure to import the Vendor model at the top of the controller

public function index(Request $request)
{
    // Fetch vendors to pass to the view for the search dropdown
    $vendors = Vendor::all();

    // Logic to filter and get the purchase orders
    $purchaseOrders = PurchaseOrder::when($request->vendor_id, function ($query) use ($request) {
        return $query->where('vendor_id', $request->vendor_id);
    })
    ->when($request->status, function ($query) use ($request) {
        return $query->where('status_en', $request->status);
    })->paginate(10); // Adjust pagination as needed

    // Pass both vendors and purchaseOrders to the view
    return view('purchase_orders.index', compact('vendors', 'purchaseOrders'));
}


    // public function create()
    // {
    //     $vendors = Vendor::all();
    //     return view('purchase_orders.create', compact('vendors'));
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'order_number' => 'required|unique:purchase_orders,order_number|max:50',
    //         'vendor_id' => 'required|exists:vendors,id',
    //         'total_price' => 'required|numeric|min:0',
    //         'status_en' => 'required',
    //         'status_fa' => 'required',
    //     ]);

    //     $purchaseOrder = PurchaseOrder::create($request->only([
    //         'order_number', 'vendor_id', 'total_price', 'status_en', 'status_fa', 'remarks'
    //     ]));

    //     return redirect()->route('purchase_orders.index')->with('success', 'Purchase order created successfully.');
    // }
    
    public function create()
{
    $vendors = Vendor::all();
    return view('purchase_orders.create', compact('vendors'));
}

public function store(Request $request)
{
    $request->validate([
        'order_number' => 'required|unique:purchase_orders,order_number|max:50',
        'vendor_id' => 'required|exists:vendors,id',
        'status_en' => 'required',
        'status_fa' => 'required',
    ]);

    // Create purchase order with default total_price = 0
    $purchaseOrder = PurchaseOrder::create([
        'order_number' => $request->order_number,
        'vendor_id' => $request->vendor_id,
        'total_price' => 0, // Default to 0
        'status_en' => $request->status_en,
        'status_fa' => $request->status_fa,
        'remarks' => $request->remarks,
    ]);

    // Redirect to the Add Items page for the created purchase order
    return redirect()->route('purchase_orders.items.create', $purchaseOrder->id)
                     ->with('success', 'Purchase order created successfully. Now add items.');
                     
                  
}


    public function show($id)
    {
        $purchaseOrder = PurchaseOrder::with('items.inventoryItem')->findOrFail($id);
        return view('purchase_orders.show', compact('purchaseOrder'));
    }

    public function edit($id)
    {
        $purchaseOrder = PurchaseOrder::with('items')->findOrFail($id);
        $vendors = Vendor::all();
        return view('purchase_orders.edit', compact('purchaseOrder', 'vendors'));
    }

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

    public function destroy($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->delete();

        return redirect()->route('purchase_orders.index')->with('success', 'Purchase order deleted successfully.');
    }
}
