<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use OpenAfghanistan\Provinces\Models\District;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $fakerIR = Faker::create('fa_IR');

        // Create 13 fake customers
        for ($i = 0; $i < 13; $i++) {
            // Get a random district
            $district = District::inRandomOrder()->first();

            // Generate fake data
            Customer::create([
                'customer_name_en' => $faker->name,
                'customer_name_fa' => $fakerIR->name,
                'district_id' => $district->id,
                'address' => $faker->streetAddress . ' ' . $district->name . ' ' . $district->province->name,
                'customer_phone' => $faker->phoneNumber,
                'email' => $faker->unique()->safeEmail,
            ]);
        }
    }
}
