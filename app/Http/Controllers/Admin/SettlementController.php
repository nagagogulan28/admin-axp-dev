<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Settlement;
use App\Models\AppxpayDocument;
use App\Repository\SettlementRepository;
use DataTables;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Exports\SettlementSuccessTxnExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SettlementReportExport;

class SettlementController extends Controller 
{

    private $settlement;
    public function __construct(Settlement $settlement)
    {
      $this->settlement = $settlement;
      $this->settlementRepo = app(SettlementRepository::class);
    }

    public function index(Request $request)
{
    $merchantId = $request->input('merchant_id');
    $status = $request->input('status');
    $dateRange = $request->input('datetimes');
    $startDate = null;
    $endDate = null;

    if ($dateRange) {
        $dates = explode(' - ', $dateRange);
        $startDate = \Carbon\Carbon::createFromFormat('m/d/Y H:i:s', $dates[0])->format('Y-m-d H:i:s');
        $endDate = \Carbon\Carbon::createFromFormat('m/d/Y H:i:s', $dates[1])->format('Y-m-d H:i:s');
    }

    $query = Settlement::where('status', '0')
        ->join('users_appxpay', 'live_settlement.merchant', '=', 'users_appxpay.id')
        ->join('users_business_details', 'users_appxpay.id', '=', 'users_business_details.user_id')
        ->select('live_settlement.*', 'users_business_details.company_name as business_name');


    if ($merchantId) {
        $query->where('live_settlement.merchant', $merchantId);
    }

    if ($status) {
        $query->where('live_settlement.status', $status);
    }

    if ($startDate && $endDate) {
        $query->whereBetween('live_settlement.created_at', [$startDate, $endDate]);
    }

    $data = $query->orderByDesc('created_at')->get();
    

    return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('merchant_name', function($row) {
            return $row->business_name;
        })
        ->addColumn('settlement_id', function($row) {
            return $row->report_id;
        })
        ->addColumn('settlement_report', function($row) {
            if (!is_null($row->receipt_url)) {
                return '<a href="' . asset($row->receipt_url) . '" class="btn btn-primary excel_download">Download Excel</a>';
            } else {
                return '-';
            }
        })
        ->addColumn('report_time', function($row) {
            return $row->report_time;
        })
        ->addColumn('success_txn_count', function($row) {
            return $row->success_txn_count;
        })
        ->addColumn('total_transaction_amount', function($row) {
            return '₹ ' . $row->total_txn_amount;
        })
        ->addColumn('merchant_fee', function($row) {
            return $row->fee_amount;
        })
        ->addColumn('gst_amount', function($row) {
            return number_format($row->tax_amount, 2);
        })
        ->addColumn('settlement_amount', function($row) {
            return $row->settlement_amount;
        })
        ->addColumn('created_at', function($row) {
            return $row->created_at;
        })
        ->addColumn('action', function($row) {
            $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="edit btn btn-success btn-sm attach_receipt">Attach Receipt</a>';
            $btn .= '<input type="file" class="d-none upload_receipt" data-id="'.$row->id.'" accept=".jpg,.jpeg,.png">';
            $btn .= '<p></p>';
            $btn .= '<a href="#" data-id="'.$row->id.'" class="mark_as_paid edit btn btn-primary btn-sm">Mark as Paid</a>';
            $btn .= '<p></p>';
            $btn .= '<a href="javascript:void(0)" data-docid="'.$row->document_id.'" class="view_attachment btn btn-info btn-sm">View Attachment</a>';
         
            return $btn;
        })
        ->rawColumns(['action', 'settlement_report'])
        ->make(true);
}
public function settlementdownloadExcel($id)
    {
        $settlement = Settlement::findOrFail($id);
        $settlementTransactionData = collect([$settlement]);
        return Excel::download(new SettlementReportExport($settlementTransactionData), 'settlement_report_' . $id . '.xlsx');
    }


public function attachReceipt(Request $request)
{
    $request->validate([
        
        'receipt' =>  'required|file|mimes:jpg,jpeg,png,|max:2048',
        'id' => 'required|exists:live_settlement,id'
    ]);

    $settlement = Settlement::find($request->id);

    // Find the existing AppxpayDocument or create a new one if it doesn't exist
    $appxapydocs = AppxpayDocument::where('document_id', $settlement->document_id)->first();
    
    if ($request->hasFile('receipt')) {
        $file = $request->file('receipt');
        $fileExtension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $fileExtension;
        $userFolder = 'settlements_' . $request->id;

        // Check if a previous receipt exists and delete it
        if ($appxapydocs && $appxapydocs->document_path) {
            Storage::disk('s3')->delete($appxapydocs->document_path);
        }

        // Store the new file on S3
        $path = Storage::disk('s3')->putFileAs('settlements/' . $userFolder, $file, $filename);

        if ($path) {
            if ($appxapydocs) {
                $appxapydocs->update([
                    'document_path' => $path,
                ]);
            } else {
                $appxapydocs = AppxpayDocument::create([
                    'user_id' => $settlement->id, // Replace with actual user_id if available
                    'document_id' => 11,
                    'base_url' => 'https://appxpaydev.s3.ap-south-1.amazonaws.com', // Replace with your actual base URL
                    'document_path' => $path,
                ]);
            }

            $settlement->document_id = $appxapydocs->id;
            $settlement->save();

            return response()->json(['success' => true, 'message' => 'Receipt attached successfully.', 'document_path' => Storage::disk('s3')->url($path)]);
        }
    }

    return response()->json(['success' => false, 'message' => 'Failed to attach receipt.'], 400);
}


public function markAsPaid(Request $request)
{
    $request->validate([
        'id' => 'required|exists:live_settlement,id'
    ]);

    $settlement = Settlement::find($request->id);

    if ($settlement->document_id == null) {
        return response()->json(['success' => false, 'message' => 'Please attach a receipt before marking as paid.'], 200);
    }

    $settlement->status = 1;
    $settlement->save();

    return response()->json(['success' => true, 'message' => 'Settlement marked as paid.']);
}





