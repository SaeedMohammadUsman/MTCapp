<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PricePackage extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['title'];
    
    public function details()
    {
        return $this->hasMany(PricePackageDetail::class);
    }

    public function customers()
    {
        return $this->hasMany(PackageCustomer::class);
    }

}
