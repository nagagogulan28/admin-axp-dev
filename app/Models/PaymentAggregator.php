<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentAggregator extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'order_prefix',
        'total_service_fee',
        'active',
        'payin_status',
        'payout_status',
        'payout_service_fee'
       
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($paymentAggregator) {
            // Generate code (e.g., incrementing number with leading zeros)
            $latestAggregator = static::latest()->first();
            if ($latestAggregator) {
                $code = str_pad((int) $latestAggregator->code + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $code = '001'; // Initial code
            }

            $paymentAggregator->code = $code;
        });
    }

    // Define any relationships if needed
    // For example, if PaymentAggregator has many transactions:
    // public function transactions()
    // {
    //     return $this->hasMany(Transaction::class);
    // }
}
