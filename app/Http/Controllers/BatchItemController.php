<?php

namespace App\Http\Controllers;

use App\Models\BatchItem;
use App\Models\InventoryBatch;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BatchItemController extends Controller
{
    // Show the form for creating batch items
    public function create(InventoryBatch $batch)
    {

        // Fetch all items to populate the dropdown (including both English and Persian trade names)
        $items = Item::all();
        return view('batch_items.create', compact('batch', 'items')); // Adjust with your view path
    }


    // Store a newly created batch item
    public function store(Request $request, InventoryBatch $batch)
    {
        $validated = $request->validate([
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.trade_name' => 'required|string',
            'items.*.cost_price' => 'required|numeric|min:0',
            'items.*.selling_price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.expiration_date' => 'nullable|date',
        ], [], [], true);
        try {
            foreach ($validated['items'] as $itemData) {
                BatchItem::create([
                    'inventory_batch_id' => $batch->id,
                    'item_id' => $itemData['item_id'],
                    'cost_price' => $itemData['cost_price'],
                    'selling_price' => $itemData['selling_price'],
                    'quantity' => $itemData['quantity'],
                    'expiration_date' => $itemData['expiration_date'],
                ]);
            }

            // return redirect()->route('batches.index')->with('success', 'Batch items saved successfully.');
            return response()->json([
                'success' => true,
                'redirect_url' => route('batches.index'),
                'flash_message' => 'Batch items saved successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    // Show the form for editing a batch item
    public function edit($batchId, $batchItemId)
    {
        $batchItem = BatchItem::with(['inventoryBatch', 'item'])
            ->where('id', $batchItemId)
            ->where('inventory_batch_id', $batchId) // Direct check instead of whereHas
            ->firstOrFail();

        $items = Item::all();
        return view('batch_items.edit', compact('batchItem', 'items'));
    }

    public function update(Request $request, $batchId, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'items.*.trade_name' => 'required|string',
            'items.*.cost_price' => 'required|numeric|min:0',
            'items.*.selling_price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.expiration_date' => 'nullable|date',
        ]);

        // Fetch the batch item
        $batchItem = BatchItem::where('id', $id)
            ->where('inventory_batch_id', $batchId)
            ->firstOrFail();

        // Split the trade name into English and Persian
        list($itemId, $trade_name_en, $trade_name_fa) = explode('|', $request->input('items.0.trade_name'));
        $item = $batchItem->item;
        $item->update([
            'trade_name_en' => $trade_name_en,
            'trade_name_fa' => $trade_name_fa,
        ]);

        // Update the batch item
        $batchItem->update([
            'cost_price' => $request->input('items.0.cost_price'),
            'selling_price' => $request->input('items.0.selling_price'),
            'quantity' => $request->input('items.0.quantity'),
            'expiration_date' => $request->input('items.0.expiration_date'), // If provided
        ]);

        // return redirect()->route('batches.items.edit', [
        //     'batch' => $batchItem->inventory_batch_id,
        //     // 'batch_item' => $batchItem->id
        // ])->with('success', 'Batch item updated successfully.');

        return redirect()->route('batches.show', ['id' => $batchId])
        ->with('success', 'Batch item updated successfully.');
    
    }

    // Delete a batch item
    public function destroy($batchId, $batchItemId)
    {
        $batchItem = BatchItem::where('id', $batchItemId)
            ->where('inventory_batch_id', $batchId)
            ->firstOrFail();

        $batchItem->delete();

        return redirect()->route('batches.show', $batchId)
            ->with('success', 'Batch item deleted successfully.');
    }
}
