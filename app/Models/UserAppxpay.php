<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UsersBusinessDetail;
use App\Models\AppxpayDocument;
use App\Models\ServiceWallet;
use App\Repository\CipherRepository;
use App\Models\PayoutCommissionCharges;

class UserAppxpay extends Model
{
    use HasFactory;

    protected $table = 'users_appxpay';

    protected $primaryKey = 'id';

    protected $cipherRepository;

    public function __construct(array $attributes = [], CipherRepository $cipherRepository = null)
    {
        parent::__construct($attributes);
        $this->cipherRepository = $cipherRepository ?: new CipherRepository();
    }

    protected $fillable = [
        'user_label_id',
        'first_name',
        'last_name',
        'user_name',
        'personal_email',
        'password',
        'mobile_no',
        'user_role',
        'last_seen_at',
        'last_login_time',
        'last_password_change',
        'ft_login',
        'failed_attempts',
        'is_account_locked',
        'user_status',
        'remember_token',
        'documents_upload',
        'bg_verified',
        'doc_verified',
        'is_email_verified',
        'is_mobile_verified',
        'i_agree',
        'refferedBy',
        'receiveAccountId',
        'verify_token',
        'is_verified',
        'is_reminders_enabled',
        'is_deleted',
        'process_level',
        'is_draft',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'last_seen_at',
        'last_login_time',
        'last_password_change',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'ft_login' => 'string',
        'is_account_locked' => 'string',
        'user_status' => 'string',
        'documents_upload' => 'string',
        'bg_verified' => 'string',
        'is_email_verified' => 'string',
        'is_mobile_verified' => 'string',
        'i_agree' => 'string',
        'is_verified' => 'string',
        'is_reminders_enabled' => 'string',
        'process_level' => 'integer',
        'is_draft' => 'string',
        'is_deleted' => 'string',
    ];

    protected static $encryptable = [
        'personal_email',
        'mobile_no',
        'first_name',
        'last_name',
        'user_name'
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

    public static function getEncryptData($data)
    {
        $instance = new self();
        return $instance->cipherRepository->encrypt($data);
    }

    public function businessDetail()
    {
        return $this->hasOne(UsersBusinessDetail::class, 'user_id', 'id');
    }

    public function userDocuments()
    {
        return $this->hasMany(AppxpayDocument::class, 'user_id', 'id');
    }
    
    public function serviceFoundReceiveAcc()
    {
        return $this->belongsTo(BankDetails::class, 'receiveAccountId', 'id');
    }

    public function serviceWalletBalance()
    {
        return $this->hasOne(ServiceWallet::class, 'user_id', 'id');
    }

    public function payoutCommissionFee()
    {
        return $this->hasMany(PayoutCommissionCharges::class, 'merchant_id', 'id');
    }

    public function getMerchantList()
    {
        $merchants_list = $this->with('businessDetail')->where('user_role',4)->get()->toArray();
        $response = [];
        if(is_array($merchants_list))
        {
            foreach($merchants_list as $merchant)
            {
                if(!is_null($merchant['business_detail']))
                {
                  $response[$merchant['business_detail']['user_id']] = $merchant['business_detail']['company_name'];
                }
            }
        }
       return $response;
    }
}
