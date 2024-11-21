<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrderItem;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class PurchaseOrderItemController extends Controller
{
    public function edit($id)
    {
        $purchaseOrderItem = PurchaseOrderItem::with('inventoryItem')->findOrFail($id);
        $inventoryItems = InventoryItem::all();

        return view('purchase_order_items.edit', compact('purchaseOrderItem', 'inventoryItems'));
    }

    public function update(Request $request, $id)
    {
        $purchaseOrderItem = PurchaseOrderItem::findOrFail($id);

        $request->validate([
            'trade_name_en' => 'required|max:100',
            'trade_name_fa' => 'required|max:100',
            'unit_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
        ]);

        $purchaseOrderItem->update($request->only([
            'trade_name_en', 'trade_name_fa', 'used_for_en', 'used_for_fa',
            'size', 'c_size', 'unit_price', 'quantity', 'total_price', 'remarks'
        ]));

        return redirect()->route('purchase_orders.show', $purchaseOrderItem->purchase_order_id)
                         ->with('success', 'Purchase order item updated successfully.');
    }

    public function destroy($id)
    {
        $purchaseOrderItem = PurchaseOrderItem::findOrFail($id);
        $purchaseOrderItem->delete();

        return back()->with('success', 'Purchase order item deleted successfully.');
    }

    public function store(Request $request, $purchaseOrderId)
    {
        $request->validate([
            'item_id' => 'required|exists:inventory_items,id',
            'trade_name_en' => 'required|max:100',
            'trade_name_fa' => 'required|max:100',
            'unit_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
        ]);

        PurchaseOrderItem::create(array_merge($request->all(), ['purchase_order_id' => $purchaseOrderId]));

        return redirect()->route('purchase_orders.show', $purchaseOrderId)
                         ->with('success', 'Purchase order item added successfully.');
    }
}
