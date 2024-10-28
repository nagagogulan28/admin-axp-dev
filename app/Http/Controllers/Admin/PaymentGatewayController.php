<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PayinTransactions;
use App\Repository\PaymentGatewayRepository;

class PaymentGatewayController extends Controller
{
    public $pg_repositoy;

    public function __construct(){
        $this->pg_repositoy = app(PaymentGatewayRepository::class);
    }

    public function getTransactionStatus(Request $request)
    {
        return $this->pg_repositoy->getTransactionStatus($request->order_id,$request->fpay_txnId);
    }

}
