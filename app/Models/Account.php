<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['name', 'type', 'balance'];
    
    protected $attributes = [
        'balance' => 0, // Default balance when an account is created
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($account) {
            if ($account->balance < 0) {
                throw new \Exception("Account balance cannot be negative.");
            }
        });
    }
}
