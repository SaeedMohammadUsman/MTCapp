<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\StockAdjustment;
use Illuminate\Http\Request;

class StockAdjustmentController extends Controller
{
public function index(Request $request)
{
    // Fetch stock adjustments with optional search and filtering
    $stockAdjustments = StockAdjustment::with('inventoryItem')
        ->when($request->input('search'), function ($query, $search) {
            // Search by item name in English or Persian
            $query->whereHas('inventoryItem', function ($q) use ($search) {
                $q->where('item_name_en', 'like', "%$search%")
                    ->orWhere('item_name_fa', 'like', "%$search%");
            })
            // Allow searching for adjustment type in both English and Persian
            ->orWhere('adjustment_type_en', 'like', "%$search%")
            ->orWhere('adjustment_type_fa', 'like', "%$search%");
        })
        ->when($request->input('adjustment_type'), function ($query, $type) {
            // Filter by adjustment type (both English and Persian)
            $query->where(function ($q) use ($type) {
                $q->where('adjustment_type_en', 'like', "%$type%")
                  ->orWhere('adjustment_type_fa', 'like', "%$type%");
            });
        })
        ->paginate(10); // Paginate results (10 per page)

    return view('stock_adjustments.index', compact('stockAdjustments'));
}


     
    /**
     * Show the form for creating a new stock adjustment.
     */
    public function create()
    {
        // Pass inventory items to the view for selection
        $inventoryItems = InventoryItem::all(['id', 'item_name_en', 'item_name_fa']);
        return view('stock_adjustments.create', compact('inventoryItems'));
    }

    /**
     * Store a newly created stock adjustment in storage.
     */
    // public function store(Request $request)
    // {
    //     dd($request->all());
    //     // Validate the request
    //     $request->validate([
    //         'item_id' => 'required|exists:inventory_items,id',
    //         'adjustment_type_en' => 'required|in:damaged,returns',
    //         'adjustment_type_fa' => 'required|in:خرابی,بازگشت',
    //         'quantity' => 'required|integer|min:1',
    //         'reason_en' => 'nullable|string',
    //         'reason_fa' => 'nullable|string',
    //     ]);

    //     // Create the stock adjustment
    //     StockAdjustment::create($request->all());

    //     // Update inventory item's stock
    //     $inventoryItem = InventoryItem::findOrFail($request->item_id);

    //     if ($request->adjustment_type_en === 'damaged') {
    //         $inventoryItem->quantity_in_stock -= $request->quantity;
    //     } elseif ($request->adjustment_type_en === 'returns') {
    //         $inventoryItem->quantity_in_stock += $request->quantity;
    //     }

    //     $inventoryItem->save();

    //     return redirect()->route('stock_adjustments.index')->with('success', 'Stock adjustment added successfully.');
    // }
    public function store(Request $request)
    {
        // Debugging: Dump the request data
        // dd($request->all());

        // Validate the request
        $request->validate([
            'item_id' => 'required|exists:inventory_items,id',
            'adjustment_type_en' => 'required|in:damaged,returns',
            'adjustment_type_fa' => 'required|in:خرابی,بازگشت',
            'quantity' => 'required|integer|min:1',
            'reason_en' => 'nullable|string',
            'reason_fa' => 'nullable|string',
        ]);

        // Create the stock adjustment
        StockAdjustment::create([
            'item_id' => $request->item_id,
            'adjustment_type_en' => $request->adjustment_type_en,
            'adjustment_type_fa' => $request->adjustment_type_fa,
            'quantity' => $request->quantity,
            'reason_en' => $request->reason_en,
            'reason_fa' => $request->reason_fa,
        ]);

        // Update inventory item's stock
        $inventoryItem = InventoryItem::findOrFail($request->item_id);

        if ($request->adjustment_type_en === 'damaged') {
            $inventoryItem->quantity_in_stock -= $request->quantity;
        } elseif ($request->adjustment_type_en === 'returns') {
            $inventoryItem->quantity_in_stock += $request->quantity;
        }

        $inventoryItem->save();

        return redirect()->route('stock_adjustments.index')->with('success', 'Stock adjustment added successfully.');
    }

    /**
     * Display the specified stock adjustment.
     */



     public function show($id)
     {
         
        // dd($id, StockAdjustment::findOrFail($id));
         $stockAdjustment = StockAdjustment::findOrFail($id);
         return view('stock_adjustments.show', compact('stockAdjustment'));
       
     }
     

    /**
     * Show the form for editing the specified stock adjustment.
     */
    public function edit($id)
    {
        $stockAdjustment = StockAdjustment::findOrFail($id);
        $inventoryItems = InventoryItem::all(['id', 'item_name_en', 'item_name_fa']);
        return view('stock_adjustments.edit', compact('stockAdjustment', 'inventoryItems'));
    }

    /**
     * Update the specified stock adjustment in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'item_id' => 'required|exists:inventory_items,id',
            'adjustment_type_en' => 'required|in:damaged,returns',
            'adjustment_type_fa' => 'required|in:خرابی,بازگشت',
            'quantity' => 'required|integer|min:1',
            'reason_en' => 'nullable|string',
            'reason_fa' => 'nullable|string',
        ]);

        $stockAdjustment = StockAdjustment::findOrFail($id);
        $inventoryItem = InventoryItem::findOrFail($request->item_id);

        // Revert previous adjustment before applying the new one
        if ($stockAdjustment->adjustment_type_en === 'damaged') {
            $inventoryItem->quantity_in_stock += $stockAdjustment->quantity;
        } elseif ($stockAdjustment->adjustment_type_en === 'returns') {
            $inventoryItem->quantity_in_stock -= $stockAdjustment->quantity;
        }

        // Update the stock adjustment
        $stockAdjustment->update($request->all());

        // Apply the new adjustment
        if ($request->adjustment_type_en === 'damaged') {
            $inventoryItem->quantity_in_stock -= $request->quantity;
        } elseif ($request->adjustment_type_en === 'returns') {
            $inventoryItem->quantity_in_stock += $request->quantity;
        }

        $inventoryItem->save();

        return redirect()->route('stock_adjustments.index')->with('success', 'Stock adjustment updated successfully.');
    }

    /**
     * Remove the specified stock adjustment from storage.
     */
    public function destroy($id)
    {
        $stockAdjustment = StockAdjustment::findOrFail($id);
        $inventoryItem = InventoryItem::findOrFail($stockAdjustment->item_id);

        // Revert the adjustment before deleting
        if ($stockAdjustment->adjustment_type_en === 'damaged') {
            $inventoryItem->quantity_in_stock += $stockAdjustment->quantity;
        } elseif ($stockAdjustment->adjustment_type_en === 'returns') {
            $inventoryItem->quantity_in_stock -= $stockAdjustment->quantity;
        }

        $inventoryItem->save();

        $stockAdjustment->delete();

        return redirect()->route('stock_adjustments.index')->with('success', 'Stock adjustment deleted successfully.');
    }
}
