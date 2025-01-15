<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CustomerOrder;
use App\Models\CustomerOrderItem;
use App\Models\Item;
use Illuminate\Http\Request;

class CustomerOrderItemController extends Controller
{
    public function create($customerOrderId)
    {
        $customerOrder = CustomerOrder::with('pricePackage')->findOrFail($customerOrderId);
        if (!$customerOrder->price_package_id) {
            return response()->json(['error' => 'No price package assigned.'], 400);
        }

        $items = Item::whereHas('pricePackageDetails', function ($query) use ($customerOrder) {
            $query->where('price_package_id', $customerOrder->price_package_id);
        })->with(['pricePackageDetails' => function ($query) use ($customerOrder) {
            $query->where('price_package_id', $customerOrder->price_package_id);
        }])->get();

        return response()->json(['items' => $items]);
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'customer_order_id' => 'required|exists:customer_orders,id',
    //         'item_ids' => 'required|array',
    //         'quantities' => 'required|array',
    //     ]);

    //     foreach ($request->item_ids as $itemId) {
    //         $customerOrder = CustomerOrder::find($request->customer_order_id);
    //         $pricePackageDetail = Item::find($itemId)->pricePackageDetails()->where('price_package_id', $customerOrder->price_package_id)->first();
    //         CustomerOrderItem::create([
    //             'customer_order_id' => $request->customer_order_id,
    //             'item_id' => $itemId,
    //             'quantity' => $request->quantities[$itemId],
    //             'price' => $pricePackageDetail->price,
    //         ]);
    //     }

    //     return response()->json(['success' => 'Items added successfully.']);
    // }
    
    public function store(Request $request)
{
    $request->validate([
        'customer_order_id' => 'required|exists:customer_orders,id',
        'item_ids' => 'required|array',
        'quantities' => 'required|array',
    ]);
    $customerOrder = CustomerOrder::find($request->customer_order_id);
    $totalAmount = $customerOrder->total_amount;
    

    foreach ($request->item_ids as $itemId) {
        // Check if the item is already added to the customer order
        $existingItem = CustomerOrderItem::where('customer_order_id', $request->customer_order_id)
                                          ->where('item_id', $itemId)
                                          ->first();

        if ($existingItem) {
            return response()->json([
                'error' => 'Item with ID ' . $itemId . ' has already been added to the order.'
            ], 400); // Return an error with a 400 status code
        }

   
        // Find the price package detail for the item
        $pricePackageDetail = Item::find($itemId)
                                  ->pricePackageDetails()
                                  ->where('price_package_id', $customerOrder->price_package_id)
                                  ->first();

        
         $itemPrice = $pricePackageDetail->price;
         $quantity = $request->quantities[$itemId];
         $itemTotalPrice = $itemPrice * $quantity;
         $totalAmount += $itemTotalPrice;
         
        // Create the new CustomerOrderItem
        CustomerOrderItem::create([
            'customer_order_id' => $request->customer_order_id,
            'item_id' => $itemId,
            'quantity' => $request->quantities[$itemId],
            'price' => $pricePackageDetail->price,
        ]);
    }
    $customerOrder->update([
        'total_amount' => $totalAmount,
    ]);
    return response()->json(['success' => 'Items added successfully.']);
}


public function destroy($customerOrderId, $customerOrderItemId)
{
    // Retrieve the customer order and item, eager load the orderItems
    $customerOrder = CustomerOrder::with('orderItems')->findOrFail($customerOrderId);
    $customerOrderItem = CustomerOrderItem::findOrFail($customerOrderItemId);

    // Calculate the price of the item to be deleted
    $itemTotalPrice = $customerOrderItem->price * $customerOrderItem->quantity;

    // Delete the customer order item
    $customerOrderItem->delete();

    // Recalculate the total amount of the customer order after the deletion
    $totalAmount = $customerOrder->orderItems->isNotEmpty() 
        ? $customerOrder->orderItems->sum(function ($item) {
            return $item->price * $item->quantity;
        }) 
        : 0;

    // Subtract the deleted item's total price from the total amount
    $totalAmount -= $itemTotalPrice;

    // Update the total amount
    $customerOrder->update(['total_amount' => $totalAmount]);

    return redirect()->route('customer_orders.show', $customerOrder->id)
                     ->with('success', 'Item deleted successfully.');
}

}
