<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PayinTransactions;
use App\Repository\CipherRepository;

class OrderOverview extends Model
{
    use HasFactory;

    protected $table = 'order_overview';

    protected static $encryptable = [
        'payload',
    ];

    protected $cipherRepository;

    public function __construct(array $attributes = [], CipherRepository $cipherRepository = null)
    {
        parent::__construct($attributes);
        $this->cipherRepository = $cipherRepository ?: new CipherRepository();
    }

    public static function boot()
    {
        parent::boot();

        static::retrieved(function ($model) {
            foreach (static::$encryptable as $field) {
                if (!empty($model->$field)) {
                    $model->$field = $model->cipherRepository->decryptPayload($model->$field);
                }
            }
        });
    }
    
    public function transaction()
    {
        return $this->belongsTo(PayinTransactions::class);
    }
}
