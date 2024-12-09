<?php

namespace App\Http\Controllers;

use App\Models\BatchItem;
use App\Models\InventoryBatch;
use App\Models\Item;
use Illuminate\Http\Request;

class BatchItemController extends Controller
{
    // Display the list of batch items with pagination and filtering
    public function index(Request $request)
    {
        $batch_id = $request->get('batch_id');
        $item_id = $request->get('item_id');

        // Query batch items with filters and paginate
        $batchItems = BatchItem::with(['inventoryBatch', 'item'])
            ->when($batch_id, function ($query, $batch_id) {
                return $query->where('inventory_batch_id', $batch_id);
            })
            ->when($item_id, function ($query, $item_id) {
                return $query->where('item_id', $item_id);
            })
            ->latest()
            ->paginate(10);

        $batches = InventoryBatch::all();
        $items = Item::all();

        return view('batch_items.index', compact('batchItems', 'batches', 'items'));
    }

    // Show the form for creating a new batch item
    public function create()
    {
        $batches = InventoryBatch::all();
        $items = Item::all();

        return view('batch_items.create', compact('batches', 'items'));
    }

    // Store a newly created batch item in the database
    public function store(Request $request)
    {
        $request->validate([
            'inventory_batch_id' => 'required|exists:inventory_batches,id',
            'item_id' => 'required|exists:items,id',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'expiration_date' => 'nullable|date',
        ]);

        BatchItem::create($request->only([
            'inventory_batch_id',
            'item_id',
            'cost_price',
            'selling_price',
            'quantity',
            'expiration_date',
        ]));

        return redirect()->route('batch_items.index')->with('success', 'Batch item created successfully!');
    }

    // Show the form for editing the specified batch item
    public function edit($id)
    {
        $batchItem = BatchItem::findOrFail($id);
        $batches = InventoryBatch::all();
        $items = Item::all();

        return view('batch_items.edit', compact('batchItem', 'batches', 'items'));
    }

    // Update the specified batch item in the database
    public function update(Request $request, $id)
    {
        $request->validate([
            'inventory_batch_id' => 'required|exists:inventory_batches,id',
            'item_id' => 'required|exists:items,id',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'expiration_date' => 'nullable|date',
        ]);

        $batchItem = BatchItem::findOrFail($id);
        $batchItem->update($request->only([
            'inventory_batch_id',
            'item_id',
            'cost_price',
            'selling_price',
            'quantity',
            'expiration_date',
        ]));

        return redirect()->route('batch_items.index')->with('success', 'Batch item updated successfully!');
    }

    // Delete a batch item
    public function destroy($id)
    {
        try {
            $batchItem = BatchItem::findOrFail($id);
            $batchItem->delete();

            return redirect()->route('batch_items.index')->with('success', 'Batch item deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('batch_items.index')->with('error', 'Error deleting batch item: ' . $e->getMessage());
        }
    }
}
