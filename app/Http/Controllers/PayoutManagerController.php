<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\UserAppxpay;
use App\Models\PayoutCommissionCharges;


class PayoutManagerController extends Controller
{

    public function commissionIndex(Request $request)
    {
        return view('payout.commissonfee');
    }

    public function slabUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'merchant_id' => 'required|integer',
            'percentage' => 'required|numeric|min:0|max:100',
            'commission_settings_id' => 'required|integer',
            'flat_fee_limit' => 'required|integer',
            'imps' => 'required|array|min:1',
            'imps.*' => 'required_with:imps|numeric|min:0',
            'neft' => 'required|array|min:1',
            'neft.*' => 'required_with:neft|numeric|min:0',
            'rtgs' => 'required|array|min:1',
            'rtgs.*' => 'required_with:rtgs|numeric|min:0',
            'upi' => 'required|array|min:1',
            'upi.*' => 'required_with:upi|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 500);
        }

        $userData = UserAppxpay::with('businessDetail')->find($request->merchant_id);

        if ($userData) {

            $userData->businessDetail->update(['payout_percentage_fees' => $request->percentage]);

            $getRow = PayoutCommissionCharges::find($request->commission_settings_id);

            if ($getRow) {
                $getRow->range_id = $request->flat_fee_limit;
                $getRow->IMPS = $request->imps[0] ?? null;
                $getRow->NEFT = $request->neft[0] ?? null;
                $getRow->RTGS = $request->rtgs[0] ?? null;
                $getRow->UPI = $request->upi[0] ?? null;

                if ($getRow->update()) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Slab Updated successfully'
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'errors' => 'Something went wrong!!'
                    ], 500);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'errors' => 'Slab Not Found!!'
                ], 500);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'errors' => 'Merchant Not Found, Something went wrong!!'
            ], 500);
        }
    }

    public function slabStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'merchant_id' => 'required|integer',
            'percentage' => 'required|numeric|min:0|max:100',
            'flat_fee_limit' => 'required|array|min:1',
            'flat_fee_limit.*' => 'required_with:flat_fee_limit|integer',
            'imps' => 'required|array|min:1',
            'imps.*' => 'required_with:imps|numeric|min:0',
            'neft' => 'required|array|min:1',
            'neft.*' => 'required_with:neft|numeric|min:0',
            'rtgs' => 'required|array|min:1',
            'rtgs.*' => 'required_with:rtgs|numeric|min:0',
            'upi' => 'required|array|min:1',
            'upi.*' => 'required_with:upi|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 500);
        }

        $userData = UserAppxpay::with('businessDetail')->find($request->merchant_id);

        if ($userData) {

            $userData->businessDetail->update(['payout_percentage_fees' => $request->percentage]);

            $allSaved = true;

            foreach ($request->flat_fee_limit as $index => $flat_fee_limit) {
                $payoutCommissionCharge = new PayoutCommissionCharges();
                $payoutCommissionCharge->merchant_id = $request->merchant_id;
                $payoutCommissionCharge->status = '1';
                $payoutCommissionCharge->gst = '4';
                $payoutCommissionCharge->range_id = $flat_fee_limit;
                $payoutCommissionCharge->IMPS = $request->imps[$index];
                $payoutCommissionCharge->NEFT = $request->neft[$index];
                $payoutCommissionCharge->RTGS = $request->rtgs[$index];
                $payoutCommissionCharge->UPI = $request->upi[$index];

                $existingRecords = PayoutCommissionCharges::where('merchant_id', $request->merchant_id)
                    ->where('range_id', $flat_fee_limit)
                    ->where('status', '1')
                    ->exists();

                if ($existingRecords) {
                    PayoutCommissionCharges::where('merchant_id', $request->merchant_id)
                        ->where('range_id', $flat_fee_limit)
                        ->where('status', '1')
                        ->update(['status' => '0']);
                }

                if (!$payoutCommissionCharge->save()) {
                    $allSaved = false;
                    break;
                }
            }

            if ($allSaved) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Slab saved successfully'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'errors' => 'Something went wrong!!'
                ], 500);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'errors' => 'Merchant Not Found, Something went wrong!!'
            ], 500);
        }
    }

    public function slabIndex(Request $request, $id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:users_appxpay,id',
        ]);

        if ($validator->fails()) {
            return Redirect::route('payout.merchant.commission')
                ->with('status', 'error')
                ->with('message', 'Invalid ID or ID not found in records.');
        }

        $userData = UserAppxpay::with('businessDetail')->find($id);

        return view('payout.commissionsettings')
            ->with('status', 'success')
            ->with('message', 'Records retrieved successfully.')
            ->with('user', $userData);
    }

    public function listofslabs(Request $request)
    {
        $slabList = PayoutCommissionCharges::with([
            'getRange',
            'merchant' => function ($query) {
                $query->select('id', 'first_name', 'last_name', 'personal_email', 'user_label_id');
            },
            'merchant.businessDetail' => function ($query) {
                $query->select('id', 'user_id', 'payout_percentage_fees', 'company_name');
            }
        ])
            ->where('merchant_id', $request->input('merchant_id'))
            ->orderBy('id', 'desc')
            ->get();
        return DataTables::of($slabList)
            ->addColumn('minRange', function ($row) {
                if (isset($row->getRange)) {
                    list($range_start, $range_end) = explode('-', $row->getRange->option_value);
                    return $range_start . ' ₹';
                } else {
                    return '0 ₹';
                }
            })
            ->addColumn('maxRange', function ($row) {
                if (isset($row->getRange)) {
                    list($range_start, $range_end) = explode('-', $row->getRange->option_value);
                    return $range_end . ' ₹';
                } else {
                    return '0 ₹';
                }
            })
            ->addColumn('action', function ($row) {

                $buttonHtml = '<button type="button" '
                    . 'data-collection=\'' . htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') . '\' '
                    . 'data-toggle="modal" '
                    . 'data-target="#update-slab-settings-model" '
                    . 'class="width-btn btn update-fee-settings btn-primary bg-edit btn-sm mr-2">Update</button>';

                return $buttonHtml;
            })
            ->rawColumns(['minRange', 'maxRange', 'action'])
            ->make(true);
    }

    public function listCommission(Request $request)
    {
        $payoutlist = UserAppxpay::with('payoutCommissionFee', 'businessDetail')->where('is_deleted', 'N')->where('is_verified', 'Y')->where('is_draft', 'N')->get();

        return DataTables::of($payoutlist)
            ->addColumn('slabCount', function ($row) {
                $countSettings = 0;
                if (count($row->payoutCommissionFee) > 0) {
                    $countSettings = count($row->payoutCommissionFee);
                }
                return $countSettings;
            })
            ->addColumn('action', function ($row) {
                $url = route('slab.list.commission', ['id' => $row->id]);
                return '<a href="' . $url . '" class="btn btn-success btn-sm">Manage</a>';
            })
            ->addColumn('company_name', function ($row) {
                $name = $row->businessDetail->company_name;
                return $name != null && $name != ''  ? $name : '-';
            })
            ->rawColumns(['slabCount', 'action', 'company_name'])
            ->make(true);
    }
}