    public function createSettlementreport()
    {
        
        $transaction_merchants = DB::table('test_payintransactions')->where('txn_status','2')->where('created_at','>=',Carbon::yesterday())->get()->groupBy('merchant_id')->toArray();

        $merchant_reports = [];

        $index = 0;

        foreach($transaction_merchants as $merchant_id=>$value)
        {
         $get_merchant_id = DB::table('user_keys')->where('test_mid',$merchant_id)->pluck('mid')->first();

         $totalamount = array_column($value, 'amount');  //Getting the amount of each transaction in a merchant.`  
         $settlement_report_id =  "stmnt_".$merchant_id.time().Str::random(5);
         $total_succfultxn_amt = array_sum($totalamount);

         $fee_amount = $total_succfultxn_amt * 0.02;
         $gst_amount = $fee_amount * 0.18;

        $arr =  [
            'settlement_gid'=>$settlement_report_id,
            'settlement_amount' => $total_succfultxn_amt,
            'settlement_fee' => $fee_amount, 
            'settlement_tax' => $gst_amount,
            'settlement_status' => 'Active',
            'settlement_date'  => date('Y-m-d'),
            'created_date' =>  Carbon::now(),
            'created_merchant' => $get_merchant_id,
            'updated_at' =>  Carbon::now(),
         ];

        $merchant_reports[$index] = $arr;

        $index++;


        }

        $insert_status =  DB::table('live_settlement')->insert($merchant_reports);

    }

