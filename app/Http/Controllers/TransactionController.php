<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\Vendor;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::query();
        if ($request->has ('transaction_type') && $request->input('transaction_type') !==''){
            $transactionType = $request->input('transaction_type');
            $query->where('transaction_type', $transactionType);
        }
        
        if ($request->has('source') && $request->input('source') !==''){
            $source = $request->input('source');
            $query->where('source', $source);
        }
        $transactions = $query->with(['account', 'customer', 'vendor'])
            ->latest()
            ->paginate(10);
         
            $accounts = Account::all();
            $customers = Customer::all();
            $vendors = Vendor::all();
            return view('transactions.index', compact('transactions', 'accounts', 'customers', 'vendors'));
    }
   
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'customer_id' => 'nullable|exists:customers,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'amount' => 'required|numeric|min:0',
            'transaction_type' => 'required|in:income,expense,transfer',
            'source' => 'required|in:payment_to_vendor,salary_payment,daily_expense,payment_to_distributors,customer_payment_received,advance_payment_for_purchasing,transfer_to_sarrafi,transfer_to_cash,miscellaneous_income,other_expense',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        $transaction = Transaction::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Transaction created successfully!',
            'transaction' => $transaction->load(['account', 'customer', 'vendor'])//added
        ]);
    }

    public function edit(Transaction $transaction)
    {
        return response()->json($transaction);
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'customer_id' => 'nullable|exists:customers,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'amount' => 'required|numeric|min:0',
            'transaction_type' => 'required|in:income,expense,transfer',
            'source' => 'required|in:payment_to_vendor,salary_payment,daily_expense,payment_to_distributors,customer_payment_received,advance_payment_for_purchasing,transfer_to_sarrafi,transfer_to_cash,miscellaneous_income,other_expense',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        $transaction->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Transaction updated successfully!',
            'transaction' => $transaction->load(['account', 'customer', 'vendor'])
        ]);
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaction deleted successfully!'
        ]);
    }

    public function restore($id)
    {
        $transaction = Transaction::withTrashed()->findOrFail($id);
        $transaction->restore();

        return response()->json([
            'success' => true,
            'message' => 'Transaction restored successfully!'
        ]);
    }
   
   
   
   
}
