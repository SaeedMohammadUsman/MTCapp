<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PurchaseOrderItemController extends Controller
{
    /**
     * Show the form for creating a new item in the purchase order.
     */
    public function create($purchaseOrderId)
    {
        $inventoryItems = InventoryItem::all();
        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);
        $purchaseOrderItems = $purchaseOrder->items;

        return view('purchase_order_items.create', compact('inventoryItems', 'purchaseOrder', 'purchaseOrderItems'));
    }

    /**
     * Store new items in the purchase order.
     */
    public function store(Request $request, $purchaseOrderId)
    {
        
        // Log::info('Request data:', $request->all());
        // Validate incoming request
        $validatedData = $request->validate([
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:inventory_items,id',
            'items.*.trade_name_en' => 'required|string|max:100',
            'items.*.trade_name_fa' => 'required|string|max:100',
            'items.*.used_for_en' => 'nullable|string|max:255',
            'items.*.used_for_fa' => 'nullable|string|max:255',
            'items.*.size' => 'nullable|string|max:50',
            'items.*.c_size' => 'nullable|string|max:50',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.remarks' => 'nullable|string',
        ]);
        
    
        try {
            // Fetch the purchase order
            $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);
            $totalOrderPrice = $purchaseOrder->total_price ?? 0;
    
            $createdItems = [];
            foreach ($validatedData['items'] as $itemData) {
                // Calculate total price for the item
                $totalPrice = $itemData['unit_price'] * $itemData['quantity'];
    
                // Create a purchase order item
                $createdItem = PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrderId,
                    'item_id' => $itemData['item_id'],
                    'trade_name_en' => $itemData['trade_name_en'],
                    'trade_name_fa' => $itemData['trade_name_fa'],
                    'used_for_en' => $itemData['used_for_en'],
                    'used_for_fa' => $itemData['used_for_fa'],
                    'size' => $itemData['size'],
                    'c_size' => $itemData['c_size'],
                    'unit_price' => $itemData['unit_price'],
                    'quantity' => $itemData['quantity'],
                    'total_price' => $totalPrice,
                    'remarks' => $itemData['remarks'],
                ]);
    
                $createdItems[] = $createdItem; // Collect created items for response
                $totalOrderPrice += $totalPrice;
            }
    
            // Update the total price of the purchase order
            $purchaseOrder->update(['total_price' => $totalOrderPrice]);
    
            // Return a JSON response for AJAX
            return response()->json([
                'success' => true,
                'message' => 'Purchase order items added successfully.',
                'data' => [
                    'purchase_order' => $purchaseOrder,
                    'items' => $createdItems,
                ],
            ]);
        } catch (\Exception $e) {
            // Handle any unexpected errors
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while adding items.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Show the form for editing an item in the purchase order.
     */
    // public function edit($id)
    // {
    //     $purchaseOrderItem = PurchaseOrderItem::with('inventoryItem')->findOrFail($id);
    //     $inventoryItems = InventoryItem::all();

    //     return view('purchase_order_items.edit', compact('purchaseOrderItem', 'inventoryItems'));
    // }

    public function edit($purchaseOrderId, $itemId)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);
        $purchaseOrderItem = $purchaseOrder->items()->findOrFail($itemId);
        $inventoryItems = InventoryItem::all(); // or filtered list of items
        return view('purchase_order_items.edit', compact('purchaseOrder', 'purchaseOrderItem', 'inventoryItems'));

    }
    
    /**
     * Update the specified item in the purchase order.
     */
    // public function update(Request $request, $id)
    // {
        
    //     $request->validate([
    //         'quantity' => 'required|integer|min:1',
    //         'unit_price' => 'required|numeric|min:0',
    //     ]);

    //     $purchaseOrderItem = PurchaseOrderItem::findOrFail($id);
    //     $totalPrice = $request->unit_price * $request->quantity;

    //     $purchaseOrderItem->update([
    //         'quantity' => $request->quantity,
    //         'unit_price' => $request->unit_price,
    //         'total_price' => $totalPrice,
    //         'remarks' => $request->remarks,
    //     ]);

    //     $purchaseOrderItem->purchaseOrder->update([
    //         'total_price' => $purchaseOrderItem->purchaseOrder->items()->sum('total_price'),
    //     ]);

    //     return redirect()->route('purchase_orders.show', $purchaseOrderItem->purchase_order_id)
    //         ->with('success', 'Purchase order item updated successfully.');
    // }

    public function update(Request $request, $purchaseOrderId, $id)
{
    // Validate input fields
    $request->validate([
        'quantity' => 'required|integer|min:1',
        'unit_price' => 'required|numeric|min:0',
        'trade_name_en' => 'required|string|max:255',
        'trade_name_fa' => 'required|string|max:255',
        'item_id' => 'required|exists:inventory_items,id',
    ]);

    // Find the purchase order and the purchase order item
    $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);
    $purchaseOrderItem = $purchaseOrder->items()->findOrFail($id); // Ensure you're only fetching items for the correct purchase order

    // Calculate the new total price for the item
    $totalPrice = $request->unit_price * $request->quantity;

    // Update the purchase order item details
    $purchaseOrderItem->update([
        'quantity' => $request->quantity,
        'unit_price' => $request->unit_price,
        'total_price' => $totalPrice,
        'remarks' => $request->remarks,
        'trade_name_en' => $request->trade_name_en, // Update trade name in English
        'trade_name_fa' => $request->trade_name_fa, // Update trade name in Farsi
        'item_id' => $request->item_id, 
    ]);

    // Update the total price of the parent purchase order
    $purchaseOrder->update([
        'total_price' => $purchaseOrder->items()->sum('total_price'),
    ]);

    // Redirect to the current purchase order details page with a success message
    return redirect()->route('purchase_orders.show', $purchaseOrder->id)
        ->with('success', 'Purchase order item updated successfully.');
}

    

    /**
     * Show a purchase order and a specific item.
     */
    public function show($purchaseOrder, $item)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrder);
        $purchaseOrderItem = PurchaseOrderItem::findOrFail($item);

        // return view('purchase_orders.show', compact('purchaseOrder', 'purchaseOrderItem'));
        return view('purchase_order_items.show', compact('purchaseOrder', 'purchaseOrderItem'));
    }

    /**
     * Remove the specified item from the purchase order.
     */
    public function destroy($purchaseOrderId, $purchaseOrderItemId)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);
        $purchaseOrderItem = PurchaseOrderItem::findOrFail($purchaseOrderItemId);

        $purchaseOrderItem->delete();

        $totalOrderPrice = $purchaseOrder->items->sum('total_price');
        $purchaseOrder->update(['total_price' => $totalOrderPrice]);

        return redirect()->route('purchase_orders.show', $purchaseOrderId)
            ->with('success', 'Item removed from purchase order.');
    }
}
