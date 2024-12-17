<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    // Fillable attributes to allow mass assignment
    protected $fillable = [

        'item_code',
        'trade_name_en',
        'used_for_en',
        'trade_name_fa',
        'used_for_fa',
        'size',
        'description_fa',
        'description_en',
        'category_id'
    ];



    protected static function booted()
    {
        static::creating(function ($item) {
            // Auto-generate item_code if not provided
            if (!$item->item_code) {
                $categoryPrefix = $item->category ? strtoupper(substr($item->category->name_en, 0, 3)) : 'GEN'; // First 3 letters of the category name or 'GEN' if no category
                $randomNumber = rand(1000, 9999); // Generate an 4-digit random number
                $item->item_code = $categoryPrefix . '-' . $randomNumber; // Category code + random number
            }
        });
    }

    // Define the relationship with categories
    public function category()
    {
        return $this->belongsTo(Category::class); // Assuming Category model exists
    }
    public function inventoryBatches()
    {
        return $this->hasMany(InventoryBatch::class, 'item_id', 'id');
    }

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'item_id', 'id');
    }
}
