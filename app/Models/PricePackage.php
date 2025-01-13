<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PricePackage extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['title', 'customer_id'];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function pricePackageDetails()
    {
        return $this->hasMany(PricePackageDetail::class);
    }
    
    public function customerOrders()
    {
        return $this->hasMany(CustomerOrder::class);
    }
}
