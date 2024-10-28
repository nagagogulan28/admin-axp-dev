<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use DataTables;
use App\Models\PayinTransactions;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MerchantTransactionExport;

class TransactionsController extends Controller
{
    public function __construct(){
        $this->middleware('prevent-back-history');
        $this->middleware('Employee');
        $this->middleware('SessionTimeOut');
    }

    public function getTransactionsList(Request $request){

            // $payinTransactions = DB::table('transactions')
            // ->select(
            //     'transactions.order_id',
            //     'transactions.amount',
            //     'transactions.aggregator_txnid',
            //     'transactions.appxpay_txnid',
            //     'transactions.terminal_id',
            //     'transactions.status',
            //     'transactions.created_at',
            //     'transactions.updated_at',
            //     'order_overview.payload',
            //     'users_business_details.company_name'
            // )
            // ->join('users_business_details', 'users_business_details.user_id', '=', 'transactions.mid')
            // ->join('order_overview', 'transactions.aggregator_txnid', '=', 'order_overview.txnid')
            // ->leftjoin('commissions_history', 'commissions_history.txn_refid', '=', 'transactions.id')
            
            // ->when($request->status !== null, function ($query) use ($request) {
            //     return $query->where('status', $request->status);
            // })
            // ->when($request->merchant_name !== null, function ($query) use ($request) {
            //     return $query->where('mid', $request->merchant_name);
            // })
    
            // ->when($request->date_range !== null, function ($query) use ($request) {
            //     // Assuming your date range parameter is in the format MM/DD/YYYY - MM/DD/YYYY
            //     $dates = explode(' - ', $request->date_range);
            //     $startDate = \Carbon\Carbon::createFromFormat('m/d/Y H:i:s', $dates[0])->startOfDay();
            //     $endDate = \Carbon\Carbon::createFromFormat('m/d/Y H:i:s', $dates[1])->endOfDay();
            //     return $query->whereBetween('transactions.created_at', [$startDate, $endDate]);
            // })
            // ->orderBy('transactions.id', 'desc')  // Specify the table for "id"
            // ->get();
    
            // $payinTransactions =  $payinTransactions->transform(function ($item) {
            //     $decrypted_response = $this->decryptPayload($item->payload);
            //     $res = !is_null($decrypted_response) ? json_decode($this->decryptPayload($item->payload)) : [];
            //     $email     = $res->mail;
            //     $mobile_no = $res->mobile;
            //     list($username, $domain) = explode('@', $email);
            //     $sublength = strlen($username) <= 2 ? 0 : 2;
            //     $maskedUsername = str_repeat('x', strlen($username) - $sublength) . substr($username, -2);
            //     $maskedEmail = $maskedUsername . '@' . $domain;
            //     list($countryCode, $number) = explode('-', $mobile_no);
            //     $maskedNumber = str_repeat('x', strlen($number) - 4) . substr($number, 6, 10);
            //     $maskedMobile = $countryCode . '-' . $maskedNumber;
            //     $item->mail =  $maskedEmail;
            //     $item->mobile = $maskedMobile;
            //     $item->customer_name = $res->customer_name;
            //     unset($item->payload);
            //     return $item;
            // });

            // // dd($payinTransactions);

            // return DataTables::of($payinTransactions)
            // ->addColumn('company_name', function($transaction){
            //     return $transaction->company_name;
            // })
            //     ->addColumn('status', function($transaction){
            //         switch ($transaction->status) {
            //             case 1:
            //                 return 'TXN not initiated';
            //                 break;
            //             case 2:
            //                 return 'Success';
            //                 break;
            //             case 0:
            //                 return 'Failed';
            //                 break;
            //             case 3:
            //                 return 'Tampered';
            //                 break;
            //             case 4:
            //                 return 'Cancelled';
            //                 break;
            //             default:
            //                 return '';
            //         }
            //     })
    
            //     ->make(true);

            $payinTransactions = PayinTransactions::with('orderDetails','businessDetails','commissionDetails')
             ->when($request->status !== null, function ($payinTransactions) use ($request) {
                return $payinTransactions->where('status', $request->status);
            })
            ->when($request->merchant_name !== null, function ($payinTransactions) use ($request) {
                return $payinTransactions->where('mid', $request->merchant_name);
            })
    
            ->when($request->date_range !== null, function ($payinTransactions) use ($request) {
                $dates = explode(' - ', $request->date_range);
                $startDate = \Carbon\Carbon::createFromFormat('m/d/Y H:i:s', $dates[0]);
                $endDate = \Carbon\Carbon::createFromFormat('m/d/Y H:i:s', $dates[1]);
                return $payinTransactions->whereBetween('transactions.created_at', [$startDate, $endDate]);
            })
            ->orderBy('transactions.id', 'desc')
            ->get();

             $payinTransactions =  $payinTransactions->transform(function ($item) {
                
                $businessDetails = $item->businessDetails;
                $order_data = $item->orderDetails;
                $res = !is_null($order_data) ? json_decode($order_data->payload) : [];
                $email     = $res->mail;
                $mobile_no = $res->mobile;
                list($username, $domain) = explode('@', $email);
                $sublength = strlen($username) <= 2 ? 0 : 2;
                $maskedUsername = str_repeat('x', strlen($username) - $sublength) . substr($username, -2);
                $maskedEmail = $maskedUsername . '@' . $domain;
                list($countryCode, $number) = explode('-', $mobile_no);
                $maskedNumber = str_repeat('x', strlen($number) - 4) . substr($number, 6, 10);
                $maskedMobile = $countryCode . '-' . $maskedNumber;
                $item->mail =  $maskedEmail;
                $item->mobile = $maskedMobile;
                $item->company_name = $businessDetails->company_name;
                // $item->
                $item->customer_name = $res->customer_name;
                unset($item->payload);
                return $item;
            });

             return DataTables::of($payinTransactions)
            ->addColumn('company_name', function($transaction){
                return $transaction->company_name;
            })
            ->addColumn('rrn_no', function($transaction){
                return '-';
            })
            ->addColumn('commision_data', function($transaction)
            {
                $commission_data = '-';
                if(!is_null($transaction->commissionDetails))
                {
                    $commission_data = '';
                    $commision = $transaction->commissionDetails;

                    $commission_data .= "<span style='color:blue'>Total fee     :  ".number_format($commision->total_servicefee,3)." </span><br/><span style='color:red'> Partner fee   : ".number_format($commision->partner_flat_fee,3)."</span><br/><span style='color:green'>Profit    : ".number_format($commision->appxpay_flat_fee,3)."</span>";
                   
                }
                return $commission_data;
            })
            ->addColumn('acqrbank_txnid', function($transaction){
                return '-';
            })

            ->addColumn('created_at', function($transaction){
                return $transaction->created_at;
            })
            ->addColumn('updated_at', function($transaction)
            {
                return $transaction->updated_at;
            })
            ->addColumn('status', function($transaction){
                    switch ($transaction->status) {
                        case 1:
                            return 'TXN not initiated';
                            break;
                        case 2:
                            return 'Success';
                            break;
                        case 0:
                            return 'Failed';
                            break;
                        case 3:
                            return 'Tampered';
                            break;
                        case 4:
                            return 'Cancelled';
                            break;
                        default:
                            return '';
                    }
                })
                ->rawColumns(['commision_data'])
                ->make(true);
    }

