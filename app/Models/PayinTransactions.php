<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use Auth;
use DateTime;

class PayinTransactions extends Model
{

    protected $table= "transactions";

    public $primarykey = 'id';

    public function __construct()
    {

    }

    public function orderDetails()
    {
        return $this->hasOne('App\Models\OrderOverview', 'id', 'id');
    }

    public function businessDetails()
    {
        return $this->hasOne('App\Models\UsersBusinessDetail', 'user_id', 'mid');
    }

    public function commissionDetails()
    {
        return $this->hasOne('App\Models\ServicewallletCommisionHistory', 'txn_refid', 'id');
    }
    
}