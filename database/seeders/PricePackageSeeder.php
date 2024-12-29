<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PricePackage;
use App\Models\PricePackageDetail;
use App\Models\PackageCustomer;
use App\Models\Item;
use App\Models\Customer;
class PricePackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Seed Price Packages
        $pricePackages = [];
        for ($i = 1; $i <= 5; $i++) {
            $pricePackages[] = PricePackage::create([
                'title' => 'Price Package ' . $i
            ]);
        }

       
        $items = Item::all(); // Fetch all items
        $customers = Customer::all(); // Fetch all customers

        foreach ($pricePackages as $package) {
           
            $itemCount = rand(2, 5); 
            $randomItems = $items->random($itemCount);

            foreach ($randomItems as $item) {
                PricePackageDetail::create([
                    'price_package_id' => $package->id,
                    'item_id' => $item->id,
                    'discount' => rand(7, 20), // Random discount between 7% and 20%
                    'price' => null, // If price needs to be calculated, you can set it here
                ]);
            }

            // Attach random customers to each price package
            $customerCount = rand(1, 3); // Each package has 1-3 customers
            $randomCustomers = $customers->random($customerCount);

            foreach ($randomCustomers as $customer) {
                PackageCustomer::create([
                    'price_package_id' => $package->id,
                    'customer_id' => $customer->id,
                ]);
            }
        }
    }
        
    }

