<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockTransaction extends Model
{
    use HasFactory, SoftDeletes;

    
    protected $fillable = [
        'transaction_type',
        'received_good_id',
        'remarks',
        'transaction_date',
    ];
    protected $casts = [
        'transaction_date' => 'datetime',
    ];
    public function receivedGood()
    {
        return $this->belongsTo(ReceivedGood::class, 'received_good_id');
    }
   
    public function details()
    {
        return $this->hasMany(StockTransactionDetail::class);
    }
    public function scopeOfType($query, $type)
    {
        return $query->where('transaction_type', $type);
    }
}
