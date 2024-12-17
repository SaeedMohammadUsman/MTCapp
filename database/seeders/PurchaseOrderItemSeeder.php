<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class PurchaseOrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        // Fetch all items from the items table
        $items = Item::pluck('id')->toArray();

        // Fetch all purchase orders
        $purchaseOrders = PurchaseOrder::all();

        foreach ($purchaseOrders as $purchaseOrder) {
            // Generate 4-10 items for each order
            $orderItems = [];

            foreach (range(1, rand(4, 10)) as $index) {
                $orderItems[] = [
                    'purchase_order_id' => $purchaseOrder->id,
                    'item_id'           => $items[array_rand($items)], // Randomly assign an item from the items table
                    'quantity' => $faker->boolean(80) ? $faker->numberBetween(100, 999) : $faker->numberBetween(1000, 2000),

                    'remarks' => $faker->boolean(70) ? $faker->sentence() : null,

                ];
            }

            // Insert the items into the purchase_order_items table
            PurchaseOrderItem::insert($orderItems);
        }
    }
}
