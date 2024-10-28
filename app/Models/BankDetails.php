<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Repository\CipherRepository;
use App\Models\UserAppxpay;
use App\Models\BankAccountType;
use App\Models\Bank;

class BankDetails extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_bank_accounts';

    protected $primaryKey = 'id';

    protected $cipherRepository;

    public function __construct(array $attributes = [], CipherRepository $cipherRepository = null)
    {
        parent::__construct($attributes);
        $this->cipherRepository = $cipherRepository ?: new CipherRepository();
    }

    protected $fillable = [
        'bank_id',
        'user_id',
        'bank_name',
        'account_holder_name',
        'account_no',
        'branch',
        'account_type',
        'status',
        'ifsc_code',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static $encryptable = [
        'account_holder_name',
        'account_no',
        'ifsc_code',
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

    /**
     * Get the user that owns the bank detail.
     */
    public function user()
    {
        return $this->belongsTo(UserAppxpay::class, 'user_id');
    }

    /**
     * Get the bank account type that owns the bank detail.
     */
    public function bankAccountType()
    {
        return $this->belongsTo(BankAccountType::class, 'account_type');
    }

    public function bankName()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }
}
