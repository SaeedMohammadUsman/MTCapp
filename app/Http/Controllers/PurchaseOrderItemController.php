<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrderItem;
use App\Models\InventoryItem;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderItemController extends Controller
{
    /**
     * Show the form for creating a new item in the purchase order.
     */
    public function create($purchaseOrderId)
    {
        // Fetch inventory items for the dropdown and the specified purchase order
        $inventoryItems = InventoryItem::all();
        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);

        // You can also pass any existing items if you want to display them in the form (optional)
        $purchaseOrderItems = $purchaseOrder->items;

        return view('purchase_order_items.create', compact('inventoryItems', 'purchaseOrder', 'purchaseOrderItems'));
    }

    public function store(Request $request, $purchaseOrderId)
    {
        // Validate incoming request for multiple items with detailed fields
        $request->validate([
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

        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);
        $totalOrderPrice = 0;

        foreach ($request->items as $itemData) {
            // Fetch the selected inventory item
            $inventoryItem = InventoryItem::findOrFail($itemData['item_id']);

            // Calculate total price for each item
            $totalPrice = $itemData['unit_price'] * $itemData['quantity'];

            // Create the purchase order item, including all the detailed fields
            $purchaseOrderItem = PurchaseOrderItem::create([
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

            // Add this item's price to the total order price
            $totalOrderPrice += $totalPrice;
        }

        // Update total price in the purchase order
        $purchaseOrder->update([
            'total_price' => $totalOrderPrice,
        ]);

        // Redirect back to purchase order with success message
        // In the 'store' method after creating the items
        // return redirect()->route('purchase_orders.items.show', [
        //     'purchase_order' => $purchaseOrder->id,
        //     'item' => $purchaseOrderItem->id, // Pass the ID of the newly created item
        // Redirect to the show route
        dd($purchaseOrder, $purchaseOrderItem->id);
      
        return redirect()->route('purchase_orders.items.show', [
            'purchase_order' => $purchaseOrder,
            'item' => $purchaseOrderItem->id,
        ])->with('success', 'Purchase order items added successfully.');

    }



    /**
     * Show the form for editing an item in the purchase order.
     */
    public function edit($id)
    {
        // Find the purchase order item and its associated inventory item
        $purchaseOrderItem = PurchaseOrderItem::with('inventoryItem')->findOrFail($id);

        // Fetch all inventory items for the dropdown
        $inventoryItems = InventoryItem::all();

        return view('purchase_order_items.edit', compact('purchaseOrderItem', 'inventoryItems'));
    }

    /**
     * Update the specified item in the purchase order.
     */
    public function update(Request $request, $id)
    {
        // Validate incoming request
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
        ]);

        // Find the purchase order item
        $purchaseOrderItem = PurchaseOrderItem::findOrFail($id);

        // Calculate the total price
        $totalPrice = $request->unit_price * $request->quantity;

        // Update the purchase order item
        $purchaseOrderItem->update([
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'total_price' => $totalPrice,
            'remarks' => $request->remarks,
        ]);

        // Update the total price of the purchase order
        $purchaseOrderItem->purchaseOrder->update([
            'total_price' => $purchaseOrderItem->purchaseOrder->items()->sum('total_price'),
        ]);

        // Redirect back with a success message
        return redirect()->route('purchase_orders.show', $purchaseOrderItem->purchase_order_id)
            ->with('success', 'Purchase order item updated successfully.');
    }

    /**
     * Remove the specified item from the purchase order.
     */
    // public function destroy($id)
    // {
    //     // Find the purchase order item and its associated purchase order
    //     $purchaseOrderItem = PurchaseOrderItem::findOrFail($id);
    //     $purchaseOrder = $purchaseOrderItem->purchaseOrder;

    //     // Delete the purchase order item
    //     $purchaseOrderItem->delete();

    //     // Update the total price of the purchase order
    //     $purchaseOrder->update([
    //         'total_price' => $purchaseOrder->items()->sum('total_price'),
    //     ]);

    //     // Redirect back with success message
    //     return redirect()->route('purchase_orders.show', $purchaseOrder->id)
    //         ->with('success', 'Purchase order item deleted successfully.');
    // }
    // public function show($purchaseOrderId, $purchaseOrderItemId)
    // {
    //     $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);
    //     $purchaseOrderItem = PurchaseOrderItem::findOrFail($purchaseOrderItemId);
    //     return view('purchase_orders.show', compact('purchaseOrder', 'purchaseOrderItem'));
    // }




    public function show($purchaseOrder, $item)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrder);
        $purchaseOrderItem = PurchaseOrderItem::findOrFail($item);
        dd($purchaseOrder, $purchaseOrderItem);

        return view('purchase_orders.show', compact('purchaseOrder', 'purchaseOrderItem'));
        
    }





    public function destroy($purchaseOrderId, $purchaseOrderItemId)
    {
        // Fetch the purchase order and item
        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);
        $purchaseOrderItem = PurchaseOrderItem::findOrFail($purchaseOrderItemId);

        // Delete the item from the purchase order
        $purchaseOrderItem->delete();

        // Recalculate the total price for the order
        $totalOrderPrice = $purchaseOrder->items->sum('total_price');
        $purchaseOrder->update(['total_price' => $totalOrderPrice]);

        return redirect()->route('purchase_orders.show', $purchaseOrderId)
            ->with('success', 'Item removed from purchase order.');
    }
}