    public function txnExcelExport(Request $request)
    {
        $payinTransactions = PayinTransactions::with('orderDetails','businessDetails','commissionDetails')
             ->when($request->status !== null, function ($payinTransactions) use ($request) {
                return $payinTransactions->where('status', $request->status);
            })
            ->when($request->merchant_name !== null, function ($payinTransactions) use ($request) {
                return $payinTransactions->where('mid', $request->merchant_name);
            })
    
            ->when($request->datetimes !== null, function ($payinTransactions) use ($request) {
                $dates = explode(' - ', $request->datetimes);
                $startDate = \Carbon\Carbon::createFromFormat('m/d/Y H:i:s', $dates[0]);
                $endDate = \Carbon\Carbon::createFromFormat('m/d/Y H:i:s', $dates[1]);
                return $payinTransactions->whereBetween('transactions.created_at', [$startDate, $endDate]);
            })
            ->orderBy('transactions.id', 'desc')
            ->get();
                

        return Excel::download(new MerchantTransactionExport($payinTransactions->toArray()), 'transactions.xlsx');
    }

    private function decryptPayload($encrypted_payload)
    {
        $method = Config::get('app.decrypt_algorithm');

        $key = Config::get('app.decrypt_key');

        $options = 0;

        $iv = Config::get('app.decrypt_iv');

        $decryptedArray = openssl_decrypt($encrypted_payload, $method, $key, $options, $iv);

        return $decryptedArray;
    }

    
}
