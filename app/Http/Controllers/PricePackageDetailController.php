<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\PricePackage;
use App\Models\PricePackageDetail;
use App\Models\StockTransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PricePackageDetailController extends Controller
{
   
    public function create($pricePackageId)
{
    $pricePackage = PricePackage::findOrFail($pricePackageId);

    // Fetch all items with their relationships to display in the dropdown
    $items = Item::with(['category'])
        ->join('received_goods_details', 'items.id', '=', 'received_goods_details.item_id')
        ->join('stock_transaction_details', 'received_goods_details.id', '=', 'stock_transaction_details.received_good_detail_id')
        ->select('items.id', 'items.trade_name_en', 'items.trade_name_fa', 'stock_transaction_details.arrival_price')
        ->distinct()
        ->get();

    // Pass the price package ID and items to the view
    return view('packages.details.create', compact('pricePackage', 'items'));

}

public function store(Request $request, $pricePackageId)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'items' => 'required|array',
        'items.*.stock_transaction_detail_id' => 'required|exists:stock_transaction_details,id',
       
        'items.*.discount' => 'nullable|numeric|min:0|max:100',
        'items.*.price' => 'required|numeric|min:0',
    ]);

    $pricePackageDetails = [];

    // Process each item and prepare the data for insertion
    foreach ($validatedData['items'] as $item) {
        $finalPrice = $item['price'];
        $discount = $item['discount'] ?? 0;
         
        // Find the corresponding StockTransactionDetail
        $stockTransactionDetail = StockTransactionDetail::find($item['stock_transaction_detail_id']);
        
        if ($stockTransactionDetail) {
            // Prepare the price package details for insertion
            $pricePackageDetails[] = [
                'price_package_id' => $pricePackageId,
                'stock_transaction_detail_id' => $stockTransactionDetail->id,
               
                'discount' => $discount,
                'price' => $finalPrice,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        } else {
            // If StockTransactionDetail is not found, skip this item
            continue;
        }
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
//         'items.*.stock_transaction_detail_id' => 'required|exists:stock_transaction_details,id',
//         'items.*.discount' => 'nullable|numeric|min:0|max:100',
//         'items.*.price' => 'required|numeric|min:0',
//     ]);

//     $pricePackageDetails = [];

//     // Process each item and prepare the data for insertion
//     foreach ($validatedData['items'] as $item) {
//         $finalPrice = $item['price'];
//         $discount = $item['discount'] ?? 0;

      

//         // Find the corresponding StockTransactionDetail
//         $stockTransactionDetail = StockTransactionDetail::where('id', $item['stock_transaction_detail_id'])->first();
        
//         if ($stockTransactionDetail) {
//             // Prepare the price package details for insertion
//             $pricePackageDetails[] = [
//                 'price_package_id' => $pricePackageId,
//                 'stock_transaction_detail_id' => $stockTransactionDetail->id,
//                 'discount' => $discount,
//                 'price' => $finalPrice,
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ];
//         } else {
//             // If StockTransactionDetail is not found, skip this item
//             continue;
//         }
//     }

//     // Insert all price package details into the database
//     if (!empty($pricePackageDetails)) {
//         PricePackageDetail::insert($pricePackageDetails);
//     }

//     return redirect()->route('packages.index')
//         ->with('success', 'Price package details saved successfully!');
// }




// public function store(Request $request, $pricePackageId)
// {
//     $validatedData = $request->validate([
//         'items' => 'required|array',
//         'items.*.item_id' => 'required|exists:items,id',
//         'items.*.arrival_price' => 'required|numeric|min:0',
//         'items.*.discount' => 'nullable|numeric|min:0|max:100',
//     ]);

//     $pricePackageDetails = [];

//     foreach ($validatedData['items'] as $item) {
//         $finalPrice = $item['arrival_price'] - ($item['arrival_price'] * ($item['discount'] ?? 0) / 100);
//         $pricePackageDetails[] = [
//             'price_package_id' => $pricePackageId,
//             'stock_transaction_detail_id' => StockTransactionDetail::where('received_good_detail_id', $item['item_id'])->first()->id,
//             'discount' => $item['discount'] ?? 0,
//             'price' => $finalPrice,
//             'created_at' => now(),
//             'updated_at' => now(),
//         ];
//     }

//     // Batch insert all rows into the price_package_details table
//     PricePackageDetail::insert($pricePackageDetails);

//     return redirect()->route('packages.index')
//         ->with('success', 'Price package details saved successfully!');
// }


    /**
     * Display the specified resource.
     */
   
    public function destroy(string $id)
    {
        //
    }
}
