<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PayoutDashboardStatistic;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use App\Models\UserAppxpay;
use App\Models\PayoutServiceWalletTransaction;
use App\Models\BankDetails;
use App\Models\ServiceWallet;
use App\Models\PayoutTransactions;
use Illuminate\Support\Facades\Log;
use App\Models\PayoutOptions;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PayoutAdminController extends Controller
{
    public function index()
    {
        $merchantsUsers = UserAppxpay::whereHas('businessDetail', function ($query) {
            $query->where('pay_out', 1);
        })
            ->where('user_status', 'active')
            ->where('is_verified', 'Y')
            ->where('is_deleted', 'N')
            ->get();

        $totalactiveusers = count($merchantsUsers);

        // Logic to retrieve and display payouts
        return view('payout.dashboard', compact('merchantsUsers', 'totalactiveusers'));
    }

    public function getTerminalsByMerchantId(Request $request)
    {
        $merchantId = $request->merchantId;
    
        // Fetch the related terminals through the businessDetail and businessApps relationships
        $terminals = UserAppxpay::with(['businessDetail.businessApps'])
            ->where('user_status', 'active')
            ->where('is_verified', 'Y')
            ->where('is_deleted', 'N')
            ->where('id', $merchantId)
            ->get()
            ->pluck('businessDetail.businessApps')
            ->flatten()
            
            ->unique();  // Fetch unique terminal IDs
    
        return response()->json($terminals);
}
public function dashboardStatistics(Request $request)
    
{
    $merchantId = $request->input('merchantId');
    $terminalId = $request->input('terminalId'); // Changed from websiteId to terminalId

    $dateRange = $request->date;

    if (!is_null($dateRange)) {
        $dates = explode(' - ', $dateRange);
        $startdate = Carbon::createFromFormat('m/d/Y', $dates[0])->format('Y-m-d');
        $enddate = Carbon::createFromFormat('m/d/Y', $dates[1])->format('Y-m-d');
    } else {
        $current_date = Carbon::now()->format('m/d/Y');
        $startdate = Carbon::createFromFormat('m/d/Y', $current_date)->format('Y-m-d');
        $enddate = Carbon::createFromFormat('m/d/Y', $current_date)->format('Y-m-d');
    }
    $parsedStartDate = Carbon::createFromFormat('Y-m-d', $startdate)->startOfDay();
    $parsedEndDate = Carbon::createFromFormat('Y-m-d', $enddate)->endOfDay();

    // Initialize the query with the date filter
    $statistics_data = PayoutDashboardStatistic::whereBetween('created_at', [$parsedStartDate, $parsedEndDate]);


    // Apply merchant_id filter if merchantId is provided
    if (!is_null($merchantId)) {
        $statistics_data = $statistics_data->where('merchant_id', $merchantId);
    }
  // Apply terminal_id filter if terminalId is provided
  if ($terminalId != 0) {
            
    $statistics_data = $statistics_data->where('terminal_id', $terminalId); // Changed from website_id to terminal_id
}

// If both merchantId and terminalId are null, it will get all values
$statistics_data = $statistics_data->get()->toArray();

// Process the data and sum up numeric values
$collection = collect($statistics_data);

$summed = $collection->reduce(function ($carry, $item) {
    foreach ($item as $key => $value) {
        if (is_numeric($value)) {
            $carry[$key] = isset($carry[$key]) ? $carry[$key] + $value : $value;
        } elseif (is_string($value) && is_numeric($numericValue = floatval($value))) {
            $carry[$key] = isset($carry[$key]) ? $carry[$key] + $numericValue : $numericValue;
        }
    }
    return $carry;
}, []);

return response()->json(['transactionStats' => $summed], 200);
}




    public function __construct()
    {
        $this->middleware('prevent-back-history');
        $this->middleware('Employee');
        $this->middleware('SessionTimeOut');
    }

    public function showAddFund()
    {
        $mlist = UserAppxpay::with('businessDetail')->where('user_role', 4)->get();
        $status = ['0' => 'Pending', '1' => 'Approved', '2' => 'Declined'];
        $status = ['' => 'Select a Status'] + $status;

        $businessNames = $mlist->mapWithKeys(function ($user) {
            return [$user->id => isset($user->businessDetail) ? $user->businessDetail->company_name : ''];
        })->prepend('Select a Company', '');


        return view('payout.addwalletfund', compact('businessNames', 'status'));
    }

    public function walletFundRequestList(Request $request)
    {
        // dd($request->all());
        // Retrieve filter parameters
        $merchantId = $request->get('merchant_id');
        $status = $request->get('status');
        $datetimes = $request->get('datetimes');

        // Initialize query
        $query = PayoutServiceWalletTransaction::with('apxBank.bankName', 'modeTxn', 'businessDetail');


        // dd($listofRoles);
        // Apply filters
        if ($merchantId) {
            $query->where('user_id', $merchantId);
        }

        if ($status !== null) {
            $query->where('status', $status);
        }

        if ($datetimes) {
            $dateRange = explode(' - ', $datetimes);
            if (count($dateRange) == 2) {
                $startDate = Carbon::createFromFormat('m/d/Y H:i:s', trim($dateRange[0]))->startOfDay();
                $endDate = Carbon::createFromFormat('m/d/Y H:i:s', trim($dateRange[1]))->endOfDay();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        // Fetch filtered data
        $listofRoles = $query->get();

        return DataTables::of($listofRoles)
            ->addIndexColumn()

            ->addColumn('action', function ($row) {
                $action = '';
                if ($row->status == 0) {
                    $action = '<button type="button" data-toggle="modal" data-target="#add-show-request" data-id="' . $row->id . '" class="btn ip-delete btn-primary btn-sm add-amt-request">Validate</button>';
                } else {
                    $action = '<button type="button" style="cursor: default;" class="btn ip-delete btn-success btn-sm">Closed</button>';
                }
                return $action;
            })
            ->addColumn('amount', function ($row) {
                return $row->amount;
            })
            ->addColumn('remark', function ($row) {
                return $row->remark;
            })
            ->addColumn('account_no', function ($row) {
                return $row->account_no ?? '';
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at ?? '';
            })
            ->rawColumns(['checkbox', 'action'])
            ->make(true);
    }



    public function getRequestData(Request $request, $id = null)
    {

        if ($id) {

            $data = PayoutServiceWalletTransaction::with('apxBank.bankName', 'apxBank.bankAccountType', 'receiveAmtReceipt')->find($id);

            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Record Not Found!!'
                ], 200);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data Retrived Successfully!!',
                    'data' => $data
                ], 200);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!!'
            ], 200);
        }
    }

    public function updateRequestData(Request $request, $id = null)
    {
        if ($id) {
            $data = PayoutServiceWalletTransaction::find($id);
            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Record Not Found!!'
                ], 200);
            } else {
                try {
                    if ($data->status == 0 && $request->adminRemark && ($request->requestType == 'approve' || $request->requestType == 'decline')) {
                        $employee = auth()->guard('employee')->user();
                        $data->status = $request->requestType == 'approve' ? 1 : 2;
                        $data->approved_by = $employee->id;
                        $data->validate_remark = $request->adminRemark;
                        if ($request->requestType == 'approve') {
                            $serviceWallet = ServiceWallet::where('user_id', $data->user_id)->first();
                            Log::channel('addPayoutwalletFundLog')->info("Wallet Actual  record" . json_encode($serviceWallet));
                            if (!$serviceWallet) {
                                $serWal = ServiceWallet::create([
                                    'user_id' => $data->user_id,
                                    'payout_current_balance' => $data->amount,
                                    'payout_total_service_funds' => $data->amount
                                ]);
                                Log::channel('addPayoutwalletFundLog')->info("Wallet Updated record" . json_encode($serWal));
                                if (!$serWal) {
                                    return response()->json([
                                        'status' => 'error',
                                        'message' => 'Something went wrong!!',
                                    ], 200);
                                }
                            } else {
                                $serviceWallet->payout_current_balance = $serviceWallet->payout_current_balance + $data->amount;
                                $serviceWallet->payout_total_service_funds = $serviceWallet->payout_total_service_funds + $data->amount;
                                if ($serviceWallet->update()) {
                                    Log::channel('addPayoutwalletFundLog')->info("Wallet Updated record" . json_encode($serviceWallet));
                                }
                            }
                            Log::channel('addPayoutwalletFundLog')->info("Add Fund Request Record" . json_encode($data));
                        }
                        $data->save();
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Updated Successfully!!',
                        ], 200);
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Something went wrong!!',
                        ], 200);
                    }
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Something went wrong!!',
                        'error' => $e->getMessage()
                    ], 500);
                }
                $employee = auth()->guard('employee')->user();
                $dataUpdate = $data->update(['status' => 1, 'approved_by' => $employee->id]);
                if ($dataUpdate) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Updated Successfully!!',
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Something went wrong!!'
                    ], 200);
                }
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!!'
            ], 200);
        }
    }

    public function serviceFundIndex(Request $request)
    {
        $mlist = UserAppxpay::with('businessDetail')->where('user_role', 4)->get();
        $status = ['0' => 'Pending', '1' => 'Approved', '2' => 'Declined'];
        $status = ['' => 'Select a Status'] + $status;

        $businessNames = $mlist->mapWithKeys(function ($user) {
            return [$user->id => isset($user->businessDetail) ? $user->businessDetail->company_name : ''];
        })->prepend('Select a Company', '');

        return view('manage_accounts.serviceFunds', compact('businessNames', 'status'));
    }


    public function addFundRequestList(Request $request)
    {
        // dd($request->all());
        // Retrieve filter parameters
        $merchantId = $request->get('merchant_id');
        $status = $request->get('status');
        $datetimes = $request->get('datetimes');

        // Initialize query
        $query = PayoutServiceWalletTransaction::with('apxBank', 'businessDetail');


        // dd($listofRoles);
        // Apply filters
        if ($merchantId) {
            $query->where('user_id', $merchantId);
        }

        if ($status !== null) {
            $query->where('status', $status);
        }

        if ($datetimes) {
            $dateRange = explode(' - ', $datetimes);
            if (count($dateRange) == 2) {
                $startDate = Carbon::createFromFormat('m/d/Y H:i:s', trim($dateRange[0]))->startOfDay();
                $endDate = Carbon::createFromFormat('m/d/Y H:i:s', trim($dateRange[1]))->endOfDay();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        // Fetch filtered data
        $listofRoles = $query->get();

        return DataTables::of($listofRoles)
            ->addIndexColumn()

            ->addColumn('action', function ($row) {
                $action = '';
                if ($row->status == 0) {
                    $action = '<button type="button" data-id="' . $row->id . '" class="btn ip-delete btn-primary btn-sm add-amt-request">Validate</button>';
                } else {
                    $action = '<button type="button" style="cursor: default;" class="btn ip-delete btn-success btn-sm">Closed</button>';
                }
                return $action;
            })
            ->addColumn('amount', function ($row) {
                return $row->amount;
            })
            ->addColumn('remark', function ($row) {
                return $row->remark;
            })
            ->addColumn('account_no', function ($row) {
                return $row->account_no ?? '';
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at ?? '';
            })
            ->rawColumns(['checkbox', 'action'])
            ->make(true);
    }


    public function receiveAmount(Request $request)
    {
        $getRoles = Role::get();
        $mainMenuCollection = Permissions::where('modulesType', 0)->get();
        $banks = Bank::get();
        $banks = $banks->pluck('name', 'id')->prepend('Select a Payment Mode', '');

        return view("manage_accounts.accountslist", compact('getRoles', 'banks', 'mainMenuCollection'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required|string|max:255',
            'account_holder_name' => 'required|string|max:255',
            'account_number' => 'required|min:9|max:18|regex:/^[0-9]+$/',
            'branch' => 'required|string|max:255',
            'ifsc_code' => 'required|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 400);
        }

        $accNo = BankDetails::getEncryptData($request->account_number);

        $accCheck = BankDetails::where('account_no', $request->account_number)->get();

        if (count($accCheck) > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'This Account No alreday added!!'
            ], 200);
        }

        $employee = auth()->guard('employee')->user();

        if ($employee) {

            $businessDetail = BankDetails::create([
                'user_id' => $employee->id,
                'bank_id' => 3,
                'account_holder_name' => $request->account_holder_name,
                'account_no' => $request->account_number,
                'account_type' => 3,
                'status' => 1,
                'branch' => $request->branch,
                'ifsc_code' => $request->ifsc_code,
            ]);

            if ($businessDetail) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Bank Account Added Successfully!!'
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Something went wrong!! Please Contact Admin!!'
                ], 422);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!! Please Contact Admin!!'
            ], 422);
        }
    }

    public function bankList(Request $request)
    {
        $listofRoles = BankDetails::get();

        return DataTables::of($listofRoles)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<button type="button" data-id="' . $row->id . '" class="btn ip-delete btn-primary btn-sm">Deactivate</button>';
            })
            ->addColumn('account_holder_name', function ($row) {
                return $row->account_holder_name;
            })
            ->addColumn('branch', function ($row) {
                return $row->branch;
            })
            ->addColumn('account_no', function ($row) {
                return $row->account_no ?? '';
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at ?? '';
            })
            ->rawColumns(['checkbox', 'action'])
            ->make(true);
    }


    public function AdminPayoutTransactions()
    {
        $merchantsUsers = UserAppxpay::with('businessDetail')
            ->where('user_status', 'active')
            ->where('is_verified', 'Y')
            ->where('is_deleted', 'N')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'company_name' => optional($user->businessDetail)->company_name // Use optional() to handle null businessDetail
                ];
            });
        $status = PayoutOptions::where('unique_model_name', 'payout_payment_status')->get()->pluck('option_name', 'option_value');
        return view('employee.payout.payoutoveralltransactions', compact('status', 'merchantsUsers'));
    }

    public function PayoutAdminTransactionList(Request $request)
    {
        if ($request->ajax()) {
            $fromdate = $request->start_date ? Carbon::createFromFormat('d/m/Y H:i:s', trim($request->start_date))->startOfDay()->format('Y-m-d 00:00:00') : Carbon::today()->startOfDay()->format('Y-m-d  H:i:s');
            $todate = $request->end_date ? Carbon::createFromFormat('d/m/Y H:i:s', trim($request->end_date))->endOfDay()->format('Y-m-d 23:59:59') : Carbon::today()->endOfDay()->format('Y-m-d  H:i:s');

            $payout_execute_query = PayoutTransactions::with('getBeneficieries','txnStatus', 'getContacts', 'commisionsData')->whereBetween('created_at', [$fromdate, $todate]);
            
            // $payout_execute_query = PayoutTransactions::with('getBeneficieries', 'txnStatus', 'getContacts', 'commisionsData');

            // dd($payout_execute_query->get());

            if($request->status !== null && $request->status !== '' && $request->status !== ' ' && $request->status !== '0'){
                $payout_execute_query = $payout_execute_query->where('merchant_id', $request->id);
            }

            if ($request->terminalId != '0' && $request->terminalId != '') {
                $payout_execute_query = $payout_execute_query->where('terminal_id', $request->terminalId);
            }

            if ($request->status !== null && $request->status !== '' && $request->status !== ' ' && $request->status !== '0') {
                $payout_execute_query->where('status', $request->status);
            }
            
            $payoutTransactions = $payout_execute_query->get();
            
            return DataTables::of($payoutTransactions)

                ->editColumn('S.No', function ($payoutTransactions) {
                    static $count = 0;
                    return ++$count;
                })
                ->addColumn('user_id', function ($payoutTransactions) {
                    return $payoutTransactions->user_id ?? '-';
                })->addColumn('bene_id', function ($payoutTransactions) {
                    $res = $payoutTransactions->getBeneficieries->first();
                    return $res['beneficiary_id'] ?? '-';
                })->addColumn('ben_name', function ($payoutTransactions) {
                    $res = $payoutTransactions->getBeneficieries->first();
                    return $res['account_holder_name'] ?? '-';
                })->addColumn('ben_phone', function ($payoutTransactions) {
                    $res = $payoutTransactions->getContacts->first();
                    list($countryCode, $number) = explode('-', $res->mobile);
                    $maskLength = max(strlen($number) - 4, 0);
                    $maskedNumber = str_repeat('x', $maskLength) . substr($number, -4);
                    $maskedMobile = $countryCode . '-' . $maskedNumber;

                    return $maskedMobile ?? '-';
                })->addColumn('apx_txnid', function ($payoutTransactions) {
                    return $payoutTransactions->apx_txnid ?? '-';
                })->addColumn('transfer_mode', function ($payoutTransactions) {
                    $res = PayoutOptions::where('option_value', $payoutTransactions->transfer_mode)->get()->pluck('option_name')->first();
                    return $res ?? '-';
                })
                ->addColumn('is_recredited', function ($payoutTransactions) {
                    $commision = $payoutTransactions->commisionsData;
                    if (isset($commision->is_recredited)) {
                        $html = $commision->is_recredited ?  "<span style='color:green'> Refunded </span><br/>" :  "<span style='color:red;'> Debited </span>";
                    } else {
                        $html = '-';
                    }
                    return $html ?? '-';
                })->addColumn('amount', function ($payoutTransactions) {
                    return $payoutTransactions->amount ?? '-';
                })->addColumn('order_id', function ($payoutTransactions) {
                    return $payoutTransactions->order_id ?? '-';
                })
                ->addColumn('prev_balance', function ($payoutTransactions) {
                    $commision = $payoutTransactions->commisionsData;
                    return isset($commision->prev_balance) ?? '-';
                })
                ->addColumn('service_fee', function ($payoutTransactions) {
                    $commision = $payoutTransactions->commisionsData;
                    $service_fee = isset($commision->total_servicefee) && isset($commision->total_servicefee) ? $commision->total_servicefee + $commision->transfer_mode_fee : 0;
                    return  $service_fee;
                })
                ->addColumn('fee_details', function ($payoutTransactions) {
                    $html = '';
                    $commision = $payoutTransactions->commisionsData;
                    $total_deduced_fee =  isset($commision->total_servicefee) && isset($payoutTransactions->amount) &&  isset($commision->transfer_mode_fee)  ? $commision->total_servicefee + $commision->transfer_mode_fee + $payoutTransactions->amount : 0;
                    $closing_balance = !is_null($commision) ? round($commision->closing_balance, 2) : 0;

                    $html .= "<span style='color:green;margin-bottom;5px'> Prev bal : " . round(isset($commision->prev_balance) ? $commision->prev_balance : 0, 2) . "</span><br/>";
                    $html .= "<span style='color:red'> Closing bal : " . $closing_balance . "</span>";
                    return $html ?? '-';
                })
                ->addColumn('closing_balance', function ($payoutTransactions) {
                    $commision = $payoutTransactions->commisionsData;
                    $closing_balance = !is_null($commision) ? round($commision->closing_balance, 2) : 0;
                    return $closing_balance ?? '-';
                })
                ->addColumn('status', function ($payoutTransactions) {
                    return $payoutTransactions->txnStatus->option_name;
                })
                ->rawcolumns(['fee_details', 'is_recredited'])
                ->make();
        } else {
            $terminalIds = DB::table('transactions')
                ->distinct()
                ->pluck('terminal_id');

            return view('merchant.pages.transaction', compact('terminalIds'));
        }
    }
}
