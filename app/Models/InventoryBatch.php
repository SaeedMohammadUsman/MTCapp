<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryBatch extends Model
{
    use HasFactory, SoftDeletes;

    // Fillable attributes to allow mass assignment
    protected $fillable = [
        'item_id',
        'batch_number',
        'cost_price',
        'selling_price',
        'quantity',
        'expiration_date',
        'remark'
    ];
    protected static function booted()
    {
        static::creating(function ($batch) {
            // Auto-generate batch number if not provided
            if (!$batch->batch_number) {
                $latestBatch = self::where('item_id', $batch->item_id)->latest('id')->first();
                $nextNumber = $latestBatch ? ((int)str_replace('BATCH', '', $latestBatch->batch_number) + 1) : 1;
                $batch->batch_number = 'BATCH' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT); // Format as BATCH001
            }
        });
    }

    /**
     * Define the relationship with the Item model.
     */
    public function items()
    {
        return $this->belongsToMany(Item::class, 'batch_item')
                    ->withPivot('cost_price', 'selling_price', 'quantity', 'expiration_date')
                    ->withTimestamps();
    }
    
}
