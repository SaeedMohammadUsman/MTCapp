<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class InventoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
            $faker = Faker::create();
            $fakerIR= Faker::create('fa_IR'); 
            for ($i = 0; $i < 5; $i++) {
                InventoryItem::create([
                    'item_name_en' => $faker->word,
                    'item_name_fa' => $fakerIR->word,
                    'item_code' => $faker->unique()->numerify('ITEM###'),
                    'cost_price' => $faker->randomFloat(2, 100, 500),
                    'selling_price' => $faker->randomFloat(2, 500, 1000),
                    'quantity_in_stock' => $faker->numberBetween(10, 100),
                    'expiration_date' => $faker->dateTimeBetween('now', '+2 years'),
                    'description_en' => $faker->sentence,
                    'description_fa' => $fakerIR->sentence,
                ]);
            }
        }
    }

