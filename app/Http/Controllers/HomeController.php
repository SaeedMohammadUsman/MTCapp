<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\PurchaseOrder;
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
        return view('home', compact('departmentCount', 'vendorCount','purchaseOrderCount'));
    }
}
