<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\Facades\DataTables;
use App\Navigation;
use App\Models\Role;
use App\Employee;
use App\Models\Permissions;
use App\Models\Rolespermissions;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\BankDetails;
use App\Models\UserAppxpay;
use App\Models\ServiceWallet;
use App\Models\ServiceWalletTransaction;
use App\Models\Bank;
use Illuminate\Support\Facades\Log;
use App\Models\BankAccountType;

class ManageOverAllAccountsController extends Controller
{

    public function __construct()
    {
        $this->middleware('prevent-back-history');
        $this->middleware('Employee');
        $this->middleware('SessionTimeOut');
    }

    public function getRequestData(Request $request, $id = null)
    {
        if ($id) {

            $data = ServiceWalletTransaction::with('apxBank.bankName', 'apxBank.bankAccountType', 'receiveAmtReceipt')->find($id);

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
            $data = ServiceWalletTransaction::find($id);
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
                            Log::channel('addServiceFundLog')->info("Wallet Actual  record" . json_encode($serviceWallet));
                            if (!$serviceWallet) {
                                $serWal = ServiceWallet::create([
                                    'user_id' => $data->user_id,
                                    'current_balance' => $data->amount,
                                    'total_service_fund' => $data->amount
                                ]);
                                Log::channel('addServiceFundLog')->info("Wallet Updated record" . json_encode($serWal));
                                if (!$serWal) {
                                    return response()->json([
                                        'status' => 'error',
                                        'message' => 'Something went wrong!!',
                                    ], 200);
                                }
                            } else {
                                $serviceWallet->current_balance = $serviceWallet->current_balance + $data->amount;
                                $serviceWallet->total_service_fund = $serviceWallet->total_service_fund + $data->amount;
                                if ($serviceWallet->save()) {
                                    Log::channel('addServiceFundLog')->info("Wallet Updated record" . json_encode($serviceWallet));
                                }
                            }
                            Log::channel('addServiceFundLog')->info("Add Fund Request Record" . json_encode($data));
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
        // Retrieve filter parameters
        $merchantId = $request->get('merchant_id');
        $status = $request->get('status');
        $datetimes = $request->get('datetimes');

        // Initialize query
        $query = ServiceWalletTransaction::with('apxBank.bankName', 'modeTxn', 'businessDetail');

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


    public function receiveAmount(Request $request)
    {
        $getRoles = Role::get();
        $mainMenuCollection = Permissions::where('modulesType', 0)->get();
        $banks = Bank::get();
        $banks = $banks->pluck('name', 'id')->prepend('Select a Payment Mode', '');
        $typeList = BankAccountType::where('type', 'service_wallet')->get()->pluck('name', 'id')->prepend('Select a Type', '0');

        return view("manage_accounts.accountslist", compact('getRoles', 'banks', 'mainMenuCollection', 'typeList'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required|integer',
            'account_using_type' => 'required|integer',
            'account_holder_name' => 'required|string|max:255',
            'account_number' => 'required|min:9|max:18|regex:/^[0-9]+$/',
            'branch' => 'required|string|max:255',
            'ifsc_code' => 'required|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 200);
        }

        $accNo = BankDetails::getEncryptData($request->account_number);

        $accCheckQuery = BankDetails::where('account_no', $accNo);

        if ($request->has('bank_row_id')) {
            $accCheckQuery->where('id', '!=', $request->bank_row_id);
        }

        $accCheck = $accCheckQuery->exists();

        if ($accCheck) {
            return response()->json([
                'status' => 'error',
                'message' => 'This Account No is already added!!'
            ], 200);
        }

        $employee = auth()->guard('employee')->user();

        if ($employee) {

            $dataCreate = [
                'user_id' => $employee->id,
                'bank_id' => $request->bank_name,
                'account_holder_name' => $request->account_holder_name,
                'account_no' => $request->account_number,
                'account_type' => $request->account_using_type,
                'status' => 1,
                'branch' => $request->branch,
                'ifsc_code' => $request->ifsc_code,
            ];

            if ($request->has('bank_row_id')) {
                $getData = BankDetails::find($request->bank_row_id);

                if (!$getData) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'No Record Found!'
                    ], 200);
                }

                if ($getData->update($dataCreate)) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Bank Account Updated Successfully!'
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to update Bank Account. Please try again!'
                    ], 200);
                }
            }

            $businessDetail = BankDetails::create($dataCreate);

            if ($businessDetail) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Bank Account Added Successfully!!'
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Something went wrong!! Please Contact Admin!!'
                ], 200);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!! Please Contact Admin!!'
            ], 200);
        }
    }

    public function statusChange(Request $request, $id)
    {
        if ($id) {
            $data = BankDetails::find($id);
            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Record Not Found!!'
                ], 200);
            }
            
            $status = $data-> status == '1' ? '0' : '1';

            if($data->update(['status' => $status ])){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Updated Successfully!!',
                ], 200);

            }else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Something went wrong!!'
                ], 200);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!!'
            ], 200);
        }
    }

    public function getBank(Request $request, $id)
    {
        if ($id) {
            $data = BankDetails::with('bankAccountType', 'bankName')->find($id);
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

    public function bankList(Request $request)
    {
        $respectiveType = $request->input('RespectiveType');

        $listofBank = BankDetails::with('bankAccountType');
        
        if ($respectiveType && $respectiveType != '0') {
            $listofBank->where('account_type', $respectiveType);
        }
        
        return DataTables::of($listofBank)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $class = 'bg-completed';
                $text = 'Activate';
                if($row->status == '1'){
                    $class = 'bg-draft';
                    $text = 'Deactivate';
                }
                return '<button type="button" data-id="' . $row->id . '" class="bank_details update width-btn btn btn-primary btn-sm bg-edit mr-2">Update</button><button type="button" data-id="' . $row->id . '" data-status="' . $row->status . '" class="btn update-row btn-primary btn-sm '.$class.'">'.$text.'</button>';
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
}
