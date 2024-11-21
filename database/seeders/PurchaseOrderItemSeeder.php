<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseOrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $fakerIR = Faker::create('fa_IR');

        // Get existing purchase order and inventory item IDs
        $purchaseOrderIds = PurchaseOrder::pluck('id')->toArray();
        $inventoryItemIds = InventoryItem::pluck('id')->toArray();

        foreach (range(1, 5) as $index) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $faker->randomElement($purchaseOrderIds),
                'item_id'           => $faker->randomElement($inventoryItemIds),
                'trade_name_en'        => $faker->word(),
                'used_for_en'          => $fakerIR->sentence(),
                'trade_name_fa'        => $faker->word(),
                'used_for_fa'          => $fakerIR->sentence(),
                'size'              => $faker->randomElement(['Small', 'Medium', 'Large']),
                'c_size'            => $faker->randomElement(['Box', 'Bottle', 'Packet']),
                'unit_price'        => $faker->randomFloat(2, 10, 100),
                'quantity'          => $faker->numberBetween(1, 50),
                'total_price'       => $faker->randomFloat(2, 100, 5000),
                'remarks'           => $faker->sentence(),
            ]);
        }
    }
}

