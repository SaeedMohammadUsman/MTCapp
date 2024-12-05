<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', null);  // Default search to null
        $categoryFilter = $request->input('category_id', null);  // Category filter from dropdown
        $trashedFilter = $request->input('filter', null); // Handle trashed filter

        // Start the query for Item model
        $query = Item::query();

        // Apply search filter if provided (search by trade name in English or Farsi)
        if ($search) {
            $query->where('trade_name_en', 'like', "%{$search}%")
                  ->orWhere('trade_name_fa', 'like', "%{$search}%");
        }

        // Apply category filter if provided
        if ($categoryFilter) {
            $query->where('category_id', $categoryFilter);
        }

        // Handle trashed items if 'filter' is set to 'trashed'
        if ($trashedFilter === 'trashed') {
            $items = $query->onlyTrashed()->paginate(10);
        } else {
            // Show active items by default
            $items = $query->paginate(10);
        }

        // Fetch all categories for the dropdown filter
        $categories = Category::all();

        // Return the view with filtered items and category dropdown
        return view('items.index', compact('items', 'categories', 'search', 'categoryFilter'));
    }

    /**
     * Show the form for creating a new item.
     */
    // public function create()
    // {
    //     dd('Create method is being called');
    //     // Fetch all categories for dropdown selection
    //     $categories = Category::all();
    //     return view('items.create', compact('categories'));
    // }
    public function create()
    {
        return "Create method is working!";
    }
    
    /**
     * Store a newly created item in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_code' => 'required|unique:items,item_code',
            'trade_name_en' => 'required|string|max:255',
            'trade_name_fa' => 'required|string|max:255',
            'used_for_en' => 'required|string|max:255',
            'used_for_fa' => 'required|string|max:255',
            'size' => 'required|string|max:100',
            'description_en' => 'required|string',
            'description_fa' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Create the new item
        Item::create($request->only([
            'item_code', 'trade_name_en', 'trade_name_fa', 'used_for_en', 'used_for_fa', 'size', 
            'description_en', 'description_fa', 'category_id'
        ]));

        return redirect()
            ->route('items.index')
            ->with('success', 'Item created successfully!');
    }

    /**
     * Display the specified item.
     */
    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified item.
     */
    public function edit(Item $item)
    {
        $categories = Category::all();
        return view('items.edit', compact('item', 'categories'));
    }

    /**
     * Update the specified item in storage.
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'trade_name_en' => 'required|string|max:255',
            'trade_name_fa' => 'required|string|max:255',
            'used_for_en' => 'required|string|max:255',
            'used_for_fa' => 'required|string|max:255',
            'size' => 'required|string|max:100',
            'description_en' => 'required|string',
            'description_fa' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Update the item with the validated data
        $item->update($request->only([
            'trade_name_en', 'trade_name_fa', 'used_for_en', 'used_for_fa', 'size', 
            'description_en', 'description_fa', 'category_id'
        ]));

        return redirect()
            ->route('items.index')
            ->with('success', 'Item updated successfully!');
    }

    /**
     * Soft delete the specified item.
     */
    public function destroy(Item $item)
    {
        $item->delete();  // Soft delete

        return redirect()
            ->route('items.index')
            ->with('success', 'Item deleted successfully!');
    }

    /**
     * Restore a soft-deleted item.
     */
    public function restore($id)
    {
        $item = Item::onlyTrashed()->findOrFail($id);
        $item->restore();  // Restore the item

        return redirect()->route('items.index')->with('success', 'Item restored successfully!');
    }
}
