<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PayoutOptions;

class PayoutTransactions extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payout_transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'merchant_id',
        'contact_id',
        'bene_id',
        'amount',
        'order_id',
        'reference_id',
        'transfer_id',
        'utr',
        'transfer_mode',
        'vendor',
        'status',
        'remarks',
        'transfer_desc',
        'vendor_charges',
        'goods_service_tax',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */

    public function getBeneficieries()
    {
        return $this->hasOne(PayoutBeneficiaries::class, 'merchant_id', 'merchant_id');
    }

    public function getContacts()
    {
        return $this->hasOne(PayoutContacts::class, 'merchant_id', 'merchant_id');
    }

    public function commisionsData()
    {
        return $this->hasOne(PayoutCommisionHistory::class, 'txn_refid', 'id');
    }

    public function txnStatus()
    {
        return $this->hasOne(PayoutOptions::class, 'option_value', 'status')
            ->where('unique_model_name', 'payout_payment_status');
    }
}
