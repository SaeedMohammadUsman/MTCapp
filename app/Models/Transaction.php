<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'account_id',
        'customer_id',
        'vendor_id',
        'amount',
        'transaction_type',
        'source',
        'description',
        'transaction_date',
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($transaction) {
            $transaction->applyBalanceChange($transaction->amount, $transaction->transaction_type);
        });

        static::updating(function ($transaction) {
            $originalAmount = $transaction->getOriginal('amount');
            $originalType = $transaction->getOriginal('transaction_type');

            // Reverse previous balance change
            $transaction->applyBalanceChange(-$originalAmount, $originalType);
        });

        static::updated(function ($transaction) {
            // Apply new transaction impact
            $transaction->applyBalanceChange($transaction->amount, $transaction->transaction_type);
        });

        static::deleted(function ($transaction) {
            $transaction->applyBalanceChange(-$transaction->amount, $transaction->transaction_type);
        });
    }

    /**
     * Apply balance change to the related account safely.
     */
    private function applyBalanceChange($amount, $type)
    {
        $account = $this->account;

        if (!$account) {
            return; // Prevent errors if account is missing
        }

        DB::transaction(function () use ($account, $amount, $type) {
            if ($type === 'income') {
                $account->increment('balance', $amount);
            } elseif ($type === 'expense' || $type === 'transfer') {
                if ($account->balance < $amount) {
                    throw new \Exception("Insufficient balance for this transaction.");
                }
                $account->decrement('balance', $amount);
            }
        });
    }
}
