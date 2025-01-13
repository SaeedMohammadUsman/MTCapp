<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerOrderItem extends Model
{
    protected $fillable = [
        'customer_order_id',
        'item_id',
        'quantity',
        'price',
    ];
    
    public function customerOrder()
    {
        return $this->belongsTo(CustomerOrder::class);
    }

    // Relationship: A customer order item belongs to an item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
