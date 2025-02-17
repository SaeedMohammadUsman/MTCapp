<?php
namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockTransaction;
use App\Models\Item;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StockTransactionExport;

class StockTransactionReportController extends Controller
{
    public function index()
    {
        $items = Item::all();
        $transactions = StockTransaction::with('details.item')->get();
        return view('reports.stock_transactions', compact('items','transactions'));
        
    }

    public function filter(Request $request)
    {
        $itemId = $request->input('item_id');
        $transactionType = $request->input('transaction_type');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = StockTransaction::query();

        if ($itemId) {
            $query->whereHas('details', function ($q) use ($itemId) {
                $q->where('item_id', $itemId);
            });
        }

        if ($transactionType) {
            $query->where('transaction_type', $transactionType);
        }

        if ($dateFrom && $dateTo) {
            $query->whereBetween('transaction_date', [$dateFrom, $dateTo]);
        }

        $transactions = $query->with('details.item')->get();

        $totalIn = $transactions->where('transaction_type', 'in')->sum('quantity');
        $totalOut = $transactions->where('transaction_type', 'out')->sum('quantity');
        $currentStock = $totalIn - $totalOut;

        return response()->json([
            'transactions' => $transactions,
            'total_in' => $totalIn,
            'total_out' => $totalOut,
            'current_stock' => $currentStock
        ]);
    }

    public function export(Request $request)
    {
        $transactions = StockTransaction::with('details.item')->get()->map(function ($transaction) {
            return [
                'transaction_date' => $transaction->transaction_date,
                'item_name' => optional($transaction->details->first()->item)->trade_name_en ?? 'N/A',
                'transaction_type' => $transaction->transaction_type,
                'quantity' => $transaction->details->sum('quantity'),
            ];
        });
        
        return Excel::download(new StockTransactionExport($transactions), 'stock_tracking.xlsx');
        
    }
    
}
