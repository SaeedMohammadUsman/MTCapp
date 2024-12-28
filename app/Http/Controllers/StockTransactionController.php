<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockTransaction;
use App\Models\StockTransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StockTransactionController extends Controller
{
    /**
     * Display a listing of the stock transactions with various filters.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */


     public function index(Request $request)
     {
      
         $query = StockTransaction::query();
       
        
         if ($request->has('transaction_type') && $request->input('transaction_type') !== '') {
             $transactionType = $request->input('transaction_type');
            
             $query->where('transaction_type', $transactionType);
         }
         
         elseif ($request->has('start_date') && $request->has('end_date') && $request->input('start_date') !== '' && $request->input('end_date') !== '') {
             $startDate = Carbon::parse($request->start_date)->startOfDay(); // Start of day
             $endDate = Carbon::parse($request->end_date)->endOfDay(); // End of day
             // Apply the date range filter
             $query->whereBetween('transaction_date', [$startDate, $endDate]);
         }
         
    
        // Fetch the stock transactions based on the applied filters
        $stockTransactions = $query->with('receivedGood:id,batch_number')
            ->latest()
            ->paginate(10);
    
     
    
    
         // Return the view with filtered stock transactions
         return view('stock_transactions.index', compact('stockTransactions'));
     }
    
     public function show($id)
     {
      
         $stockTransaction = StockTransaction::with([
             'details.item',            // Eager load the 'item' relationship
             'details.receivedGoodDetail'  // Eager load the 'receivedGoodDetail' relationship
         ])
         ->findOrFail($id);
     
        //  dd($stockTransaction->details);
         return view('stock_transactions.show', compact('stockTransaction'));
     }
     
     

     // In StockTransactionController.php

// In StockTransactionController.php

public function getItemsWithTransactions()
{
    $items = StockTransactionDetail::with('item') 
        ->select('received_good_detail_id') 
        ->distinct()
        ->get()
        ->pluck('item'); // Pluck the item from the relationship

    return response()->json($items);
}


public function itemStockDetails(Request $request)
{
    $request->validate([
        'item_id' => 'required|exists:items,id',
    ]);

    $itemId = $request->item_id;

    // Fetch stock transactions related to the item
    $stockTransactions = StockTransaction::with(['details' => function ($query) use ($itemId) {
        $query->where('item_id', $itemId);
    }])
    ->whereHas('details', function ($query) use ($itemId) {
        $query->where('item_id', $itemId);
    })
    ->get();

    // Calculate total quantity and other details
    $totalQuantity = $stockTransactions->sum(function ($transaction) {
        return $transaction->details->sum('quantity');
    });

    return view('item_stock_details', compact('stockTransactions', 'totalQuantity'));
}

}