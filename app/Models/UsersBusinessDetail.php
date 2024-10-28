<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserAppxpay;
use App\Models\BusinessApp;
use App\Models\BankDetails;
use App\AppOption;
use App\Repository\CipherRepository;

class UsersBusinessDetail extends Model
{
    use HasFactory;

    protected $table = 'users_business_details';

    protected $primaryKey = 'id';

    protected $cipherRepository;

    public function __construct(array $attributes = [], CipherRepository $cipherRepository = null)
    {
        parent::__construct($attributes);
        $this->cipherRepository = $cipherRepository ?: new CipherRepository();
    }

    protected $fillable = [
        'user_id',
        'pay_out',
        'pay_in',
        'api_ufw_pairs',
        'percentage_fees',
        'payout_percentage_fees',
        'payin_receive_bank',
        'payout_receive_bank',
        'business_type_id',
        'business_category_id',
        'business_sub_category_id',
        'bank_name',
        'bank_account_number',
        'bank_ifsc_code',
        'branch_name',
        'account_holder_name',
        'monthly_expenditure',
        'company_name',
        'company_address',
        'company_pincode',
        'city',
        'state_id',
        'country',
    ];

    protected static $encryptable = [
        'company_name',
        'company_address',
        'company_pincode',
        'city',
        'country',
        'bank_name',
        'bank_account_number',
        'bank_ifsc_code',
        'branch_name',
        'account_holder_name'
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

    public function userDetail()
    {
        return $this->belongsTo(UserAppxpay::class, 'user_id');
    }

    public function businessApps()
    {
        return $this->hasMany(BusinessApp::class, 'business_id', 'id');
    }

    public function monthlyExpenditure()
    {
        return $this->hasOne(AppOption::class, 'id', 'monthly_expenditure')
            ->where('module', 'merchant_business');
    }

    public function payinReceiveBank()
    {
        return $this->hasOne(BankDetails::class, 'id', 'payin_receive_bank');
    }

    public function payoutReceiveBank()
    {
        return $this->hasOne(BankDetails::class, 'id', 'payout_receive_bank');
    }

}
