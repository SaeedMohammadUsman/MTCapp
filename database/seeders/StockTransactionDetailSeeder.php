<?php

namespace Database\Seeders;

use App\Models\StockTransaction;
use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockTransactionDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all the stock transactions
        $stockTransactions = StockTransaction::all();

        // Loop over each stock transaction and create details
        foreach ($stockTransactions as $stockTransaction) {
            // Only create details for Stock In transactions (type 1)
            if ($stockTransaction->transaction_type == 1) {
                // Fetch random items (3 to 6 items) for this transaction
                $items = Item::inRandomOrder()->take(rand(3, 6))->get();

                // Create stock transaction details for each selected item
                foreach ($items as $item) {
                    // Create the stock transaction detail
                    $stockTransaction->details()->create([
                        'item_id' => $item->id,               // Item id
                        'quantity' => rand(1, 10),             // Random quantity between 1 and 10
                        'price' => rand(100, 1000),            // Random price between 100 and 1000
                        'remarks' => 'Stock In for item ' . $item->trade_name_en,
                    ]);
                }
            }
        }
    }
}
