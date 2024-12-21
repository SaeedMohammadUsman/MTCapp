<?php

namespace Database\Seeders;

use App\Models\StockTransaction;
use App\Models\StockTransactionDetail;
use App\Models\ReceivedGood;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class StockTransactionDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Initialize Faker for random data generation
        $faker = Faker::create();

        // Fetch all stock transactions
        $stockTransactions = StockTransaction::all();

        // Loop through each stock transaction
        foreach ($stockTransactions as $stockTransaction) {
            // Fetch the received good related to the stock transaction
            if ($stockTransaction->reference_type === 'received_goods') {
                // Fetch the corresponding received good
                $receivedGood = ReceivedGood::find($stockTransaction->reference_id);
                
                if ($receivedGood) {
                    // Loop through each item in the batch (assuming each received good has many items)
                    foreach ($receivedGood->details as $receivedGoodDetail) {
                        // Create a StockTransactionDetail for each item in the received good batch
                        StockTransactionDetail::create([
                            'stock_transaction_id' => $stockTransaction->id, // Link to stock transaction
                            'arrival_price' => $faker->randomFloat(2, 10, 100), // Random arrival price between 10 and 100
                            'remarks' => $faker->sentence, // Random remarks
                        ]);
                    }
                }
            }
        }
    }
}
