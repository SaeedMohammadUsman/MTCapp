<?php

namespace Database\Seeders;

use App\Models\BatchItem;
use App\Models\InventoryBatch;
use App\Models\Item;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BatchItemSeeder extends Seeder
{
    public function run()
    {
       // DB::table('inventory_batches')->truncate();  // This will remove all existing data

        // Fetch items to associate batches with (for the example, take 5 items)
        $items = Item::take(5)->get();
        // Get all batches
        $batches = InventoryBatch::all();
        $faker = Faker::create();

        // Loop through each batch and item to insert into the pivot table
        foreach ($batches as $batch) {
            foreach ($items as $item) {
                // Generate a random cost price first
                $costPrice = $faker->randomFloat(2, 50, 500); // Random cost price between 50 and 500
    
                // Generate a selling price greater than the cost price (e.g., cost price + a random value between 50 and 200)
                $sellingPrice = $costPrice + $faker->randomFloat(2, 50, 200); // Selling price is always greater than cost price
    
                BatchItem::create([
                    'inventory_batch_id' => $batch->id,
                    'item_id' => $item->id,
                    'cost_price' => $costPrice,
                    'selling_price' => $sellingPrice,  // Ensure selling price is greater than cost price
                    'quantity' => $faker->numberBetween(10, 100),  // Random quantity between 10 and 100
                    'expiration_date' => $faker->date('Y-m-d', '2025-12-31'),  // Random expiration date up to the end of 2025
                ]);
            }
        }
        
    }
}
