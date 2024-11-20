<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InventoryItemController extends Controller
{
    // Show all inventory items
    public function index(Request $request)
    {
        // Filter inventory items based on search input
        $inventoryItems = InventoryItem::when($request->input('search'), function ($query, $search) {
            $query->where('item_name_en', 'like', "%$search%")
                  ->orWhere('item_name_fa', 'like', "%$search%")
                  ->orWhere('item_code', 'like', "%$search%")
                  ->orWhere('cost_price', 'like', "%$search%")
                  ->orWhere('selling_price', 'like', "%$search%")
                  ->orWhere('quantity_in_stock', 'like', "%$search%");
        })
        ->paginate(10);  // You can adjust the number of results per page
    
        return view('inventory_items.index', compact('inventoryItems'));
    }

    // Show form to create a new inventory item
    public function create()
    {
        return view('inventory_items.create');
    }

    // Store a newly created inventory item
    public function store(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'item_name_en' => 'required|string|max:55',
            'item_name_fa' => 'required|string|max:55',
            'item_code' => 'required|unique:inventory_items,item_code|max:55',
            'cost_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'quantity_in_stock' => 'required|integer',
            'expiration_date' => 'required|date',
            'description_en' => 'nullable|string',
            'description_fa' => 'nullable|string',
        ]);
          // Debug: Check if validation passes
  
    
        // Create the new inventory item
        InventoryItem::create($request->only([
            'item_name_en',
            'item_name_fa',
            'item_code',
            'cost_price',
            'selling_price',
            'quantity_in_stock',
            'expiration_date',
            'description_en',
            'description_fa',
        ]));
    
        // Redirect with success message
        return redirect()->route('inventory_items.index')->with('success', 'Inventory Item created successfully.');
    }
    

    // Show a specific inventory item
    public function show($id)
    {
        $inventoryItem = InventoryItem::findOrFail($id);
        return view('inventory_items.show', compact('inventoryItem'));
    }

    // Show form to edit an existing inventory item
    public function edit($id)
    {
        $inventoryItem = InventoryItem::findOrFail($id);
        return view('inventory_items.edit', compact('inventoryItem'));
    }


    public function update(Request $request, $id)
    {
        // Validate incoming request
        $request->validate([
            'item_name_en' => 'required|string|max:55',
            'item_name_fa' => 'required|string|max:55',
            'item_code' => 'required|unique:inventory_items,item_code,' . $id . '|max:55', // Ensure unique for the specific record
            'cost_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'quantity_in_stock' => 'required|integer',
            'expiration_date' => 'required|date',
            'description_en' => 'nullable|string',
            'description_fa' => 'nullable|string',
        ]);
          // Debug: Check if validation passes
      Log::info('Create Request Data:', $request->all());
    
        // Find and update the inventory item
        $inventoryItem = InventoryItem::findOrFail($id);
        
        // Update the fields with validated data
        $inventoryItem->update($request->only([
            'item_name_en',
            'item_name_fa',
            'item_code',
            'cost_price',
            'selling_price',
            'quantity_in_stock',
            'expiration_date',
            'description_en',
            'description_fa',
        ]));
    
        // Redirect with success message
        return redirect()->route('inventory_items.index')->with('success', 'Inventory Item updated successfully.');
    }
    
 

    // Delete an inventory item
    public function destroy($id)
    {
        $inventoryItem = InventoryItem::findOrFail($id);
        $inventoryItem->delete();

        return redirect()->route('inventory_items.index')->with('success', 'Inventory Item deleted successfully.');
    }
}
