<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ServiceWallet extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'service_wallet';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'current_balance',
        'total_service_fund',
        'minimum_balance',
        'payout_current_balance',
        'payout_total_service_funds',
        'payout_minimum_balance'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'current_balance' => 'decimal:2',
        'total_service_fund' => 'decimal:2',
        'minimum_balance' => 'decimal:2',
        'payout_current_balance' => 'decimal:2',
        'payout_total_service_funds' => 'decimal:2',
        'payout_minimum_balance' => 'decimal:2'
    ];
    
    public function userDetail()
    {
        return $this->belongsTo(UserAppxpay::class, 'user_id');
    }
}