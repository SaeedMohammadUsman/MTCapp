<?php

namespace App\Http\Controllers;

use App\Models\ReceivedGood;
use App\Models\ReceivedGoodDetail;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReceivedGoodDetailController extends Controller
{
    // Show the form for creating a received good detail
    public function create(ReceivedGood $receivedGood)
    {
        // Fetch all items to populate the dropdown
        $items = Item::all();
        return view('received_goods.details.create', compact('receivedGood', 'items'));
    }

    // Store a newly created received good detail
    public function store(Request $request, ReceivedGood $receivedGood)
    {
        $validated = $request->validate([
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.vendor_price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.expiration_date' => 'nullable|date',
        ], [], [], true);

        try {
            foreach ($validated['items'] as $itemData) {
                $existingItem = ReceivedGoodDetail::where('received_good_id', $receivedGood->id)
                ->where('item_id', $itemData['item_id'])
                ->exists();
                if ($existingItem) {
                    // Return an error response if the item already exists
                    return response()->json([
                        'success' => false,
                        'message' => 'Duplicate item detected: ' . $itemData['item_id'],
                    ], 400); // 400 Bad Request
                }
                ReceivedGoodDetail::create([
                    'received_good_id' => $receivedGood->id,
                    'item_id' => $itemData['item_id'],
                    'vendor_price' => $itemData['vendor_price'],
                    'quantity' => $itemData['quantity'],
                    'expiration_date' => $itemData['expiration_date'],
                ]);
            }

            // Return a success response
            return response()->json([
                'success' => true,
                'redirect_url' => route('received_goods.show', $receivedGood->id),
                'flash_message' => 'Received good details saved successfully.',
            ]);
        } catch (\Exception $e) {
            // Log error and return failure response
            Log::error('Error while saving received good details: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    // Show the form for editing a received good detail
    public function edit($receivedGoodId, $receivedGoodDetailId)
    {
        $receivedGoodDetail = ReceivedGoodDetail::with(['receivedGood', 'item'])
            ->where('id', $receivedGoodDetailId)
            ->where('received_good_id', $receivedGoodId)
            ->firstOrFail();

        $items = Item::all();
        return view('received_goods.details.edit', compact('receivedGoodDetail', 'items'));
    }

    // Update the received good detail
    public function update(Request $request, $receivedGoodId, $receivedGoodDetailId)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'vendor_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'expiration_date' => 'nullable|date',
        ]);

        // Fetch the received good detail
        $receivedGoodDetail = ReceivedGoodDetail::where('id', $receivedGoodDetailId)
            ->where('received_good_id', $receivedGoodId)
            ->firstOrFail();
            
            
            $existingItem = ReceivedGoodDetail::where('received_good_id', $receivedGoodId)
            ->where('item_id', $validated['item_id'])
            ->where('id', '!=', $receivedGoodDetailId) // Exclude the current record from the check
            ->exists();
            if ($existingItem) {
                // Redirect back with error message if duplicate item is found
                return redirect()->back()
                    ->with('error', 'This item has already been added to the received good.');
            }
            
        // Update the received good detail
        $receivedGoodDetail->update([
            'item_id' => $validated['item_id'],
            'vendor_price' => $validated['vendor_price'],
            'quantity' => $validated['quantity'],
            'expiration_date' => $validated['expiration_date'],
        ]);

        return redirect()->route('received_goods.show', $receivedGoodId)
            ->with('success', 'Received good detail updated successfully.');
    }

    // Delete a received good detail
    public function destroy($receivedGoodId, $receivedGoodDetailId)
    {
        $receivedGoodDetail = ReceivedGoodDetail::where('id', $receivedGoodDetailId)
            ->where('received_good_id', $receivedGoodId)
            ->firstOrFail();

        $receivedGoodDetail->delete();

        return redirect()->route('received_goods.show', $receivedGoodId)
            ->with('success', 'Received good detail deleted successfully.');
    }
}
