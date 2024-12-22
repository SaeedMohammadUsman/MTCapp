<?php

namespace Database\Seeders;

use App\Models\StockTransaction;
use App\Models\ReceivedGood;
use App\Models\StockTransactionDetail;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
class StockTransactionDetailSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        // Fetch all stock transactions
        $stockTransactions = StockTransaction::all();
        Log::info('Starting to seed StockTransactionDetails...');
        Log::info('Total Stock Transactions: ' . $stockTransactions->count());
    
        foreach ($stockTransactions as $stockTransaction) {
            // Log the actual transaction type for debugging
            Log::info('Processing StockTransaction ID: ' . $stockTransaction->id . ' with transaction type: ' . $stockTransaction->transaction_type);
    
            // Process only "Stock In" type (transaction_type 1)
            if ($stockTransaction->transaction_type === 1) {  // Correct condition for "Stock In"
                // Find the related ReceivedGood by the 'received_good_id'
                $receivedGood = $stockTransaction->receivedGood; // Use the relationship defined on StockTransaction
    
                if ($receivedGood) {
                    // Loop through all ReceivedGoodDetails of this ReceivedGood
                    foreach ($receivedGood->details as $receivedGoodDetail) {
                        // Get the associated Item
                        $item = $receivedGoodDetail->item;
    
                        // Create StockTransactionDetail for each item
                        StockTransactionDetail::create([
                            'stock_transaction_id' => $stockTransaction->id, // Link to StockTransaction
                            'arrival_price' => $faker->randomFloat(2, 10, 100), // Random arrival price between 10 and 100
                            'remarks' => $faker->sentence, // Random remarks
                        ]);
    
                        Log::info('Created StockTransactionDetail for Item ID: ' . $item->id);
                    }
                } else {
                    Log::warning('No received good found for StockTransaction ID: ' . $stockTransaction->id);
                }
            } else {
                Log::info('Skipping StockTransaction ID ' . $stockTransaction->id . ' as it is not of type "Stock In".');
            }
        }
    
        Log::info('Finished seeding StockTransactionDetails.');
    }
}