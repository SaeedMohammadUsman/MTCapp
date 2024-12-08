<?php 
namespace App\Http\Controllers;

use App\Models\InventoryBatch;
use Illuminate\Http\Request;

class InventoryBatchController extends Controller
{
    // Display the list of batches with pagination and filter options
    public function index(Request $request)
    {
        // Fetch filter values if available
        $batch_number = $request->get('batch_number');
        $remark = $request->get('remark');

        // Query batches with filters and paginate
        $batches = InventoryBatch::query()
            ->when($batch_number, function ($query, $batch_number) {
                return $query->where('batch_number', 'like', "%{$batch_number}%");
            })
            ->when($remark, function ($query, $remark) {
                return $query->where('remark', 'like', "%{$remark}%");
            })
            ->latest()
            ->paginate(10);

        // Return batches to view with flash success or error messages
        return view('batches.index', compact('batches'));
    }

    // View a specific batch and its items
    // public function show($id)
    // {
    //     $batch = InventoryBatch::with('items.item')->findOrFail($id); 
    //     return view('batches.show', compact('batch'));
    // }
    public function show($id)
    {
        // Eager load 'items' relationship in 'BatchItem' with the 'item' relationship
        $batch = InventoryBatch::with('items')->findOrFail($id); 
        // dd($batch);
        // dd($batch->items); 
        return view('batches.show', compact('batch'));
    }
    
  

    // Show the form for creating a new batch
    public function create()
    {
        return view('batches.create');
    }

    // Store a newly created batch in the database
    public function store(Request $request)
    {
        $request->validate([
            'batch_number' => 'required|unique:inventory_batches',
            'remark' => 'nullable|string',
        ]);

        InventoryBatch::create([
            'batch_number' => $request->batch_number,
            'remark' => $request->remark,
        ]);

        return redirect()->route('batches.index')->with('success', 'Batch created successfully!');
    }

    // Show the form for editing the specified batch
    public function edit($id)
    {
        $batch = InventoryBatch::findOrFail($id);
        return view('batches.edit', compact('batch'));
    }

    // Update the specified batch in the database
    public function update(Request $request, $id)
    {
        $request->validate([
            'batch_number' => 'required|unique:inventory_batches,batch_number,' . $id,
            'remark' => 'nullable|string',
        ]);

        $batch = InventoryBatch::findOrFail($id);
        $batch->update([
            'batch_number' => $request->batch_number,
            'remark' => $request->remark,
        ]);

        return redirect()->route('batches.index')->with('success', 'Batch updated successfully!');
    }
    
      // Delete a batch and its associated items
    //   public function destroy($id)
    //   {
    //       $batch = InventoryBatch::findOrFail($id);
    //       $batch->delete();
    //       return redirect()->route('batches.index')->with('success', 'Batch deleted successfully!');
    //   }
    
    public function destroy($id)
{
    try {
        $batch = InventoryBatch::findOrFail($id);
        $batch->delete();
        return redirect()->route('batches.index')->with('success', 'Batch deleted successfully!');
    } catch (\Exception $e) {
        return redirect()->route('batches.index')->with('error', 'Error deleting batch: ' . $e->getMessage());
    }
}

      
    
  
}
