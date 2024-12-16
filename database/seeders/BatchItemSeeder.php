<?php

namespace Database\Seeders;

use App\Models\BatchItem;
use App\Models\InventoryBatch;
use App\Models\Item;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class BatchItemSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Get all items and batches
        $items = Item::all();
        $batches = InventoryBatch::all();

        if ($items->isEmpty() || $batches->isEmpty()) {
            $this->command->warn('No items or batches found. Seeder skipped.');
            return;
        }

        foreach ($batches as $batch) {
            $randomItems = $items->random(rand(1, min(5, $items->count())));
            foreach ($randomItems as $item) {
                if (!BatchItem::where('inventory_batch_id', $batch->id)->where('item_id', $item->id)->exists()) {
                    BatchItem::create([
                        'inventory_batch_id' => $batch->id,
                        'item_id' => $item->id,
                        'cost_price' => $faker->randomFloat(2, 10, 500),
                        'selling_price' => $faker->randomFloat(2, 15, 600),
                        'quantity' => $faker->numberBetween(1, 100),
                        'expiration_date' => $faker->dateTimeBetween('now', '+1 year'),
                    ]);
                } else {
                    $this->command->info("Duplicate skipped: Batch ID {$batch->id}, Item ID {$item->id}");
                }
            }
        }
        
    }
}
