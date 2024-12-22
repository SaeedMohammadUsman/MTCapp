<?php

namespace Database\Seeders;

use App\Models\StockTransaction;
use App\Models\ReceivedGood;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

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

        // For each received good, create a Stock In (type 1) transaction
        foreach ($receivedGoods as $receivedGood) {
            // Generate a random date within the current year for the transaction
            $randomDate = Carbon::now()->startOfYear()->addMonths(rand(0, 11))->addDays(rand(0, 30));

            StockTransaction::create([
                'transaction_type' => 1, // Stock In
                'received_good_id' => $receivedGood->id, // Foreign key to ReceivedGood
                'remarks' => 'Stock In for received good ' . $receivedGood->batch_number,
                'transaction_date' => $randomDate,
            ]);
        }

        
        StockTransaction::create([
            'transaction_type' => 2, // Stock Out
            'received_good_id' => null,  // No reference for Stock Out
            'remarks' => 'Stock Out transaction',
            'transaction_date' => Carbon::now()->startOfYear()->addMonths(rand(0, 11))->addDays(rand(0, 30)),
        ]);

        // Return transaction
        StockTransaction::create([
            'transaction_type' => 3, // Return
            'received_good_id' => null,  // No reference for Return
            'remarks' => 'Return transaction',
            'transaction_date' => Carbon::now()->startOfYear()->addMonths(rand(0, 11))->addDays(rand(0, 30)),
        ]);

        // Damaged transaction
        StockTransaction::create([
            'transaction_type' => 4, // Damaged
            'received_good_id' => null,  // No reference for Damaged
            'remarks' => 'Damaged transaction',
            'transaction_date' => Carbon::now()->startOfYear()->addMonths(rand(0, 11))->addDays(rand(0, 30)),
        ]);
    }
}