    public function settlementFileUpload(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'settlement_file' => 'required|file|mimes:jpeg,png,jpg|max:2048', // Max 2MB and image file types
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => 'invalid file type/size'],200);
        }

        if ($request->hasFile('settlement_file')) {
            $file = $request->file('settlement_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('settlement'), $fileName);
            $update =  DB::table('live_settlement')->where('id',$request->settlement_record_id)->update(['receipt_url' => 'settlement/'.$fileName]);
            if($update)
            {
                return response()->json(['success' => 'file uploaded Successfully!!!']);
            }
            else{

            }
           
        } else {
            return response()->json(['error' => 'An error occurred while uploading the file'],200);
        }
    }


    public function PaidStatusUpdate(Request $request)
    {
       
       $update = Settlement::where('id',$request->settlement_id)->update(["status" => '1']);

       if($update)
       {
        return response()->json(['success' => 'Settlement is Marked as Paid'],200);
       }
       else{
        return response()->json(['error' => 'Something went wrong'],500);
       }

        
    }

    public function SettlementList()
    {

        $startDate = request()->input('startDate'); 
       
        $endDate = request()->input('endDate'); 
        // $data = Settlement::where('status','1')->orderBy('updated_at','desc')->get();
        $data = Settlement::where('status','1')->join('merchant','live_settlement.merchant','=','merchant.id')->select('live_settlement.*','merchant.name as merchant_name')
        ->when(!$startDate && !$endDate, function ($query) {
               
            return $query->whereDate('live_settlement.created_at', now()->toDateString());
        })
        ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            return $query->whereRaw('DATE(live_settlement.created_at) BETWEEN ? AND ?', [$startDate, $endDate]);
        })
        ->orderBy('updated_at','desc')->get();
        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('merchant_name', function($row) {
                   return $row->merchant_name;
                })
                ->addColumn('settlement_id', function($row) {
                    return $row->report_id;
                })
                ->addColumn('settlement_report', function ($row) {
                    if (!is_null($row->receipt_url)) {
                        return '<a  href="'.asset($row->receipt_url).'" class="btn btn-primary excel_download">Download Excel</button>';
                    } else {
                        return '-';
                    }})
                ->addColumn('report_time', function($row) {
                    return $row->report_time;
                })
                ->addColumn('success_txn_count', function($row) {
                    return $row->success_txn_count;
                })
                ->addColumn('total_transaction_amount', function($row) {
                    return '₹ '.$row->total_txn_amount;
                })
                ->addColumn('merchant_fee', function($row) {
                    return $row->fee_amount;
                })
                ->addColumn('gst_amount', function($row) {
                    return number_format($row->tax_amount,2);
                })
                ->addColumn('settlement_amount', function($row) {
                    return $row->settlement_amount;
                })
                ->addColumn('created_at', function($row) {
                    return $row->created_at;
                })
                ->addColumn('completed_at', function($row){
                    return $row->updated_at;
                })
                // ->rawColumns(['action'])
                ->rawColumns(['settlement_report'])
                ->make(true);
    }
     
    public function ExcelFilereportGenerator($data){

        $fileName = $data[0]['merchant_id'].now()->format('YmdHis') . '.xlsx';
        $filePath = 'settlement/'.$data[0]['merchant_id'].'/'.date('Y-m-d')."/".$fileName;
    
        Log::channel('debug')->info("excel name log ".json_encode($data));

        // $excelData = (new SettlementSuccessTxnExport($data))->store('excel', 's3');

        Storage::disk('s3')->put($filePath, Excel::raw(new SettlementSuccessTxnExport($data), \Maatwebsite\Excel\Excel::XLSX));

        return $filePath;
    }

    public function downloadExcel($filename)
    {
        $file = storage_path('app/settlement/' .$filename);

        return response()->download($file); 
    }

    public function test()
    {
        $this->settlementRepo->generateSettlementreport();
    }
    
    
    
    public function getattachReceipt($doc_id)
{
    $appxapydocs = AppxpayDocument::find($doc_id);

    if (!is_null($appxapydocs)) {
        $img_url = $appxapydocs->base_url . '/' . $appxapydocs->document_path;
        echo json_encode(['success' => true, 'img_link' => $img_url]);
    } else {
        return response()->json(['success' => false, 'message' => 'No attachment found. Please attach the receipt and try again.']);
    }
}


  }
