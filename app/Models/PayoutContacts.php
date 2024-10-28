<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Repository\CipherRepository;
use App\State;

use App\Models\PayoutBeneficiaries;

class PayoutContacts extends Model
{
    protected $table = 'payout_contacts';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $cipherRepository;

    public function __construct(array $attributes = [], CipherRepository $cipherRepository = null)
    {
        parent::__construct($attributes);
        $this->cipherRepository = $cipherRepository ?: new CipherRepository();
    }

    protected $fillable = [
        'name', 
        'email', 
        'mobile', 
        'address', 
        'state', 
        'pincode', 
        'merchant_id',
        'is_deleted'
    ];

    protected static $encryptable = [
        'email', 
        'mobile', 
        'address'
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

    public function states()
    {
        return $this->hasOne(State::class, 'id', 'state');
 
    }

    public function payoutBeneficiaries()
    {
     
        return $this->hasMany(PayoutBeneficiaries::class, 'contact_id');
    }

}