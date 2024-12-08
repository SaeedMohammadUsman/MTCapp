<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventoryBatch;
use App\Models\Item;
use App\Models\BatchItem;

class BatchItemSeeder extends Seeder
{
    public function run()
    {
        // Fetch items to associate batches with (for the example, take 5 items)
        $items = Item::take(5)->get();

        // Get all batches
        $batches = InventoryBatch::all();

        // Loop through each batch and item to insert into the pivot table
        foreach ($batches as $batch) {
            foreach ($items as $item) {
                BatchItem::create([
                    'batch_id' => $batch->id,
                    'item_id' => $item->id,
                    'cost_price' => 200.00,  // Example cost price
                    'selling_price' => 250.00, // Example selling price
                    'quantity' => 50,  // Example quantity
                    'expiration_date' => '2025-01-01', // Example expiration date
                ]);
            }
        }
    }
}
