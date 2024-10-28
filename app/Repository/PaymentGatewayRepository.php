<?php
namespace App\Repository;

use App\Repository\BaseRepository;
use DB;
use App\Models\PayinTransactions;

class PaymentGatewayRepository extends BaseRepository
{

    public function __construct(PayinTransactions $model)
    {
        parent::__construct($model);
    }

    public function getTransactionStatus($order_id,$fpay_txnId)
    {
        $transaction = PayinTransactions::where('order_id',$order_id)->where('fpay_trxnid',$fpay_txnId)->first();

        if(!is_null($transaction))
        {
            $res['status'] = ($transaction->txn_status == '1') ? "pending" : 
            (($transaction->txn_status == '4') ? "cancelled" : 
            (($transaction->txn_status == '3') ? "tampered" : 
            (($transaction->txn_status == '0') ? "failed" : "success")));

            $res['utr_no'] = $res['status'] == "success" ? $transaction->rrn_no : null; 
        }
        else{
            $res['status'] = 'no-data';
        }
        
        echo json_encode($res);die();
    }
}
?>