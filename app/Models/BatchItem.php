<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchItem extends Model
{
    
    protected $table = 'batch_items'; 
    public function inventoryBatches()
    {
        return $this->belongsToMany(InventoryBatch::class, 'batch_items')
            ->withPivot('cost_price', 'selling_price', 'quantity', 'expiration_date')
            ->withTimestamps();
    }
}
