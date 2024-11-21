<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'item_id',
        'trade_name_en',
        'trade_name_fa',
        'used_for_en',
        'used_for_fa',
        'size',
        'c_size',
        'unit_price',
        'quantity',
        'total_price',
        'remarks',
    ];
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }
    
}
