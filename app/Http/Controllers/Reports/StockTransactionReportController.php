<?php
namespace App\Http\Controllers\Reports;

use App\Exports\StockTransactionExport;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class StockTransactionReportController extends Controller
{
    public function index()
    {
        $items = Item::all();
       
        return view('reports.stock_transactions', compact('items'   ));
        
    }


    public function filter(Request $request)
    {
        $itemId = $request->input('item_id');
        $transactionType = $request->input('transaction_type');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
    
        $query = StockTransaction::with(['details' => function ($query) use ($itemId) {
            if ($itemId) {
                $query->where('item_id', $itemId);
            }
            $query->with('item:id,trade_name_en,trade_name_fa');
        }]);
    
        // Apply date range filter if provided
        if ($dateFrom && $dateTo) {
            $query->whereBetween('transaction_date', [
                Carbon::parse($dateFrom)->startOfDay(),
                Carbon::parse($dateTo)->endOfDay()
            ]);
        }
    
        // Filter by transaction type if specified
        if ($transactionType) {
            $type = ($transactionType === 'in') ? 1 : 2;
            $query->where('transaction_type', $type);
        }
    
        // Get transactions and filter out ones with no matching details
        $transactions = $query->get()->filter(function ($transaction) {
            return $transaction->details->isNotEmpty();
        });
    
        // Initialize totals
        $totalIn = 0;
        $totalOut = 0;
    
        // Calculate totals
        foreach ($transactions as $transaction) {
            foreach ($transaction->details as $detail) {
                if ($transaction->transaction_type == 1) {
                    $totalIn += $detail->quantity;
                } else if ($transaction->transaction_type == 2) {
                    $totalOut += $detail->quantity;
                }
            }
        }
    
        // Calculate current stock
        $currentStock = $totalIn - $totalOut;
    
        return response()->json([
            'transactions' => $transactions->values(),
            'total_in' => $totalIn,
            'total_out' => $totalOut,
            'current_stock' => $currentStock
        ]);
    }
    
}
