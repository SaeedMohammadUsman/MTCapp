<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Faker\Provider\ar_EG\Company;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $fakerIR = Faker::create('fa_IR');
        

        foreach (range(1, 10) as $index) {
            DB::table('departments')->insert([
                'title_en' => $faker->Company, // Random English title
                'title_fa' => $fakerIR->sentence, // Random Farsi title
                'position'=>$faker->randomElement(['salesman', 'visitor', 'cook', 'manager', 'administrator']),
                'status' => $faker->randomElement(['active', 'inactive', 'archived']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
