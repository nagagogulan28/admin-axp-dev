<?php

namespace App\Repository;

use App\Settlement;
use App\Repository\BaseRepository;
use App\Interfaces\SettlementRepositoryInterface;
use Illuminate\Support\Collection;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PayinTransactions;
use Illuminate\Support\Facades\Validator;
use App\Models\MerchantSettlementOption;
use Excel;
use DB;
use App\Exports\SettlementSuccessTxnExport;
use Illuminate\Support\Facades\Log;
use App\Models\SettlementSlotTypes;
use App\Http\Controllers\Admin\SettlementController;

class SettlementRepository extends BaseRepository implements SettlementRepositoryInterface
{
    public function __construct(Settlement $model)
    {
        parent::__construct($model);
    }
 
    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();    
    }

    public function generateSettlementreport()
    {
        $current_time_in_ist = Carbon::now('Asia/Kolkata');
        // round the hit time in a 0 seconds
        $current_time_in_ist->second(0);

        // $current_time_in_ist = '04:00:00';
        // $current_time_in_ist->setTime(11, 0, 0);
        // $current_time_in_ist->minutes(0);

        $current_formatted_time = $current_time_in_ist->format('H:i:s');

        $current_time_slots = MerchantSettlementOption::join('settlement_slot_types','settlement_slot_types.type_id','merchant_settlement_option.type_id')
        ->join('user_keys','user_keys.mid','merchant_settlement_option.mid')
        ->select('settlement_slot_types.txnreport_start_time','settlement_slot_types.txnreport_end_time','settlement_slot_types.report_generate_time','settlement_slot_types.type_id','settlement_slot_types.is_prev_date_incl','user_keys.prod_mid')->where('report_generate_time',$current_formatted_time)->get()->toArray();

        $current_time_slot_group = collect($current_time_slots)->groupBy('type_id')->toArray();

        if(!empty($current_time_slot_group) && is_array($current_time_slot_group) && count($current_time_slot_group) > 0 )
        {
          foreach($current_time_slot_group as $type_id=>$slots)
          {
            $slot['start_time']   = $slots[0]['txnreport_start_time'];
            $slot['end_time']     = $slots[0]['txnreport_end_time'];
            $slot['slot_type_id'] = $type_id;
            
            foreach($slots as $slot_record)
            {
                $slot['mid']               = $slot_record['prod_mid'];
                $slot['is_prev_date_incl'] = $slot_record['is_prev_date_incl'];
                $this->getMerchantTransactions($slot);
            }
            
          }
        }
        else{
            Log::channel('settlementlog')->info("No settlement slots available on ".$current_formatted_time);
            die();
        }
    }


    public function getMerchantTransactions($slot)
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $type_id = $slot['slot_type_id'];

        if(!$type_id)
        {
            $start_time = $yesterday->setTime(0, 0, 0);  
            $end_time   = $today->setTime(0, 0, 0);
        }
        elseif($slot['is_prev_date_incl'])
        {
            $start_time = $yesterday->setTimeFromTimeString($slot['start_time']);
            $end_time = date("Y-m-d ".$slot['end_time']);
        }
        else{
            $start_time = date("Y-m-d ".$slot['start_time']);
            $end_time = date("Y-m-d ".$slot['end_time']);
        }

        $end_time = Carbon::parse($end_time)->subSeconds(1);
        
        $start_time_format = Carbon::parse($start_time)->format('h:i:s A');
        $end_time_format = Carbon::parse($end_time)->format('h:i:s A');

        // echo "mid : ".$slot['mid'];echo "<br/>";
        // echo "start time : ".$start_time;echo "<br/>";
        // echo "end time : ".$end_time;echo "<br/>";

        $merchant_trxns = PayinTransactions::select('merchant.name','payin_transactions.customer_name','payin_transactions.mobile_no','payin_transactions.email','payin_transactions.amount','payin_transactions.order_id','payin_transactions.fpay_trxnid','payin_transactions.evok_txnid','payin_transactions.rrn_no','payin_transactions.acqrbank_txnid','payin_transactions.created_at','payin_transactions.updated_at','payin_transactions.merchant_id','user_keys.mid')->join('user_keys','payin_transactions.merchant_id','user_keys.prod_mid')
        ->join('merchant','user_keys.mid','merchant.id')->where('payin_transactions.merchant_id',$slot['mid'])->where('txn_status','2')->whereBetween('created_at', [$start_time, $end_time])->get(['payin_transactions.amount','user_keys.mid'])->toArray();

        if(!empty($merchant_trxns) && is_array($merchant_trxns) && count($merchant_trxns) > 0 )
        {
        $settlement     = new Settlement();
        $settlementExpo = new SettlementController($settlement);
        $file_path      = $settlementExpo->ExcelFilereportGenerator($merchant_trxns);
        
        $total_success_txn_count = count($merchant_trxns);
        $totalamount = array_column($merchant_trxns, 'amount'); 
        $total_succfultxn_amt = array_sum($totalamount);

        // Log::channel('settlementlog')->info("settlement report time ".$time_arr[$report_count]);

        $random_id =  "FPAYSMT".$merchant_trxns[0]['merchant_id'].time().Str::random(8);
        $settlement_report_id = substr($random_id, 0, 30);

        $merchant_fee_percentage = MerchantSettlementOption::where('mid',$merchant_trxns[0]['mid'])->get()->pluck('settlement_fee')->toArray();

        $merchant_fee_percentage = $merchant_fee_percentage[0]/100;
        $fee_amount = $total_succfultxn_amt * $merchant_fee_percentage;
        $gst_amount = $fee_amount * 0.18;

        $arr =  [
            'report_id'=>$settlement_report_id,
            'receipt_url' => $file_path,
            'total_txn_amount' => $total_succfultxn_amt,
            'settlement_amount' => $total_succfultxn_amt - ( $fee_amount + $gst_amount ),
            'fee_amount' => $fee_amount, 
            'tax_amount' => $gst_amount,
            'success_txn_count' => $total_success_txn_count,
            'status' => '0',
            'type_id' => $type_id,
            'report_time'  => $start_time_format.' - '.$end_time_format,
            'created_at' =>  Carbon::now(),
            'merchant' => $merchant_trxns[0]['mid'],
            ];

        Log::channel('settlementlog')->info("insert record  ".json_encode($arr));

        $insert_status =  Settlement::insert($arr);

        }
        else{
            Log::channel('settlementlog')->info('no transactions found!!!');
            echo 'no transactions found!!!';
        }
    
    }
}