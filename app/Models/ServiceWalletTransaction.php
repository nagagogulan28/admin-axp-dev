<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserAppxpay;
use App\Models\BankDetails;
use App\Models\AppxpayDocument;
use App\Models\UsersBusinessDetail;

class ServiceWalletTransaction extends Model
{
    use HasFactory;

    protected $table = 'servicewallet_transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'apx_bank_id',
        'amount',
        'rrn_no',
        'payment_slip_id',
        'payment_mode',
        'remark',
        'approved_by',
        'created_at',
        'updated_at',
        'approved_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'approved_at' => 'datetime',
        'amount' => 'decimal:2', // Cast amount as decimal with 2 decimal places
    ];

    /**
     * Get the user associated with the transaction.
     */
    public function merchantUser()
    {
        return $this->belongsTo(UserAppxpay::class, 'user_id');
    }

    /**
     * Get the bank associated with the transaction.
     */
    public function apxBank()
    {
        return $this->belongsTo(BankDetails::class, 'apx_bank_id');
    }

    /**
     * Get the transaction mode associated with the transaction.
     */
    public function modeTxn()
    {
        return $this->belongsTo(TransactionMode::class, 'payment_mode');
    }

    /**
     * Get the bank associated with the transaction.
     */
    public function receiveAmtReceipt()
    {
        return $this->belongsTo(AppxpayDocument::class, 'payment_slip_id');
    }

    public function businessDetail()
    {
        return $this->hasOne(UsersBusinessDetail::class, 'user_id', 'user_id');
    }
}
