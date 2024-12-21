<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockTransactionDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'stock_transaction_id',  // Foreign key to StockTransaction
        'arrival_price',         // Price on arrival (including transport, tax, etc.)
        'remarks',               // Any remarks for the transaction
    ];

    /**
     * Relationship with StockTransaction.
     */
    public function stockTransaction()
    {
        return $this->belongsTo(StockTransaction::class);  // Belongs to one StockTransaction
    }

   
}
