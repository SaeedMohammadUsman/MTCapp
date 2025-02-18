<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerOrder;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CustomerReportController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('reports.customer_report', compact('customers'));
    }

    public function filter(Request $request)
    {
        $customerId = $request->input('customer_id');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = CustomerOrder::with(['customer', 'orderItems']) // Load items with the order
            ->where('status', 'completed');

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        if ($dateFrom && $dateTo) {
            $query->whereBetween('order_date', [
                Carbon::parse($dateFrom)->startOfDay(),
                Carbon::parse($dateTo)->endOfDay()
            ]);
        }

        $orders = $query->get();

        // Calculate total amount for each order and overall total amount
        $totalAmount = 0;
        foreach ($orders as $order) {
            $orderTotal = $order->orderItems->sum(function ($item) {
                return $item->quantity * $item->price; // Calculate total for each item
            });
            $order->total_amount = $orderTotal; // Set the calculated total amount for the order
            $totalAmount += $orderTotal; // Add to overall total amount
        }


        // Fetch transactions for the selected customer
        $transactions = Transaction::where('customer_id', $customerId)
            ->where('transaction_type', 'income')
            ->where('source', 'customer_payment_received')
            ->get();

        // Calculate total payments made by the customer
        $totalPaid = $transactions->sum('amount');
        
        return response()->json([
            'orders' => $orders,
            'total_amount' => $totalAmount,
            'total_paid' => $totalPaid,
            'remaining_balance' => $totalAmount - $totalPaid,
            'transactions' => $transactions
        ]);
    }
}
