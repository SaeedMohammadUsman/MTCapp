<?php 
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', null);  // Default search to null
    
        // Query the Category model
        $query = Category::query();
        
        // Apply search filter if provided
        if ($search) {
            $query->where('name_en', 'like', "%{$search}%")
                  ->orWhere('name_fa', 'like', "%{$search}%");
        }
    
        // Apply filter for trashed categories
        if ($request->has('filter') && $request->input('filter') == 'trashed') {
            $categories = $query->onlyTrashed()->paginate(10);
        } else {
            // Show active categories by default
            $categories = $query->paginate(10);
        }
    
        // Return view with categories and search data
        return view('categories.index', compact('categories', 'search'));
    }
    
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_fa' => 'required|string|max:255',
        ]);

        Category::create($request->only('name_en', 'name_fa'));

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category created successfully!');
    }

   
    
    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_fa' => 'required|string|max:255',
        ]);

        $category->update($request->only('name_en', 'name_fa'));

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category deleted successfully!');
    }
  // Add this to your CategoryController
public function restore($id)
{
    $category = Category::onlyTrashed()->findOrFail($id);
    $category->restore();

    return redirect()->route('categories.index')->with('success', 'Category restored successfully.');
}


}

