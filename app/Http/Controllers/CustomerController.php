<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenAfghanistan\Provinces\Models\Province;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       
        $provinces = Province::all();
        $search = $request->input('search', null);
        $provinceFilter = $request->input('province_id', null);
        $statusFilter = $request->input('status', null);

        $customers = Customer::query()
            ->when($search, function ($query, $search) {
                return $query->where('customer_name_en', 'like', "%{$search}%")
                             ->orWhere('customer_name_fa', 'like', "%{$search}%");
            })
            ->when($provinceFilter, function ($query, $provinceFilter) {
                return $query->whereHas('district.province', function ($q) use ($provinceFilter) {
                    $q->where('id', $provinceFilter);
                });
            })
            ->when($statusFilter === 'inactive', function ($query) {
                return $query->onlyTrashed(); // Show trashed customers
            })
            ->when($statusFilter === 'active', function ($query) {
                return $query->whereNull('deleted_at'); // Only active customers
            })
            ->latest()
            ->paginate(10);
    
        // Check if there are trashed records
        $hasTrashed = Customer::onlyTrashed()->exists();
    
        return view('customers.index', compact('customers', 'provinces', 'search', 'provinceFilter', 'hasTrashed'));
    }
    
    
    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        // Fetch all provinces for dropdown selection
        $provinces = Province::all();
        return view('customers.create', compact('provinces'));
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name_en' => 'required|string|max:255',
            'customer_name_fa' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
            'address' => 'nullable|string',
            'customer_phone' => 'required|string|unique:customers,customer_phone',
            'email' => 'nullable|email|unique:customers,email',
            'status' => 'required|in:active,inactive',
        ]);

        // Create the new customer
        Customer::create($request->only([
            'customer_name_en', 'customer_name_fa', 'district_id', 'address', 
            'customer_phone', 'email', 'status'
        ]));

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer created successfully!');
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer)
    {
        $provinces = Province::all();
        return view('customers.edit', compact('customer', 'provinces'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'customer_name_en' => 'required|string|max:255',
            'customer_name_fa' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
            'address' => 'nullable|string',
            'customer_phone' => 'required|string|unique:customers,customer_phone,' . $customer->id,
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'status' => 'required|in:active,inactive',
        ]);

        // Update the customer with the validated data
        $customer->update($request->only([
            'customer_name_en', 'customer_name_fa', 'district_id', 'address',
            'customer_phone', 'email', 'status'
        ]));

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer updated successfully!');
    }

    /**
     * Soft delete the specified customer.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();  // Soft delete

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer deleted successfully!');
    }

    /**
     * Restore a soft-deleted customer.
     */
    public function restore($id)
    {
        $customer = Customer::onlyTrashed()->findOrFail($id);
        $customer->restore();  // Restore the customer

        return redirect()->route('customers.index')->with('success', 'Customer restored successfully!');
    }
}
