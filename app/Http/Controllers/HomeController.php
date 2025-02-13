<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Customer;
use App\Models\CustomerOrder;
use App\Models\Department;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\ReceivedGood;
use App\Models\StockTransaction;
use App\Models\Transaction;
use App\Models\Vendor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $departmentCount = Department::count();
        $vendorCount = Vendor::count();
        $purchaseOrderCount = PurchaseOrder::count();
        $customerCount = Customer::count();
        $customerOrderCount = CustomerOrder::count();
        $itemCount = Item::count();
        $rescivedGoodsCouunt = ReceivedGood::where('is_finalized', true)->count();
        $stockTransactionsCount = StockTransaction::count();
        $accountCount = Account::count();
        $transactionCount= Transaction::count();
        return view('home', compact('departmentCount', 'vendorCount','purchaseOrderCount', 'customerCount','customerOrderCount','itemCount','rescivedGoodsCouunt','stockTransactionsCount','accountCount','transactionCount'));
    }
}
