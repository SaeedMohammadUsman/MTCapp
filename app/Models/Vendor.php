<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\purchaseOrders;
class Vendor extends Model
{
    use HasFactory;
    protected $table = 'vendors';
    protected $fillable = [
        'company_name_en',
        'company_name_fa',
        'email',
        'phone_number',
        'address_en',
        'address_fa',
        'country_name',
    ];
    
//     public function purchaseOrders()
// {
//     return $this->hasMany(PurchaseOrder::class);
// }
}
