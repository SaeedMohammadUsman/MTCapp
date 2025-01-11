<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockTransactionDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'stock_transaction_id',  
        'remarks',             
        'item_id',
        'quantity',
        'price',
    ];

    /**
     * Relationship with StockTransaction.
     */
    public function stockTransaction()
    {
        return $this->belongsTo(StockTransaction::class);  // Belongs to one StockTransaction
    }
   
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
