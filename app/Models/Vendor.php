<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\purchaseOrders;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'vendors';
    protected $fillable = [
        'company_name_en',
        'company_name_fa',
        'email',
        'phone_number',
        'address_en',
        'address_fa',
        'country_name',
        'currency',
    ];
    

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($vendor) {
            if ($vendor->country_name == 'Pakistan') {
                $vendor->currency = 'PKR';
            } elseif ($vendor->country_name == 'India') {
                $vendor->currency = 'INR';
            } elseif ($vendor->country_name == 'Iran') {
                $vendor->currency = 'Toman';
            }
        });

        static::updating(function ($vendor) {
            if ($vendor->country_name == 'Pakistan') {
                $vendor->currency = 'PKR';
            } elseif ($vendor->country_name == 'India') {
                $vendor->currency = 'INR';
            } elseif ($vendor->country_name == 'Iran') {
                $vendor->currency = 'Toman';
            }
        });
    }
public function purchaseOrders()
{
    return $this->hasMany(PurchaseOrder::class);
}

public function receivedGoods()
    {
        return $this->hasMany(ReceivedGood::class, 'vendor_id');
    }
}
