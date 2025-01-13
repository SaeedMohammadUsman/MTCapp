<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\PricePackage;
use App\Models\PricePackageDetail;
use App\Models\Item;
use App\Models\StockTransactionDetail;

class PricePackageSeeder extends Seeder
{
    public function run()
    {
        $numberOfPackages = 10; // Number of packages to create
        $itemsPerPackage = 3; // Number of items per package

        for ($i = 0; $i < $numberOfPackages; $i++) {
            // Get a random customer
            $customer = Customer::inRandomOrder()->first();

            // Create a new price package
            $pricePackage = PricePackage::create([
                'customer_id' => $customer->id,
                'title' => 'Package for ' . $customer->customer_name_en,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Get random items
            $randomItems = Item::inRandomOrder()->take($itemsPerPackage)->get();

            // Inside the PricePackageSeeder run() method
            foreach ($randomItems as $item) {
                // Get the latest stock transaction detail for the item
                $latestPriceDetail = StockTransactionDetail::where('item_id', $item->id)
                    ->orderByDesc('created_at')
                    ->first();

                if ($latestPriceDetail) {
                    $price = $latestPriceDetail->price;

                    // Skip items with no price
                    if ($price == 0) {
                        continue;
                    }

                    $discount = rand(7, 20); // Random discount between 7% and 20%

                    // Calculate price after discount
                    $priceAfterDiscount = $price - ($price * ($discount / 100));

                    // Create a new PricePackageDetail model for each item
                    PricePackageDetail::create([
                        'price_package_id' => $pricePackage->id,
                        'item_id' => $item->id,
                        'discount' => $discount,
                        'price' => $priceAfterDiscount,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    // public function run()
    // {
    //     // Fetch all customers
    //     $customers = Customer::all();

    //     // Fetch all items
    //     $items = Item::all();

    //     // Create 5 to 7 price packages
    //     $pricePackageCount = rand(5, 7);

    //     for ($i = 1; $i <= $pricePackageCount; $i++) {
    //         // Assign a random customer to the package
    //         $randomCustomer = $customers->random();

    //         // Create the price package
    //         $pricePackage = PricePackage::create([
    //             'title' => 'Price Package ' . $i,
    //             'customer_id' => $randomCustomer->id,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ]);

    //         $pricePackageDetails = [];
    //         $itemCount = rand(3, 6);
    //         $randomItems = $items->random($itemCount);

    //         foreach ($randomItems as $item) {
    //             // Get the latest stock transaction detail for the item
    //             $latestPriceDetail = StockTransactionDetail::where('item_id', $item->id)
    //                 ->orderByDesc('created_at')
    //                 ->first();

    //             if ($latestPriceDetail) {
    //                 $arrivalPrice = $latestPriceDetail->arrival_price;

    //                 // Skip items with no price
    //                 if ($arrivalPrice == 0) {
    //                     continue;
    //                 }

    //                 $discount = rand(7, 20); // Random discount between 7% and 20%

    //                 // Calculate price after discount
    //                 $priceAfterDiscount = $arrivalPrice - ($arrivalPrice * ($discount / 100));

    //                 // Prepare price package detail
    //                 $pricePackageDetails[] = [
    //                     'price_package_id' => $pricePackage->id,
    //                     'stock_transaction_detail_id' => $latestPriceDetail->id,
    //                     'discount' => $discount,
    //                     'price' => $priceAfterDiscount,
    //                     'created_at' => now(),
    //                     'updated_at' => now(),
    //                 ];
    //             }
    //         }


    //     }
    // }
}
