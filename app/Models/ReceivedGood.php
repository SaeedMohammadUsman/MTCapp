<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceivedGood extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['date'];  // Add other date fields if needed
    protected $casts = [
        'date' => 'date',
    ];
    
    protected $fillable = [
        'batch_number',
        'remark',
        'vendor_id',
        'bill_attachment',
        'date',
        'is_finalized',
    ];

    protected static function booted()
    {
        static::creating(function ($receivedGood) {
            if (!$receivedGood->batch_number) {
                $latestBatch = self::withTrashed()->latest('id')->first();
                $nextNumber = $latestBatch ? ((int)str_replace('BATCH', '', $latestBatch->batch_number) + 1) : 1;
                $receivedGood->batch_number = 'BATCH' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }
        });
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

  
    public function details()
    {
        return $this->hasMany(ReceivedGoodDetail::class, 'received_good_id');
    }
    
    
    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class, 'received_good_id');
    }
}
