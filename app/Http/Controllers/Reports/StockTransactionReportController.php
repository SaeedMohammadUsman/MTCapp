<?php

namespace App\Http\Controllers\Reports;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockTransaction;
use App\Models\StockTransactionDetail;
use App\Models\Item;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StockTransactionExport;

class StockTransactionReportController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::select('id', 'trade_name_en', 'trade_name_fa')->get();

        $query = StockTransaction::with(['details.item'])
            ->when($request->item_id, function ($q) use ($request) {
                return $q->whereHas('details', function ($query) use ($request) {
                    $query->where('item_id', $request->item_id);
                });
            })
            ->when($request->transaction_type, function ($q) use ($request) {
                return $q->where('transaction_type', $request->transaction_type);
            })
            ->when($request->start_date && $request->end_date, function ($q) use ($request) {
                return $q->whereBetween('transaction_date', [$request->start_date, $request->end_date]);
            })
            ->orderBy('transaction_date', 'desc')
            ->get();

        return view('reports.stock_transactions', compact('items', 'query'));
    }

    public function export(Request $request)
    {
        return Excel::download(new StockTransactionExport($request), 'stock_transactions.xlsx');
    }
    
}
