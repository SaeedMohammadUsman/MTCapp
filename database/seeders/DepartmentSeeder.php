<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $fakerIR = Faker::create('fa_IR');
        

        foreach (range(1, 5) as $index) {
            DB::table('departments')->insert([
                'title_en' => $faker->words(3, true), // Random English title
                'title_fa' => $fakerIR->words(3, true), // Random Farsi title
                'status' => $faker->randomElement(['active', 'inactive', 'archived']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
