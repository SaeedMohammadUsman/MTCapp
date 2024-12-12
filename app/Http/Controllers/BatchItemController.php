<?php

namespace App\Http\Controllers;
use App\Models\BatchItem;
use App\Models\InventoryBatch;
use App\Models\Item;
// use Illuminate\Bus\Batch;
use Illuminate\Http\Request;

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
        // Validate the incoming request data
        $request->validate([
            'items.*.trade_name' => 'required|string',
            'items.*.cost_price' => 'required|numeric|min:0',
            'items.*.selling_price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1', 
            'items.*.expiration_date' => 'nullable|date', 
        ]);

        // Loop through each item submitted
        foreach ($request->input('items') as $itemData) {
            // Split the trade name into English and Persian
            list($trade_name_en, $trade_name_fa) = explode('|', $itemData['trade_name']);

            // Store the batch item
            BatchItem::create([
                'inventory_batch_id' => $batch->id, 
                'trade_name_en' => $trade_name_en,
                'trade_name_fa' => $trade_name_fa,
                'cost_price' => $itemData['cost_price'],
                'selling_price' => $itemData['selling_price'],
                'quantity' => $itemData['quantity'],
                'expiration_date' => $itemData['expiration_date'], // If provided
            ]);
        }
        return redirect()->route('batches.show', $batch->id)->with('success', 'Batch items created successfully.');

        // return redirect()->route('batch-items.create', ['batch' => $batch->id])->with('success', 'Batch items created successfully.');

    }

    // Show the form for editing a batch item
    public function edit($id)
    {
        // Fetch the batch item and all items for the dropdown
        $batchItem = BatchItem::findOrFail($id);
        $items = Item::all();

        return view('batch_items.edit', compact('batchItem', 'items')); // Adjust with your view path
    }

    // Update the specified batch item
    public function update(Request $request, $id)
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
        $batchItem = BatchItem::findOrFail($id);

        // Split the trade name into English and Persian
        list($trade_name_en, $trade_name_fa) = explode('|', $request->input('items.0.trade_name'));

        // Update the batch item
        $batchItem->update([
            'trade_name_en' => $trade_name_en,
            'trade_name_fa' => $trade_name_fa,
            'cost_price' => $request->input('items.0.cost_price'),
            'selling_price' => $request->input('items.0.selling_price'),
            'quantity' => $request->input('items.0.quantity'),
            'expiration_date' => $request->input('items.0.expiration_date'), // If provided
        ]);

        return redirect()->route('batch-items.create')->with('success', 'Batch item updated successfully.');
    }

    // Delete a batch item
    public function destroy($id)
    {
        $batchItem = BatchItem::findOrFail($id);
        
        // Fetch the batch item and delete it
        try {
            $batchItem->delete();
    
            return redirect()->route('batches.show', $batchItem->inventoryBatch->id)->with('success', 'Batch item deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('batches.show')->with('error', 'Error deleting batch item: ' . $e->getMessage());
        }
    }
}
