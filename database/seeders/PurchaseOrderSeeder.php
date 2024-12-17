<?php

namespace Database\Seeders;

use App\Models\PurchaseOrder;
use App\Models\Vendor;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PurchaseOrderSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $vendors = Vendor::inRandomOrder()->limit(rand(4, 8))->get();
        $ordersToInsert = [];

        foreach ($vendors as $vendor) {
            $vendorCodeBase = strtoupper(substr($vendor->company_name_en, 0, 3));

            if (strlen($vendorCodeBase) < 3) {
                $vendorCodeBase = strtoupper(substr($vendor->name, 0, 1) . 'ND');
            }
            $ordersCount = rand(3, 6);

            for ($sequence = 1; $sequence <= $ordersCount; $sequence++) {
                $sequenceFormatted = str_pad($sequence, 3, '0', STR_PAD_LEFT);
                $orderNumber = $vendorCodeBase  . '-P' . $sequenceFormatted;

                while (PurchaseOrder::where('order_number', $orderNumber)->exists()) {
                    $sequence++;
                    $sequenceFormatted = str_pad($sequence, 3, '0', STR_PAD_LEFT);
                    $orderNumber = $vendorCodeBase . '-' . $vendor->id . '-P' . $sequenceFormatted;
                }

                $ordersToInsert[] = [
                    'order_number' => $orderNumber,
                    'vendor_id'    => $vendor->id,
                    'status_en'    => 'Pending',
                    'status_fa'    => 'در انتظار',
                    'remarks'      => $faker->boolean(70) ? $faker->sentence() : null,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
            }
        }

        if (!empty($ordersToInsert)) {
            PurchaseOrder::insert($ordersToInsert);
        }
    }
}
