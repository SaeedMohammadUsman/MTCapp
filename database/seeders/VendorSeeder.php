<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $fakerIR = Faker::create('fa_IR');
        
        $countries = ['Pakistan', 'India', 'Iran'];
        foreach (range(1, 10) as $index) {
            $country = $countries[array_rand($countries)];
            $currency = '';
            if ($country == 'Pakistan') {
                $currency = 'PKR';
            } elseif ($country == 'India') {
                $currency = 'INR';
            } elseif ($country == 'Iran') {
                $currency = 'Toman';
            }
            DB::table('vendors')->insert([
                'company_name_en' => $faker->company,  
                'company_name_fa' => $fakerIR->company,  
                'email' => $faker->unique()->safeEmail,  
                'phone_number' => $faker->phoneNumber,  
                'address_en' => $faker->address, 
                'address_fa' => $faker->address,  
                'country_name' => $country,  
                'currency' => $currency,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
        
    }

