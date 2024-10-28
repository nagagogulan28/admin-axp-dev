<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class CfRefund extends Model
{
    
    protected $table = 'appxpay_refund';

    public function merchant()
    {
        return $this->hasOne('App\User', 'id', 'merchant_id');
    }

}
