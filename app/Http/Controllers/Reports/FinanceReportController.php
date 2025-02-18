<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FinanceReportExport;

class FinanceReportController extends Controller
{
    public function index()
    {
        $accounts = Account::all();
        return view('reports.finance_report', compact('accounts'));
    }

    public function filter(Request $request)
    {
        $accountId = $request->input('account_id');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $source = $request->input('source');

        $query = Transaction::with('account');

        if ($accountId) {
            $query->where('account_id', $accountId);
        }

        if ($dateFrom && $dateTo) {
            $query->whereBetween('transaction_date', [
                Carbon::parse($dateFrom)->startOfDay(),
                Carbon::parse($dateTo)->endOfDay()
            ]);
        }

        if ($source) {
            $query->where('source', $source);
        }

        $transactions = $query->get();

        // Calculate totals
        $totalIncome = $transactions->where('transaction_type', 'income')->sum('amount');
        $totalExpense = $transactions->where('transaction_type', 'expense')->sum('amount');

        return response()->json([
            'transactions' => $transactions,
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
        ]);
    }

  
}