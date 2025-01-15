<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerOrderController extends Controller
{

    public function index(Request $request)
    {
        // Fetch filter values if available
        $customer_id = $request->get('customer_id'); // Filter by customer ID
        $status = $request->get('status');
        $filter = $request->get('filter'); // 'active', 'trashed'
    
        // Query customer orders with filters and paginate
        $customerOrders = CustomerOrder::query()
            ->when($customer_id, function ($query, $customer_id) {
                return $query->where('customer_id', $customer_id);
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($filter === 'trashed', function ($query) {
                $query->onlyTrashed(); // Show trashed records when 'trashed' is selected
            })
            ->when($filter === 'active', function ($query) {
                $query->whereNull('deleted_at'); // Only active records
            })
            ->latest()
            ->paginate(10);
    
      
        $customers = Customer::all();
    
        $hasTrashed = CustomerOrder::onlyTrashed()->exists();
    
        return view('customer_orders.index', compact('customerOrders', 'customers', 'hasTrashed'));
    }
    
    public function create()
{
    $customers = Customer::with('pricePackages')->get();

    if (request()->ajax()) {
        return response()->json([
            'customers' => $customers
        ]);
    }

    return redirect()->route('customer_orders.index');
}

public function store(Request $request)
{
    // Validate incoming request data
    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'package_id' => 'nullable|exists:price_packages,id',
        'remarks' => 'nullable|string|max:1000',
    ]);

    // Create and save the customer order
    $customerOrder = CustomerOrder::create([
        'customer_id' => $request->customer_id,
        'price_package_id' => $request->package_id,
        'status' => 'pending', 
        'total_amount' => 0,  
        'remarks' => $request->remarks,
        'order_date' => now(),
    ]);

    return response()->json([
        'success' => 'Customer order created successfully!',
        'order' => $customerOrder
    ]);
}

public function show($id){
    $customerOrder = CustomerOrder::with('customer', 'orderItems.item')->findOrFail($id);
    return view('customer_orders.show', compact('customerOrder'));
}
public function edit($id)
{
    $customerOrder = CustomerOrder::with('customer')->findOrFail($id);
    $customers = Customer::all();
    return view('customer_orders.edit', compact('customerOrder', 'customers'));
}


public function update(Request $request, $id)
{
    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'remarks' => 'nullable|string|max:1000',
        'status' => 'required|in:pending,completed',
    ]);

    $customerOrder = CustomerOrder::findOrFail($id);
    $customerOrder->update([
        'customer_id' => $request->customer_id,
        'remarks' => $request->remarks,
        'status' => $request->status,
    ]);

    return redirect()->route('customer_orders.index')->with('success', 'Customer order updated successfully!');
}

public function getPackages($customerId)
{
    // Fetch customer with their related price packages
    $customer = Customer::with('pricePackages')->findOrFail($customerId);

    return response()->json([
        'packages' => $customer->pricePackages
    ]);
}


public function destroy(CustomerOrder $customerOrder)
{
    // Soft delete the customer order
    $customerOrder->delete();

 
   return redirect()
            ->route('customer_orders.index')
            ->with('success', 'Package deleted successfully!');

}   
public function restore($id)
{
    // Restore the soft deleted customer order
    $customerOrder = CustomerOrder::onlyTrashed()->findOrFail($id);
    $customerOrder->restore();

    return redirect()
        ->route('customer_orders.index')
        ->with('success', 'Package restored successfully!');

}
}