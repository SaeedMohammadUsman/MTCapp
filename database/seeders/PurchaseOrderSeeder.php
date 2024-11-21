<?php

// namespace Database\seeders;

// use App\Models\PurchaseOrder;
// use App\Models\Vendor;
// use Faker\Factory as Faker;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// use Illuminate\Database\Seeder;

// class PurchaseOrderSeeder extends Seeder
// {
//     /**
//      * Run the database seeds.
//      */
//     public function run(): void
//     {
//         $vendorIds = Vendor::pluck('id')->toArray();
//         $faker = Faker::create();
//         $fakerIR= Faker::create('fa_IR'); 
        
        
        
//         foreach (range(1, 5) as $index) {
//             PurchaseOrder::create([
//                 'order_number' => $faker->unique()->numerify('PO#####'),
//                 'vendor_id'    => $faker->randomElement($vendorIds),
//                 'total_price'  => $faker->randomFloat(2, 1000, 5000),
//                 'status_en'    => $faker->randomElement(['Pending', 'Completed', 'Cancelled']),
//                 'status_fa'    => $fakerIR->randomElement(['در انتظار', 'تکمیل شده', 'لغو شده']),
//                 'remarks'      => $faker->sentence(),
//             ]);
//         }
//     }
// }



namespace Database\Seeders;

use App\Models\PurchaseOrder;
use App\Models\Vendor;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch existing vendor IDs
        $vendorIds = Vendor::pluck('id')->toArray();

        // Initialize Faker for English and Persian
        $faker = Faker::create();
        $fakerIR = Faker::create('fa_IR'); 

        // Create 5 purchase orders
        foreach (range(1, 5) as $index) {
            PurchaseOrder::create([
                'order_number' => $faker->unique()->numerify('PO#####'),
                'vendor_id'    => $faker->randomElement($vendorIds),
                'total_price'  => $faker->randomFloat(2, 1000, 5000),
                'status_en'    => $faker->randomElement(['Pending', 'Completed', 'Cancelled']),
                'status_fa'    => $fakerIR->randomElement(['در انتظار', 'تکمیل شده', 'لغو شده']),
                'remarks'      => $faker->sentence(),
            ]);
        }
    }
}


