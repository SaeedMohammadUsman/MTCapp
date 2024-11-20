<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
class VendorController extends Controller
{
    public function index(Request $request)
    {
        // Search by ID, Company Name (EN or FA), or Country
        $vendors = Vendor::when($request->input('search'), function ($query, $search) {
            $query->where('id', $search)
                ->orWhere('company_name_en', 'like', "%$search%")
                ->orWhere('company_name_fa', 'like', "%$search%")
                ->orWhere('country_name', 'like', "%$search%");
        })->paginate(7); // Paginate results (7 per page)

        return view('vendors.index', compact('vendors'));
    }

    // Show the form for creating a new vendor
    public function create()
    {
        return view('vendors.create');
    }

    // Store a new vendor in the database
    public function store(Request $request)
    {
        $request->validate([
            'company_name_en' => 'required|string|max:55',
            'company_name_fa' => 'required|string|max:55',
            'email' => 'required|email|unique:vendors,email',
            'phone_number' => 'required|string|max:15',
            'address_en' => 'required|string|max:55',
            'address_fa' => 'required|string|max:55',
            'country_name' => 'required|string|in:Pakistan,India,Iran',
        ]);

        Vendor::create($request->all());

        return redirect()->route('vendors.index')->with('success', 'Vendor created successfully!');
    }

    // Show the form for editing a specific vendor
    public function edit(Vendor $vendor)
    {
        return view('vendors.edit', compact('vendor'));
    }

    // Update the specified vendor
    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'company_name_en' => 'required|string|max:55',
            'company_name_fa' => 'required|string|max:55',
            'email' => 'required|email|unique:vendors,email,' . $vendor->id,
            'phone_number' => 'required|string|max:15',
            'address_en' => 'required|string|max:55',
            'address_fa' => 'required|string|max:55',
            'country_name' => 'required|string|in:Pakistan,India,Iran',
        ]);

        $vendor->update($request->all());

        return redirect()->route('vendors.index')->with('success', 'Vendor updated successfully!');
    }
    public function show($id)
    {
        // Find the vendor by ID
        $vendor = Vendor::findOrFail($id);
    
        // Return the view with the vendor details
        return view('vendors.show', compact('vendor'));
    }

    // Delete the specified vendor
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();

        return redirect()->route('vendors.index')->with('success', 'Vendor deleted successfully!');
    }
}


