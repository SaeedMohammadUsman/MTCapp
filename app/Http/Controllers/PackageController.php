<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\PricePackage;
use App\Models\PricePackageDetail;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $statusFilter = $request->input('status', null);
        $customerFilter = $request->input('customer_id', null);
        $customers = Customer::all();
        $packages = PricePackage::query()
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%");
            })
            ->when($statusFilter === 'inactive', function ($query) {
                return $query->onlyTrashed(); // Show trashed packages
            })
            ->when($statusFilter === 'active', function ($query) {
                return $query->whereNull('deleted_at'); // Only active packages
            })->when($customerFilter, function ($query, $customerFilter) {
                return $query->where('customer_id', $customerFilter);
            })
            ->latest()
            ->paginate(10);

        // Check if there are trashed records
        $hasTrashed = PricePackage::onlyTrashed()->exists();

        return view('packages.index', compact('packages', 'search', 'hasTrashed', 'customers'));
    }

    /**
     * Show the form for creating a new package.
     */
    public function create()
    {
        $customers = Customer::all();  // Get all customers
        return view('packages.create', compact('customers'));
    }

    /**
     * Store a newly created package in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
        ]);

        PricePackage::create($request->only(['title', 'customer_id']));

        if ($request->ajax()) {
            return response()->json(['success' => 'Package created successfully!']);
        }

        // return redirect()
        //     ->route('packages.index')
        //     ->with('success', 'Package created successfully!');
    }
    
    // public function show($id)
    // {
    //     $package = PricePackage::with('customer')->findOrFail($id);
    
    //     // Fetch price package details along with item information
    //     $pricePackageDetails = PricePackageDetail::where('price_package_id', $id)
    //         ->with('item:id,trade_name_en,trade_name_fa')
    //         ->get()
    //         ->map(function ($detail) {
    //             return [
    //                 'trade_name_en' => $detail->item->trade_name_en,
    //                 'trade_name_fa' => $detail->item->trade_name_fa,
    //                 'arrival_price' => $detail->price,
    //                 'discount' => $detail->discount,
    //                 'final_price' => $detail->price - ($detail->price * ($detail->discount / 100)),
    //             ];
    //         });
    
    //     return view('packages.show', compact('package', 'pricePackageDetails'));
    // }


    public function show($id)
{
    $package = PricePackage::with('customer')->findOrFail($id);

    // Fetch price package details along with item information
    $pricePackageDetails = PricePackageDetail::where('price_package_id', $id)
        ->with('item:id,trade_name_en,trade_name_fa')
        ->get();

    return view('packages.show', compact('package', 'pricePackageDetails'));
}



    public function destroy(PricePackage $package)
    {
        $package->delete();  // Soft delete

        return redirect()
            ->route('packages.index')
            ->with('success', 'Package deleted successfully!');
    }

    /**
     * Restore a soft-deleted package.
     */
    public function restore($id)
    {
        $package = PricePackage::onlyTrashed()->findOrFail($id);
        $package->restore();  // Restore the package

        return redirect()->route('packages.index')->with('success', 'Package restored successfully!');
    }
}
