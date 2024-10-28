<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserAppxpay;
use App\Models\PayoutOptions;
use App\Models\UsersBusinessDetail;

class PayoutCommissionCharges extends Model
{
    protected $table = 'payout_commission_charges';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'merchant_id', 'range_id', 'gst',
        'IMPS', 'NEFT', 'RTGS', 'UPI', 'PAYTM', 'AMAZON', 'udf1', 'udf2', 'udf3', 'udf4', 'status'
    ];

    public function merchant()
    {
        return $this->belongsTo(UserAppxpay::class, 'merchant_id');
    }

    public function getRange()
    {
        return $this->belongsTo(PayoutOptions::class, 'range_id');
    }
}