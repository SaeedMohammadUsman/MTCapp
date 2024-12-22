<?php

namespace Database\Seeders;

use App\Models\ReceivedGoodDetail;
use App\Models\ReceivedGood;
use App\Models\Item;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ReceivedGoodDetailSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Get all received goods
        $receivedGoods = ReceivedGood::all();

        foreach ($receivedGoods as $receivedGood) {
            
            $items = Item::inRandomOrder()->take(rand(4, 7))->get();

            foreach ($items as $item) {
                ReceivedGoodDetail::create([
                    'received_good_id' => $receivedGood->id,
                    'item_id' => $item->id,
                    'vendor_price' => $faker->randomFloat(2, 200, 1200), 
                    'quantity' => $faker->numberBetween(20, 600), 
                    'expiration_date' => now()->addYears(rand(1, 4)), 
                ]);
            }
        }
    }
}
