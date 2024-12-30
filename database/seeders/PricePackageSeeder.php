<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PricePackage;
use App\Models\PricePackageDetail;
use App\Models\Customer;
use App\Models\StockTransactionDetail;

class PricePackageSeeder extends Seeder
{
    public function run()
    {
        // Fetch all customers
        $customers = Customer::all();

        // Fetch all stock transaction details
        $stockTransactionDetails = StockTransactionDetail::all();

        // Create 5 to 7 price packages
        $pricePackageCount = rand(5, 7);

        for ($i = 1; $i <= $pricePackageCount; $i++) {
            // Assign a random customer to the package
            $randomCustomer = $customers->random();

            // Create the price package
            $pricePackage = PricePackage::create([
                'title' => 'Price Package ' . $i,
                'customer_id' => $randomCustomer->id,
            ]);

            // Add 3 to 6 items to the package from stock transaction details
            $itemCount = rand(3, 6);
            $randomItems = $stockTransactionDetails->random($itemCount);

            foreach ($randomItems as $item) {
                $discount = rand(7, 20); // Random discount between 7% and 20%
                $arrivalPrice = $item->arrival_price;

                // Calculate price after discount
                $priceAfterDiscount = $arrivalPrice - ($arrivalPrice * ($discount / 100));

                // Create the price package detail
                PricePackageDetail::create([
                    'price_package_id' => $pricePackage->id,
                    'stock_transaction_detail_id' => $item->id,
                    'discount' => $discount,
                    'price' => $priceAfterDiscount,
                ]);
            }
        }
    }
}
