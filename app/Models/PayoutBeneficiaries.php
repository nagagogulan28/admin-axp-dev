<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Repository\CipherRepository;

class PayoutBeneficiaries extends Model
{
    protected $table = 'payout_beneficiaries';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $cipherRepository;

    public function __construct(array $attributes = [], CipherRepository $cipherRepository = null)
    {
        parent::__construct($attributes);
        $this->cipherRepository = $cipherRepository ?: new CipherRepository();
    }

    protected $fillable = [
        'contact_id', 'group_id', 'merchant_id', 'beneficiary_id',
        'account_holder_name', 'account_no', 'ifsc_code',
        'penny_drop_status', 'is_default', 'is_active', 'upi_id', 'is_deleted'
    ];

    protected static $encryptable = [
        'account_holder_name',
        'account_no',
        'ifsc_code'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            foreach (static::$encryptable as $field) {
                if (!empty($model->$field)) {
                    $model->$field = $model->cipherRepository->encrypt($model->$field);
                }
            }
        });

        static::updating(function ($model) {
            foreach (static::$encryptable as $field) {
                if (!empty($model->$field)) {
                    $model->$field = $model->cipherRepository->encrypt($model->$field);
                }
            }
        });

        static::retrieved(function ($model) {
            foreach (static::$encryptable as $field) {
                if (!empty($model->$field)) {
                    $model->$field = $model->cipherRepository->decrypt($model->$field);
                }
            }
        });
    }

    public function contact()
    {
        return $this->belongsTo('App\Models\PayoutContacts', 'contact_id');
    }
    

    // public function group()
    // {
    //     return $this->belongsTo('App\PayoutBeneficiaryGroup', 'group_id');
    // }

    public function merchant()
    {
        return $this->belongsTo('App\Models\UserAppxpay', 'merchant_id');
    }
}
