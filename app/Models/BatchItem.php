<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchItem extends Model
{

    protected $table = 'batch_items';
    protected $fillable = [
        'inventory_batch_id', // Add this line
        'item_id',
        'cost_price',
        'selling_price',
        'quantity',
        'expiration_date',
    ];

    public function inventoryBatch()
    {
        return $this->belongsTo(InventoryBatch::class, 'inventory_batch_id'); // Each BatchItem belongs to an InventoryBatch
    }
    public function item()
    {
        return $this->belongsTo(Item::class);  // Define the relationship to the Item model
    }
}
