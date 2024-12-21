<?php

namespace Database\Seeders;

use App\Models\StockTransaction;
use App\Models\ReceivedGood;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class StockTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Fetch all received goods
        $receivedGoods = ReceivedGood::all();

        // Select 5-7 received goods to create stock transactions with type 1 (Stock In)
        $stockInReceivedGoods = $receivedGoods->random(rand(5, 7));

        // Select 2-3 received goods to create stock transactions with type 2 (Stock Out)
        $stockOutReceivedGoods = $receivedGoods->random(rand(2, 3));

        // Insert Stock In transactions
        foreach ($stockInReceivedGoods as $receivedGood) {
            StockTransaction::create([
                'transaction_type' => 1, // Stock In
                'reference_id' => $receivedGood->id,
                'reference_type' => 'received_goods',
                'remarks' => 'Stock In for received good ' . $receivedGood->batch_number,
                'transaction_date' => now(),
            ]);
        }

        // Insert Stock Out transactions
        foreach ($stockOutReceivedGoods as $receivedGood) {
            StockTransaction::create([
                'transaction_type' => 2, // Stock Out
                'reference_id' => null,  // No reference for Stock Out
                'reference_type' => null, 
                'remarks' => 'Stock Out for received good ' . $receivedGood->batch_number,
                'transaction_date' => now(),
            ]);
        }
    }
}
