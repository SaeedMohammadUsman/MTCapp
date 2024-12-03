<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $fakerIR = Faker::create('fa_IR');
        
        foreach (range(1, 10) as $index) {
            DB::table('categories')->insert([
                'name_en' => $faker->word,       // English category name
                'name_fa' => $fakerIR->company,    // Persian category name
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
