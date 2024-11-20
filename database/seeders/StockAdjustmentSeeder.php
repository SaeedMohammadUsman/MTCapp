<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use App\Models\StockAdjustment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class StockAdjustmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $fakerIR= Faker::create('fa_IR'); 

        // Ensure we have items to associate adjustments with
        $items = InventoryItem::all();
        if ($items->isEmpty()) {
            $this->command->info('No Inventory Items found. Run InventoryItemSeeder first.');
            return;
        }

        foreach ($items->take(5) as $item) {
            StockAdjustment::create([
                'item_id' => $item->id,
                'adjustment_type_en' => $faker->randomElement(['damaged', 'returns']),
                'adjustment_type_fa' => $fakerIR->randomElement(['خرابی', 'بازگشت']),
                'quantity' => $faker->numberBetween(1, 10),
                'reason_en' => $faker->sentence,
                'reason_fa' => $fakerIR->sentence,
            ]);
        }
    }
}
