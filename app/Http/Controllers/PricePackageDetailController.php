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
        // Log::info("Fetching Price Package with ID: {$pricePackageId}");

        $pricePackage = PricePackage::findOrFail($pricePackageId);
        // Log::info('Price Package Data:', $pricePackage->toArray());

        // Fetch all items for dropdown selection
        $items = Item::select('id', 'trade_name_en', 'trade_name_fa')->get();
        $latestPrices = [];

        // Log::info('Total Items Fetched: ' . $items->count());

        // Fetch latest prices for all items
        foreach ($items as $item) {
            $latestPriceDetail = StockTransactionDetail::where('item_id', $item->id)
                ->orderByDesc('created_at')
                ->first();
            $latestPrices[$item->id] = $latestPriceDetail ? $latestPriceDetail->price : null;
        }

        return view('packages.details.create', compact('pricePackage', 'items', 'latestPrices'));
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
        $existingItem = PricePackageDetail::where('price_package_id', $pricePackageId)
            ->where('item_id', $item['item_id'])
            ->exists();

        if ($existingItem) {
            // If the item already exists, return an error
            return redirect()->back()
                ->with('error', 'This item has already been added to the price package.');
        }

        // Add the item details to the array
        $pricePackageDetails[] = [
            'price_package_id' => $pricePackageId,
            'item_id' => $item['item_id'],
            'discount' => $item['discount'] ?? 0,
            'price' => $item['price'],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    // Insert all price package details into the database
    if (!empty($pricePackageDetails)) {
        PricePackageDetail::insert($pricePackageDetails);
    }

    return redirect()->route('packages.index')
        ->with('success', 'Price package details saved successfully!');
}

    // public function store(Request $request, $pricePackageId)
    // {
    //     // Validate the incoming request data
    //     $validatedData = $request->validate([
    //         'items' => 'required|array',
    //         'items.*.item_id' => 'required|exists:items,id',
    //         'items.*.discount' => 'nullable|numeric|min:0|max:100',
    //         'items.*.price' => 'required|numeric|min:0',
    //     ]);
    //     // Initialize an array to hold the price package details
    //     $pricePackageDetails = [];

    //     // Loop through each item to prepare data for insertion
    //     foreach ($validatedData['items'] as $item) {
    //         // Get the first available stock transaction detail for the item
    //         $stockDetail = StockTransactionDetail::whereHas('receivedGoodDetail', function ($query) use ($item) {
    //             $query->where('item_id', $item['item_id']);
    //         })
    //             ->orderBy('created_at', 'asc') // FIFO: Oldest stock first
    //             ->first(); // Take only the first one for FIFO

    //         if ($stockDetail) {
    //             $existingItem = PricePackageDetail::where('price_package_id', $pricePackageId)
    //             ->where('stock_transaction_detail_id', $stockDetail->id)
    //             ->exists();

    //         if ($existingItem) {
    //             // If the item already exists, return an error
    //             return redirect()->back()
    //                 ->with('error', 'This item has already been added to the price package.');
    //         }
                
    //             // Add the stock transaction detail if it's not already stored
    //             $pricePackageDetails[] = [
    //                 'price_package_id' => $pricePackageId,
    //                 'stock_transaction_detail_id' => $stockDetail->id,
    //                 'discount' => $item['discount'] ?? 0,
    //                 'price' => $item['price'],
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ];
    //         }
    //     }

    //     // Insert all price package details into the database
    //     if (!empty($pricePackageDetails)) {
    //         PricePackageDetail::insert($pricePackageDetails);
    //     }

    //     return redirect()->route('packages.index')
    //         ->with('success', 'Price package details saved successfully!');
    // }

        public function destroy($id)
    {
        $pricePackageDetail = PricePackageDetail::findOrFail($id);
        $pricePackageDetail->delete();
    
        return redirect()->back()->with('success', 'Item deleted successfully!');
    }
}
