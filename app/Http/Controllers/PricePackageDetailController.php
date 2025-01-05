<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\PricePackage;
use App\Models\PricePackageDetail;
use App\Models\StockTransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PricePackageDetailController extends Controller
{


    public function create($pricePackageId)
{
    $pricePackage = PricePackage::findOrFail($pricePackageId);

    // Fetch all items with their available quantity calculated based on FIFO
    $items = Item::with(['category'])
        ->join('received_goods_details', 'items.id', '=', 'received_goods_details.item_id')
        ->join('stock_transaction_details', 'received_goods_details.id', '=', 'stock_transaction_details.received_good_detail_id')
        ->select(
            'items.id',
            'items.trade_name_en',
            'items.trade_name_fa',
            DB::raw('SUM(received_goods_details.quantity) as available_quantity'),
            DB::raw('MIN(stock_transaction_details.created_at) as earliest_created_at'), // Get the earliest created_at
            DB::raw('GROUP_CONCAT(stock_transaction_details.arrival_price ORDER BY stock_transaction_details.created_at) as arrival_prices')
        )
        ->groupBy('items.id', 'items.trade_name_en', 'items.trade_name_fa')
        ->having('available_quantity', '>', 0) 
        ->get();

    // Now, for each item, fetch the FIFO arrival price based on the earliest created_at
    foreach ($items as $item) {
        $fifoStockDetail = StockTransactionDetail::whereHas('receivedGoodDetail', function ($query) use ($item) {
            $query->where('item_id', $item->id);
        })
        ->where('created_at', $item->earliest_created_at) // Match the earliest created_at
        ->first();

        // Set the FIFO arrival price
        $item->fifo_arrival_price = $fifoStockDetail ? $fifoStockDetail->arrival_price : null;
    }

    return view('packages.details.create', compact('pricePackage', 'items'));
}


public function store(Request $request, $pricePackageId)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'items' => 'required|array',
        'items.*.item_id' => 'required|exists:items,id',
        'items.*.discount' => 'nullable|numeric|min:0|max:100',
        'items.*.price' => 'required|numeric|min:0',
    ]);
    // Initialize an array to hold the price package details
    $pricePackageDetails = [];

    // Loop through each item to prepare data for insertion
    foreach ($validatedData['items'] as $item) {
        // Get the first available stock transaction detail for the item
        $stockDetail = StockTransactionDetail::whereHas('receivedGoodDetail', function ($query) use ($item) {
            $query->where('item_id', $item['item_id']);
        })
            ->orderBy('created_at', 'asc') // FIFO: Oldest stock first
            ->first(); // Take only the first one for FIFO

        if ($stockDetail) {
            $existingItem = PricePackageDetail::where('price_package_id', $pricePackageId)
            ->where('stock_transaction_detail_id', $stockDetail->id)
            ->exists();

        if ($existingItem) {
            // If the item already exists, return an error
            return redirect()->back()
                ->with('error', 'This item has already been added to the price package.');
        }
            
            // Add the stock transaction detail if it's not already stored
            $pricePackageDetails[] = [
                'price_package_id' => $pricePackageId,
                'stock_transaction_detail_id' => $stockDetail->id,
                'discount' => $item['discount'] ?? 0,
                'price' => $item['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
    }

    // Insert all price package details into the database
    if (!empty($pricePackageDetails)) {
        PricePackageDetail::insert($pricePackageDetails);
    }

    return redirect()->route('packages.index')
        ->with('success', 'Price package details saved successfully!');
}




    public function destroy(string $id)
    {
        //
    }
}
