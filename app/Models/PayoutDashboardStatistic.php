<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayoutDashboardStatistic extends Model
{
    protected $table = 'payout_dashboard_statistics';

    protected $fillable = [
        'merchant_id',
        'total_transactions_count',
        'total_successfull_transactions',
        'total_failed_transactions',
        'pending_transactions',
        'total_amount_of_successfull_transaction',
        'total_users',
        'total_live_users',
        'total_active_users',
        'total_gtv',
        'terminal_id',
    ];

    // Define relationships if needed, e.g.:
    // public function merchant()
    // {
    //     return $this->belongsTo(Merchant::class);
   // In PayoutDashboardStatistic model
public function userAppxpay()
{
    return $this->belongsTo(UserAppxpay::class, 'merchant_id', 'user_id');
}

    // }
}
