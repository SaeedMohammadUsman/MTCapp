<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchItem extends Model
{

    protected $table = 'batch_items';
    // public function inventoryBatches()
    // {
    //     return $this->belongsToMany(InventoryBatch::class, 'batch_items')
    //         ->withPivot('cost_price', 'selling_price', 'quantity', 'expiration_date')
    //         ->withTimestamps();
    // }
    public function inventoryBatch()
    {
        return $this->belongsTo(InventoryBatch::class , 'inventory_batch_id'); // Each BatchItem belongs to an InventoryBatch
    }
    public function item()
    {
        return $this->belongsTo(Item::class);  // Define the relationship to the Item model
    }
}
