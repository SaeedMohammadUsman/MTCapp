<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivedGoodDetail extends Model
{
    use HasFactory;
    protected $table = 'received_goods_details';

    protected $fillable = [
        'received_good_id',
        'item_id',
        'vendor_price',
        'quantity',
        'expiration_date',
    ];

    /**
     * Define the relationship with ReceivedGood model.
     */
    public function receivedGood()
    {
        return $this->belongsTo(ReceivedGood::class, 'received_good_id');
    }

    /**
     * Define the relationship with Item model.
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
