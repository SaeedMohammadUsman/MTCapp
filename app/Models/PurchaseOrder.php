<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'vendor_id',
        'status_en',
        'status_fa',
        'remarks',
    ];
    protected $dates = ['deleted_at'];
    public function setStatusEnAttribute($value)
    {
        $this->attributes['status_en'] = $value;
        $this->attributes['status_fa'] = match ($value) {
            'Pending' => 'در انتظار',
            'Completed' => 'تکمیل شده',
            'Cancelled' => 'لغو شده',
            default => 'در انتظار',
        };
    }
    
    protected static function booted()
    {
        static::creating(function ($order) {
            $vendor = $order->vendor; // Assumes `vendor_id` is already set
    
            // Get the vendor code (First 3 letters of the vendor's company name, uppercase)
            $vendorCode = strtoupper(substr($vendor->company_name_en, 0, 3));
    
            // Generate a unique order number
            $sequence = 1; // Default sequence number
            $orderNumber = null;
            do {
                // Get the last order for this vendor
                $lastOrder = self::where('vendor_id', $vendor->id)
                    ->orderBy('id', 'desc')
                    ->first();
    
                // Determine the next sequence number
                $sequence = $lastOrder ? (int)substr($lastOrder->order_number, -3) + 1 : 1;
    
                // Format the sequence as a 3-digit number
                $sequenceFormatted = str_pad($sequence, 3, '0', STR_PAD_LEFT);
    
                // Generate the order number
                $orderNumber = $vendorCode . '-P' . $sequenceFormatted;
            } while (self::where('order_number', $orderNumber)->exists()); // Ensure the order number is unique
    
            $order->order_number = $orderNumber;
        });
    }
    
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
