<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name_en',
        'item_name_fa',
        'item_code',
        'cost_price',
        'selling_price',
        'quantity_in_stock',
        'expiration_date',
        'description_en',
        'description_fa',
    ];

    public function stockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class, 'item_id'); // Use 'item_id' instead of 'inventory_item_id'
    }
    
    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'item_id');
    }
}
