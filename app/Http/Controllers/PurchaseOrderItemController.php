<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;

class PurchaseOrderItemController extends Controller
{
    public function create($purchaseOrderId)
    {
        $items = Item::all();
        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);

        return view('purchase_order_items.create', compact('items', 'purchaseOrder'));
    }

    public function store(Request $request, $purchaseOrderId)
    {
        $validatedData = $request->validate([
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.remarks' => 'nullable|string',
        ]);

        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);
        $createdItems = [];

        foreach ($validatedData['items'] as $itemData) {
            $createdItem = PurchaseOrderItem::create([
                'purchase_order_id' => $purchaseOrderId,
                'item_id' => $itemData['item_id'],
                'quantity' => $itemData['quantity'],
                'remarks' => $itemData['remarks'],
            ]);
            $createdItems[] = $createdItem;
        }

        return response()->json([
            'success' => true,
            'message' => 'Purchase order items added successfully.',
            'data' => ['items' => $createdItems],
        ]);
    }

    public function edit($purchaseOrderId, $itemId)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);
        $purchaseOrderItem = $purchaseOrder->items()->findOrFail($itemId);
        $items = Item::all();

        return view('purchase_order_items.edit', compact('purchaseOrder', 'purchaseOrderItem', 'items'));
    }

    public function update(Request $request, $purchaseOrderId, $id)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'remarks' => 'nullable|string',
        ]);

        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);
        $purchaseOrderItem = $purchaseOrder->items()->findOrFail($id);

        $purchaseOrderItem->update([
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('purchase_orders.show', $purchaseOrderId)
            ->with('success', 'Purchase order item updated successfully.');
    }

    public function destroy($purchaseOrderId, $purchaseOrderItemId)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);
        $purchaseOrderItem = PurchaseOrderItem::findOrFail($purchaseOrderItemId);

        $purchaseOrderItem->delete();

        return redirect()->route('purchase_orders.show', $purchaseOrderId)
            ->with('success', 'Item removed from purchase order.');
    }
}



