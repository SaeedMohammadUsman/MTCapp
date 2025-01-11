<?php

namespace App\Http\Controllers;

use App\Models\StockTransaction;
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
             'details.item' // Directly eager load the item relation
         ])->findOrFail($id);
     
         return view('stock_transactions.show', compact('stockTransaction'));
     }
     
    
    
     
  
}