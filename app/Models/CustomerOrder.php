<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'price_package_id',
        'status',
        'total_amount',
        'remarks',
        'order_date',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function pricePackage()
    {
        return $this->belongsTo(PricePackage::class);
    }

    // public function orderItems()
    // {
    //     return $this->hasMany(CustomerOrderItem::class);
    // }
}
