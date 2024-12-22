<?php

namespace Database\Seeders;

use App\Models\ReceivedGood;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ReceivedGoodSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Get 5 to 7 vendors
        $vendors = Vendor::inRandomOrder()->take(rand(8, 12))->get();

        foreach ($vendors as $vendor) {
            ReceivedGood::create([
                'batch_number' => null, // The batch number will be auto-generated
                'remark' => $faker->sentence(),
                'vendor_id' => $vendor->id,
                'bill_attachment' => $faker->randomElement([
                    $faker->image(storage_path('app/public'), 640, 480, 'cats', false, true), // Generate a random JPG image
                    $faker->file(storage_path('app'), storage_path('app/public/pdfs'), false) // Generate a random PDF, storing in a different folder
                ]),


                'date' => now(),
                'is_finalized' => false,
            ]);
        }
    }
}
