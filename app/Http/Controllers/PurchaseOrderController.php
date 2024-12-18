<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

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
            })->orderBy('created_at', 'desc')
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

        // If status is completed, download the PDF and redirect with a success message
        if ($purchaseOrder->status_en === 'Completed') {
            $this->generatePdf($purchaseOrder->id); 
            return redirect()->route('purchase_orders.index')
            ->with('success', 'Purchase order updated successfully and PDF downloaded.');
        }
        return redirect()->route('purchase_orders.index')
            ->with('success', 'Purchase order updated successfully.');
    }

    /**
     * Remove the specified purchase order from storage.
     */


    public function generatePdf($purchaseOrderId)
    {
        $purchaseOrder = PurchaseOrder::with(['vendor', 'items.item'])->findOrFail($purchaseOrderId);
        $fileName = $purchaseOrder->order_number . '.pdf';
        $filePath = 'C:\\Users\\mohus\\Downloads\\' . $fileName;
        $counter = 1;
        while (file_exists($filePath)) {
            $filePath = 'C:\\Users\\mohus\\Downloads\\' . $purchaseOrder->order_number . '_' . $counter . '.pdf';
            $counter++;
        }
     
        
        $pdf = PDF::loadView('purchase_orders.pdf', compact('purchaseOrder'))
        ->setOption('enable-local-file-access', true);            
            $pdf->save($filePath);
            return response()->download($filePath);
    }
    
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
