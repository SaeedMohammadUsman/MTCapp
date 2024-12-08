<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventoryBatch;

class InventoryBatchSeeder extends Seeder
{
    public function run()
    {
        // Define batch data
        $batchData = [
            ['remark' => 'First batch of item'],
            ['remark' => 'Limited edition'],
            ['remark' => 'High-demand item'],
            ['remark' => 'Discounted batch'],
            ['remark' => 'Seasonal item'],
        ];

        // Create inventory batches
        foreach ($batchData as $index => $batch) {
            InventoryBatch::create([
                'batch_number' => 'BATCH' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'remark' => $batch['remark'],
            ]);
        }
    }
}
