<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricePackageDetail extends Model
{
    use HasFactory;

    protected $fillable = ['price_package_id', 'stock_transaction_detail_id', 'discount', 'price'];
    public function pricePackage()
    {
        return $this->belongsTo(PricePackage::class);
    }

    public function stockTransactionDetail()
    {
        return $this->belongsTo(StockTransactionDetail::class);
    }
}
