<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OpenAfghanistan\Provinces\Models\District;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    // Fillable attributes
    protected $fillable = [
        'customer_name_en',
        'customer_name_fa',
        'district_id',
        'address',
        'customer_phone',
        'email',
       
    ];

    // Relationship with District
    public function district()
    {
        return $this->belongsTo(District::class);
    }
   
    public function orders()
    {
        return $this->hasMany(CustomerOrder::class);
    }
  
}
