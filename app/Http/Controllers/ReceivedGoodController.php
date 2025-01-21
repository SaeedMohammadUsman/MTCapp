<?php

namespace App\Http\Controllers;

use App\Models\ReceivedGood;
use App\Models\ReceivedGoodDetail;
use App\Models\StockTransaction;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReceivedGoodController extends Controller
{
    /**
     * Display a listing of received goods with optional filtering and pagination.
     */
    public function index(Request $request)
    {
        $vendors = Vendor::all();

        // Fetch filter values if available
        $batch_number = $request->get('batch_number');
        $vendor_id = $request->get('vendor_id');
        $is_finalized = $request->get('is_finalized');
        $filter = $request->get('filter');

        // Query received goods with filters and paginate
        $receivedGoods = ReceivedGood::query()
            ->when($batch_number, function ($query, $batch_number) {
                return $query->where('batch_number', 'like', "%{$batch_number}%");
            })
            ->when($vendor_id, function ($query, $vendor_id) {
                return $query->where('vendor_id', $vendor_id);
            }) ->when($is_finalized !== null, function ($query) use ($is_finalized) {
                return $query->where('is_finalized', $is_finalized);
            })
            ->when($filter === 'trashed', function ($query) {
                $query->onlyTrashed(); // Show trashed records when 'trashed' is selected
            })
            ->when($filter === 'active', function ($query) {
                $query->whereNull('deleted_at'); // Only active records
            })
            ->latest()
            ->paginate(10);

        // Check if there are trashed records
        $hasTrashed = ReceivedGood::onlyTrashed()->exists();
        // dd($receivedGoods);
        return view('received_goods.index', compact('receivedGoods', 'vendors', 'hasTrashed'));
    }

    /**
     * Show the form for creating a new received good.
     */
    public function create()
    {
        $vendors = Vendor::all();
        return view('received_goods.create', compact('vendors'));
    }

    /**
     * Store a newly created received good in storage.
    **/
    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'remark' => 'nullable|string',
            'bill_attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ]);

        $billAttachmentPath = null;

        // Create a new record to fetch the auto-generated batch number
        $receivedGood = ReceivedGood::create([
            'vendor_id' => $request->vendor_id,
            'remark' => $request->remark,
            'bill_attachment' => null, // Placeholder until file is stored
            'date' => now(),
        ]);

        // Handle bill attachment with custom naming logic
        if ($request->hasFile('bill_attachment')) {
            $vendor = Vendor::findOrFail($request->vendor_id); // Fetch vendor details
            $vendorPrefix = substr($vendor->company_name_en, 0, 3); // First 3 letters of vendor name
            $batchNumber = $receivedGood->batch_number; // Get auto-generated batch number

            $originalExtension = $request->file('bill_attachment')->getClientOriginalExtension();
            $customFileName = "{$vendorPrefix}_Batch{$batchNumber}." . $originalExtension;

            $billAttachmentPath = $request->file('bill_attachment')->storeAs('bill_attachments', $customFileName, 'public');
            $receivedGood->update(['bill_attachment' => $billAttachmentPath]); // Update the record
        }

        return redirect()->route('received_goods.index')->with('success', 'Received good created successfully!');
    }


    /**
     * Display the specified received good and its details.
     */
    public function show($id)
    {
        $receivedGood = ReceivedGood::with(['vendor', 'details'])->findOrFail($id);
        return view('received_goods.show', compact('receivedGood'));
    }

    /**
     * Show the form for editing the specified received good.
     */
    public function edit($id)
    {
        $receivedGood = ReceivedGood::findOrFail($id);
        $vendors = Vendor::all();
        return view('received_goods.edit', compact('receivedGood', 'vendors'));
    }

    /**
     * Update the specified received good in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'remark' => 'nullable|string',
            'bill_attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'is_finalized' => 'required|boolean',
        ]);

        $receivedGood = ReceivedGood::findOrFail($id);

        $billAttachmentPath = $receivedGood->bill_attachment; // Retain existing attachment by default

        if ($request->hasFile('bill_attachment')) {
            $vendor = Vendor::findOrFail($request->vendor_id); // Fetch vendor details
            $vendorPrefix = substr($vendor->company_name_en, 0, 3); // First 3 letters of vendor name
            $batchNumber = $receivedGood->batch_number; // Use the existing batch number

            $originalExtension = $request->file('bill_attachment')->getClientOriginalExtension();
            $customFileName = "{$vendorPrefix}_Batch{$batchNumber}." . $originalExtension;

            $billAttachmentPath = $request->file('bill_attachment')->storeAs('bill_attachments', $customFileName, 'public');
        }

        $receivedGood->update([
            'vendor_id' => $request->vendor_id,
            'remark' => $request->remark,
            'bill_attachment' => $billAttachmentPath,
            'is_finalized' => $request->is_finalized,
        ]);

        return redirect()->route('received_goods.index')->with('success', 'Received good updated successfully!');
    }

    public function getDetails($id)
    {
        // Eager load the details and the associated items.
        $receivedGood = ReceivedGood::with('details.item')->findOrFail($id);
    
        // Return the details with the associated items as a response
        return response()->json([
            'details' => $receivedGood->details->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'item' => [
                        'id' => $detail->item->id,
                        'trade_name_en' => $detail->item->trade_name_en,
                        'trade_name_fa' => $detail->item->trade_name_fa,
                    ],
                    'quantity' => $detail->quantity,
                    'vendor_price' => $detail->vendor_price,
                    'expiration_date' => $detail->expiration_date,
                ];
            })
        ]);
    }
    
    public function stockIn(Request $request, $id)
{
    
    // Validate the request
    $request->validate([
        'arrival_prices' => 'required|array',
        'arrival_prices.*' => 'required|numeric|min:0',
    ]);

    // Fetch the ReceivedGood and its details
    $receivedGood = ReceivedGood::with('details')->find($id);
    if (!$receivedGood) {
        return redirect()->back()->with('error', 'Received good not found.');
    }
    
    

    if ($receivedGood->is_finalized != 1) {
        return redirect()->back()->with('warning', 'The received good is not finalized.');
    }

    // Check if this batch has already been stocked in
    $alreadyStockedIn = StockTransaction::where('received_good_id', $receivedGood->id)->exists();
    if ($alreadyStockedIn) {
        return response()->json([
            'message' => 'This batch has already been stocked in.'
        ]);
    }
    try {
        // Process the stock-in within a database transaction
        DB::transaction(function () use ($receivedGood, $request) {
            $stockTransaction = StockTransaction::create([
                'transaction_type' => 1, // Stock In
                'received_good_id' => $receivedGood->id,
                'remarks' => 'Stock in for received goods',
                'transaction_date' => now(),
            ]);
            
            
            foreach ($receivedGood->details as $detail) {
                $arrivalPrice = $request->arrival_prices[$detail->id] ?? null;

                if (!$arrivalPrice) {
                    throw new \Exception("Arrival price missing for detail ID: {$detail->id}");
                }
                
                $stockTransaction->details()->create([
                    'stock_transaction_id' => $stockTransaction->id,
                    'item_id' => $detail->item_id, 
                    'quantity' => $detail->quantity,
                    'price' => $arrivalPrice, 
                    'remarks' => 'Added to stock',
                ]);
            }
            $receivedGood->update(['stocked_in' => true]);
        });
        return redirect()->back()->with('success', 'Stock in completed successfully.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Stock-in process failed.');
    }
}

  
  

    /**
     * Remove the specified received good from storage.
     */
    public function destroy($id)
    {
        $receivedGood = ReceivedGood::findOrFail($id);
        $receivedGood->delete();

        return redirect()->route('received_goods.index')->with('success', 'Received good deleted successfully!');
    }

    /**
     * Restore the specified soft-deleted received good.
     */
    public function restore($id)
    {
        $receivedGood = ReceivedGood::withTrashed()->findOrFail($id);
        $receivedGood->restore();

        return redirect()->route('received_goods.index')->with('success', 'Received good restored successfully!');
    }
}
