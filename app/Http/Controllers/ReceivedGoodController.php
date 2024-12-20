<?php

namespace App\Http\Controllers;

use App\Models\ReceivedGood;
use App\Models\Vendor;
use Illuminate\Http\Request;

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
            // ->when($is_finalized, function ($query, $is_finalized) {
            //     return $query->where('is_finalized', $is_finalized);
            // })
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
