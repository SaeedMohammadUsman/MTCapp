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

        $categories = [
            ['name_en' => 'Skincare', 'name_fa' => 'مراقبت از پوست'],
            ['name_en' => 'Haircare', 'name_fa' => 'مراقبت از مو'],
            ['name_en' => 'Makeup', 'name_fa' => 'آرایش'],
            ['name_en' => 'Fragrances', 'name_fa' => 'عطر و ادکلن'],
            ['name_en' => 'Bodycare', 'name_fa' => 'مراقبت از بدن'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name_en' => $category['name_en'],
                'name_fa' => $category['name_fa'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
