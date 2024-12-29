<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricePackageDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'price_package_id',
        'item_id',
        'discount',
        'price',
    ];
    public function pricePackage()
    {
        return $this->belongsTo(PricePackage::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
