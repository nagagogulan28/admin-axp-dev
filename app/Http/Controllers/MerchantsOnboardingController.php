<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\UserAppxpay;
use App\Models\UsersBusinessDetail;
use App\Models\BusinessApp;
use App\Models\AppxpayDocument;
use App\Models\DocumentType;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use App\Models\PaymentAggregator;
use App\Models\ServiceWallet;
use App\Models\BankDetails;
use App\Models\BankAccountType;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MerchantTransactionExport;
use Illuminate\Support\Facades\Config;

class MerchantsOnboardingController extends Controller
{
    public function index(Request $request, $id = null)
    {
        //$paymentAggregator = PaymentAggregator::where(['active' => 1])->get();
        if ($id) {

            $user = UserAppxpay::find($id);

            if (!$user) {

                return view("onboarding.onboardingStepOne");
            }

            return view('onboarding.onboardingStepOne')->with(["user" => $user, "userUpdate" => true]);
        } else {

            return view("onboarding.onboardingStepOne");
        }
    }

    public function steptwo(Request $request, $id = null)
    {

        $businesstype = DB::table('business_type')->get();
        $businessCategory = DB::table('business_category')->get();
        $businessSubcategory = DB::table('business_sub_category')->get();
        $listofstate = DB::table('state')->get();
        $monthlyExpenditure = DB::table('app_option')->where('module', 'merchant_business')->get();
        $merchant = DB::table('app_option')->where('module', 'merchant_business')->get();
        $paymentAggregator = PaymentAggregator::where(['active' => 1])->get();

        if ($id) {
            $user = UserAppxpay::with('businessDetail')->find($id);

            if (!$user) {
                return Redirect::to('/onboarding/eqyroksfig')->with('status', 'error')->with('message', 'User ID is not found!!');
            }
            return view('onboarding.onboardingStepTwo')->with(["user" => $user, "state" => $listofstate, "businesstype" => $businesstype, "businesscategory" => $businessCategory, "businesssubcategory" => $businessSubcategory, "monthlyExpenditure" => $monthlyExpenditure, "aggreGator" => $paymentAggregator]);
        } else {
            return Redirect::to('/onboarding/eqyroksfig')->with('status', 'error')->with('message', 'Something went wrong!!');
        }
    }

    public function stepthree(Request $request, $id = null)
    {

        if ($id) {
            $payoutList = BankAccountType::with('bankDetails.bankName')->where('type', 'service_wallet')->where('name', 'Payout')->first();
            $payinList = BankAccountType::with('bankDetails.bankName')->where('type', 'service_wallet')->where('name', 'Payin')->first();
            // echo "<pre>";
            // print_r($payoutList->toArray());
            // echo "<pre>";
            // echo "<pre>";
            // print_r($payinList->toArray());
            // echo "<pre>";
            // dd($payoutList);
            $user = UserAppxpay::with('businessDetail')->find($id);
            $paymentAggregator = PaymentAggregator::where(['active' => 1])->get();
            $vendorUser = UserAppxpay::where(['user_role' => 3, 'is_verified' => 'Y', 'is_draft' => 'N', 'bg_verified' => 'Y'])->get();


            if (!$user) {
                return Redirect::to('/onboarding/eqyroksfig')->with('status', 'error')->with('message', 'User ID is not found!!');
            }

            return view('onboarding.onboardingStepThree')->with(["payoutList" => $payoutList, "payinList" => $payinList, "user" => $user, "vendorList" => $vendorUser, "aggreGator" => $paymentAggregator]);
        } else {

            return Redirect::to('/onboarding/eqyroksfig')->with('status', 'error')->with('message', 'Something went wrong!!');
        }
    }

    public function stepfour(Request $request, $id = null)
    {
        if ($id) {

            $user = UserAppxpay::with('businessDetail')->with('userDocuments.documentType')->find($id);

            if (!$user) {
                return Redirect::to('/onboarding/eqyroksfig')->with('status', 'error')->with('message', 'User ID is not found!!');
            }
            return view('onboarding.onboardingStepFour')->with(["user" => $user]);
        } else {
            return Redirect::to('/onboarding/eqyroksfig')->with('status', 'error')->with('message', 'Something went wrong!!');
        }
    }

    public function validateEmail(Request $request)
    {
        if (isset($request->user_id)) {
            $rules = [
                'user_id' => 'required|integer',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
        }

        $encryptedEmail = UserAppxpay::getEncryptData($request->email);

        $emailQuery = UserAppxpay::where('personal_email', $encryptedEmail);

        if (isset($request->user_id)) {
            $emailQuery->where('id', '!=', $request->user_id);
        }

        $emailExists = $emailQuery->exists();

        return response()->json(['exists' => $emailExists]);
    }

    public function validateMobile(Request $request)
    {
        if (isset($request->user_id)) {
            $rules = [
                'user_id' => 'required|integer',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
        }

        $encryptedPhone = UserAppxpay::getEncryptData($request->mobile);

        $mobileExists = UserAppxpay::where('mobile_no', $encryptedPhone);

        if (isset($request->user_id)) {
            $mobileExists->where('id', '!=', $request->user_id);
        }

        $mobileExists = $mobileExists->exists();

        return response()->json(['exists' => $mobileExists]);
    }

    public function validateUsername(Request $request)
    {
        if (isset($request->user_id)) {
            $rules = [
                'user_id' => 'required|integer',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
        }

        $encrypteduserName = UserAppxpay::getEncryptData($request->userName);

        $usernameExists = UserAppxpay::where('user_name', $encrypteduserName);

        if (isset($request->user_id)) {
            $usernameExists->where('id', '!=', $request->user_id);
        }

        $usernameExists = $usernameExists->exists();

        return response()->json(['exists' => $usernameExists]);
    }

    public function store(Request $request)
    {
        $userId = $request->user_id ?? null;

        $validateFields = [
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            //'assignedPartner'=> 'required|integer',
            'userName' => [
                'required',
                'string',
                'max:50',
                // Unique validation rule, excluding the current user's ID if updating
                'unique:users_appxpay,user_name' . ($userId ? ',' . $userId : '')
            ],
            'email' => [
                'required',
                'email',
                'max:50',
                'unique:users_appxpay,personal_email' . ($userId ? ',' . $userId : '')
            ],
            'mobile' => [
                'required',
                'string',
                'max:10',
                'unique:users_appxpay,mobile_no' . ($userId ? ',' . $userId : '')
            ],
        ];

        if (!isset($request->user_id) && !$request->user_id) {
            $validateFields['password'] = 'required|string|max:255';
        }

        $validator = Validator::make($request->all(), $validateFields);

        if ($validator->fails()) {
            return Redirect::to('onboarding/eqyroksfig')
                ->with('status', 'error')
                ->with('message', $validator->errors()->first());
        }

        if (isset($request->user_id) && $request->user_id) {
            $user = UserAppxpay::with('businessDetail')->find($request->user_id);
            if (!$user) {
                return Redirect::to('/onboarding/eqyroksfig')->with('status', 'error')->with('message', 'User ID is not found!!');
            } else {
                $userUpdate = $user->update([
                    'first_name' => $request->firstname,
                    'last_name' => $request->lastname,
                    'user_name' => $request->userName,
                    'personal_email' => $request->email,
                    'mobile_no' => $request->mobile,
                    // 'assignedPartner'=>$request->assignedPartner,
                    'updated_at' => Carbon::now(),
                ]);
                if ($userUpdate) {
                    return Redirect::to('/onboarding/aftxjcqenf/' . $request->user_id)->with('status', 'success')->with('message', 'Step One Updated Successfully!!');
                } else {
                    return Redirect::to('/onboarding/eqyroksfig')->with('status', 'error')->with('message', 'Something went wrong!!');
                }
            }
        }

        $randomNumber = rand(100000, 999999);
        $user_label_id = 'APPXPAY' . $randomNumber;

        $user = UserAppxpay::create([
            'user_label_id' => $user_label_id,
            'first_name' => $request->firstname,
            'last_name' => $request->lastname,
            'user_name' => $request->userName,
            'personal_email' => $request->email,
            'password' => bcrypt($request->password),
            'mobile_no' => $request->mobile,
            'verify_token' => Str::random(25),
            'process_level' => 1,
            'user_role' => 4,
            //'assignedPartner'=>$request->assignedPartner,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        if ($user) {
            return Redirect::to('/onboarding/aftxjcqenf/' . $user->id)->with('status', 'success')->with('message', 'Step One Completed Successfully!!');
        } else {
            return Redirect::to('/onboarding/eqyroksfig')->with('status', 'error')->with('message', 'Something went wrong!!');
        }
    }

    public function businessStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_type_id' => 'required|integer',
            'user_id' => 'required|integer',
            'business_category_id' => 'required|integer',
            'business_sub_category_id' => 'required|integer',
            'bank_name' => 'required|string|max:255',
            'bank_account_number' =>  'required|min:9|max:18',
            'bank_ifsc_code' => 'required|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
            'branch_name' => 'required|string|max:255',
            'account_holder_name' => 'required|string|max:255',
            'monthly_expenditure' => 'required|integer',
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:255',
            'company_pincode' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state_id' => 'required|integer',
            'country' => 'required|string|max:255',

        ]);

        if (isset($request->user_id)) {
            if ($validator->fails()) {
                return Redirect::to('onboarding/aftxjcqenf/' . $request->user_id)
                    ->with('status', 'error')
                    ->with('message', $validator->errors()->first());
            }
        } else {
            return Redirect::to('/onboarding/eqyroksfig')->with('status', 'error')->with('message', 'User ID Missing OR Something went wrong!!');
        }

        $user = UserAppxpay::with('businessDetail')->find($request->user_id);

        if ($user->businessDetail) {
            $businessDetail = $user->businessDetail->update($validator->validated());
        } else {
            $businessDetail = UsersBusinessDetail::create($validator->validated());
        }

        if ($businessDetail) {
            if ($user->process_level < 2 && $user->is_draft == "Y") {
                $user->update(['process_level' => 2]);
            }
            return Redirect::to('/onboarding/cokxgpauql/' . $request->user_id)->with('status', 'success')->with('message', 'Step Two Completed Successfully!!');
        } else {
            return Redirect::to('/onboarding/aftxjcqenf/' . $request->user_id)->with('status', 'error')->with('message', 'Something went wrong!!');
        }
    }

    public function webhookStore(Request $request)
    {
        $rules = [
            'user_id' => 'required|integer',
            'pay_in' => 'required|boolean',
            'pay_out' => 'required|boolean'
        ];
        if ($request->pay_in == 1) {
            $rules['percentage_fees'] = 'required|numeric|between:0,100.00';
            $rules['payin_receive_bank'] = 'required|integer';
        }
        if ($request->pay_out == 1) {
            $rules['payout_percentage_fees'] = 'required|numeric|between:0,100.00';
            $rules['payout_receive_bank'] = 'required|integer';
        }

        $validator = Validator::make($request->all(), $rules);

        if (isset($request->user_id)) {
            if ($validator->fails()) {
                return Redirect::to('onboarding/cokxgpauql/' . $request->user_id)
                    ->with('status', 'error')
                    ->with('message', $validator->errors()->first());
            }
        } else {
            return Redirect::to('/onboarding/eqyroksfig')
                ->with('status', 'error')
                ->with('message', 'User ID Missing OR Something went wrong!!');
        }

        $data = $validator->validated();

        $user = UserAppxpay::with('businessDetail.businessApps')->find($request->user_id);

        if (count($user->businessDetail->businessApps) > 0) {
            $errorMsg = '';
            $containsTruePayIn = collect($user->businessDetail->businessApps->toArray())->contains(function ($item) {
                return array_key_exists('is_active', $item) && $item['is_active'] === 1
                    && array_key_exists('type', $item) && $item['type'] == 1;
            });

            if ($containsTruePayIn == false && $request->pay_in == 1) {
                $errorMsg = 'Add active Pay-In web app atleast one record Required!!';
            }
            $containsTruePayOut = collect($user->businessDetail->businessApps->toArray())->contains(function ($item) {
                return array_key_exists('is_active', $item) && $item['is_active'] === 1
                    && array_key_exists('type', $item) && $item['type'] == 2;
            });
            if ($containsTruePayOut == false && $request->pay_out == 1) {
                $errorMsg = 'Add active Pay-Out web app atleast one record Required!!';
            }
            if ($containsTruePayIn == false && $containsTruePayOut == false && $request->pay_out == 1 && $request->pay_in == 1) {
                $errorMsg = 'Add active Pay-In & Pay-Out web app atleast one record Required!!';
            }
            if ($request->pay_out == 1 || $request->pay_in == 1) {
                if ($containsTruePayIn == false && $request->pay_in == 1) {
                    return Redirect::to('/onboarding/cokxgpauql/' . $request->user_id)->with('status', 'error')->with('message', $errorMsg);
                }
                if ($containsTruePayOut == false && $request->pay_out == 1) {
                    return Redirect::to('/onboarding/cokxgpauql/' . $request->user_id)->with('status', 'error')->with('message', $errorMsg);
                }
            }
        } else {
            if ($request->pay_out == 1 || $request->pay_in == 1) {
                return Redirect::to('/onboarding/cokxgpauql/' . $request->user_id)->with('status', 'error')->with('message', 'Add active Pay-In & Pay-Out web app atleast one record Required!!');
            }
        }

        $userData = UserAppxpay::with('businessDetail')->find($request->user_id);

        if ($userData) {

            if ($userData->businessDetail) {

                if ($user->process_level < 3 && $user->is_draft == "Y") {
                    $user->update(['process_level' => 3]);
                }

                $updateUser = [];

                $updateUser['refferedBy'] = $request->refferedBy == '' || $request->refferedBy == 0 ? 0 : $request->refferedBy;
                //$userData['assignedPartnerPayin'] = $request->assignedPartnerPayin == '' || $request->assignedPartnerPayin == 0 ? 0 : $request->assignedPartnerPayin;
                //$userData['assignedPartnerPayout'] = $request->assignedPartnerPayout == '' || $request->assignedPartnerPayout == 0 ? 0 : $request->assignedPartnerPayout;
                // dd($data);
                if ($userData != $request->refferedBy && $userData->update($updateUser) && $userData->businessDetail->update($data)) {
                    return Redirect::to('/onboarding/xyhsrpzfip/' . $request->user_id)->with('status', 'success')->with('message', 'Step Three Completed Successfully!!');
                } else {
                    return Redirect::to('/onboarding/cokxgpauql/' . $request->user_id)->with('status', 'error')->with('message', 'Something went wrong!!');
                }
            } else {
                return Redirect::to('/onboarding/cokxgpauql/' . $request->user_id)->with('status', 'error')->with('message', 'Business data Not found OR Something went wrong!!');
            }
        } else {
            return Redirect::to('/onboarding/cokxgpauql/' . $request->user_id)->with('status', 'error')->with('message', 'User ID Not found OR Something went wrong!!');
        }
    }

    public function businessAppsGet(Request $request, $id)
    {
        $app = BusinessApp::find($id);
        if ($app) {
            return response()->json(['status' => 'success', 'message' => 'Webapps Get data successfully!', 'data' => $app], 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'No data found!'], 400);
        }
    }

    public function businessAppsStore(Request $request, $id = null)
    {
        //echo "<pre>";print_r($request->all());exit;
        $rules = [
            'app_name' => 'required|string',
            'app_url' => 'required|url',
            'order_prefix' => 'required|string',
            'webhook_url' => 'required|url',
            'status' => 'required',
            'type' => 'required',
            'assignedPartnerPayin' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $sid = rand(10000, 99999);
        $ran = rand(10000000, 99999999);

        $user = UserAppxpay::with('businessDetail.businessApps')->find($request->user_id);
        if ($user->businessDetail->id) {

            $insertData = [
                'business_id' => $user->businessDetail->id,
                'app_name' => $request->app_name,
                'app_url' => $request->app_url,
                'order_prefix' => $request->order_prefix,
                'webhook_url' => $request->webhook_url,
                'ip_whitelist' => $request->ip_whitelist,
                'sid' => $sid,
                'is_active' => $request->status,
                'type' => $request->type,
                'aggregators_id' => $request->assignedPartnerPayin
            ];

            // dd(  $insertData );
            if (isset($request->webappsid)) {
                $app = BusinessApp::find($request->webappsid);
                if ($app->update($insertData)) {
                    return response()->json(['status' => 'success', 'message' => 'Webapps updated successfully!'], 200);
                } else {
                    return response()->json(['status' => 'failed', 'message' => 'Unable to update Webapps!'], 400);
                }
            }

            $insertData['terminal_id'] = $request->order_prefix . $ran;
            $insertData['merchant_app_key'] = bin2hex(random_bytes(32));
            $businessApp = BusinessApp::create($insertData);

            if ($businessApp) {
                return response()->json(['status' => 'success', 'message' => 'Webapps added successfully!'], 200);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'Unable to add Webapps!'], 400);
            }
        } else {
            return response()->json(['status' => 'Failed', 'message' => 'Business data not found OR Something went wrong!!'], 400);
        }
    }

    public function webAppsList(Request $request)
    {

        $user = UserAppxpay::with('businessDetail.businessApps')->find($request->user_id);
        return DataTables::of($user->businessDetail->businessApps)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<button type="button" data-id="' . $row->id . '" class="btn update-webapp btn-primary btn-sm">Update</button>';
            })
            ->addColumn('terminal_id', function ($row) {
                return $row->terminal_id;
            })
            ->addColumn('app_name', function ($row) {
                return $row->app_name ?? '';
            })
            ->addColumn('app_url', function ($row) {
                return $row->app_url ?? '';
            })
            ->addColumn('order_prefix', function ($row) {
                return $row->order_prefix ?? '';
            })
            ->addColumn('webhook_url', function ($row) {
                return $row->webhook_url ?? '';
            })
            ->addColumn('status', function ($row) {
                return $row->status ?? '';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function uploadFile(Request $request)
    {

        $rules = [
            'user_id' => 'required|integer',
            'docfile' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', // Example rules for a file upload
            'document_id' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $file_extension = $request->docfile->getClientOriginalExtension();
        $imageName = time() . '.' . $file_extension;
        $file = $request->docfile;
        $userFolder = 'appxpay_' . $request->user_id;
        $checkExit = AppxpayDocument::where(['user_id' => $request->user_id, 'document_id' => $request->document_id])->get();
        if ($checkExit) {
            $image_path = Storage::disk('s3')->delete("onboarding/" . $userFolder, $file, $imageName);
        }

        $documentData = AppxpayDocument::where([
            'user_id' => $request->user_id,
            'document_id' => $request->document_id
        ])->first();

        $update = false;

        if ($documentData) {
            $update = true;
            $userDel = 'appxpay_' . $request->user_id;
            $existsImage_path = 'onboarding/' . $userDel . '/' . $documentData->document_path;
            if (Storage::disk('s3')->exists($existsImage_path)) {
                Storage::disk('s3')->delete($existsImage_path);
            }
        }

        $image_path = Storage::disk('s3')->put("onboarding/" . $userFolder, $file, $imageName);

        if ($image_path) {

            if ($update == true) {
                if ($documentData->update(['document_path' => $image_path])) {
                    return response()->json(['status' => 'success', 'message' => 'Image path update successfully!'], 200);
                } else {
                    return response()->json(['status' => 'failed', 'message' => 'Unable to update Image path!'], 400);
                }
            }

            $docment = AppxpayDocument::create(['user_id' => $request->user_id, 'document_id' => $request->document_id, 'base_url' => 'https://appxpaydev.s3.ap-south-1.amazonaws.com', 'document_path' => $image_path]);
            if ($docment) {
                return response()->json(['status' => 'success', 'message' => 'Image Uploaded Successfully!'], 200);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'Unable to Image Uploaded!'], 400);
            }
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Unable to Image Uploaded!'], 400);
        }
    }

    public function deleteDocument(Request $request)
    {
        $rules = [
            'user_id' => 'required|integer',
            'document_id' => 'required|integer'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $document = AppxpayDocument::where([
            'user_id' => $request->user_id,
            'document_id' => $request->document_id
        ])->first();

        if ($document) {

            $userFolder = 'appxpay_' . $request->user_id;
            $image_path = 'onboarding/' . $userFolder . '/' . $document->document_path;

            if (Storage::disk('s3')->exists($image_path)) {
                Storage::disk('s3')->delete($image_path);
                $document->delete();

                return response()->json(['success' => true, 'message' => 'Document deleted successfully']);
            } else {
                return response()->json(['success' => false, 'message' => 'File does not exist on S3']);
            }
        }

        return response()->json(['success' => false, 'message' => 'Document not found']);
    }

    public function onboardingComplete(Request $request)
    {
        $rules = [
            'user_id' => 'required|integer'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $mandatoryDocumentTypeIds = DocumentType::where('mandatory_field', true)->pluck('id')->toArray();

        $documents = AppxpayDocument::where('user_id', $request->user_id)->get();

        $mandatoryDocuments = $documents->filter(function ($document) use ($mandatoryDocumentTypeIds) {
            return in_array($document->document_id, $mandatoryDocumentTypeIds);
        });

        if (count($mandatoryDocumentTypeIds) == count($mandatoryDocuments)) {

            $user = UserAppxpay::with('businessDetail')->find($request->user_id);
            $udata = $user->user_label_id . "-" . $user->user_name . "-" . $user->personal_email;
            $encdata = self::encrypt_decrypt('encrypt', $udata);

            if ($user->update(['is_draft' => 'N', 'process_level' => 4, 'api_ufw_pairs' => $encdata]) && $user->businessDetail->update(['api_ufw_pairs' => $encdata])) {
                return Redirect::to('/merchants/list/index')->with('status', 'success')->with('message', 'Merchant Onboarding Process is Completed!');
            } else {
                return Redirect::to('/onboarding/xyhsrpzfip/' . $request->user_id)->with('status', 'error')->with('message', 'Something went wrong!!');
            }
        } else {
            return Redirect::to('/onboarding/xyhsrpzfip/' . $request->user_id)->with('status', 'error')->with('message', 'Kindly Upload all the Mandatory Documents!');
        }
    }

    public function listofmerchants(Request $request)
    {

        $userAppxpay = UserAppxpay::where('user_role', 4)->with('businessDetail.businessApps', 'serviceWalletBalance')->orderBy('created_at', 'desc');

        if ($request->input('vendorId')) {
            $userAppxpay = $userAppxpay->where('refferedBy', $request->input('vendorId'));
        }

        $userAppxpay = $userAppxpay->get();

        return DataTables::of($userAppxpay)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $get = '<div class="align-items-center d-flex"><a href="/onboarding/eqyroksfig/' . $row->id . '"><button type="button" data-id="' . $row->id . '" class="width-btn btn update-webapp btn-primary bg-edit btn-sm mr-2">Edit</button></a>';

                if ($row->bg_verified == 'N' && $row->is_verified == 'N' && $row->is_draft == 'N') {
                    $get =  $get . '<button type="button" data-id="' . $row->id . '" class="width-btn btn update-webapp btn-primary btn-sm view-webapp bg-verify" data-toggle="modal" data-target="#viewWebAppModal" id="viewmerchantlist">Verify</button></div>';
                }

                if ($row->is_draft == 'Y') {
                    $getUrl = '';
                    if ($row->process_level == 1) {
                        $getUrl = '/onboarding/eqyroksfig/';
                    } else if ($row->process_level == 2) {
                        $getUrl = '/onboarding/aftxjcqenf/';
                    } else if ($row->process_level == 3) {
                        $getUrl = '/onboarding/cokxgpauql/';
                    } else if ($row->process_level == 4) {
                        $getUrl = '/onboarding/xyhsrpzfip/';
                    }
                    $get =  $get . '<a href="' . $getUrl . $row->id . '"><button type="button" class="width-btn btn btn-primary btn-sm bg-draft" id="draftmerchantlist">Draft</button></a></div>';
                }
                if ($row->is_draft == 'N' && $row->process_level == 4 && $row->bg_verified == 'Y' && $row->is_verified == 'Y') {
                    $get =  $get . '<button type="button" class="width-btn btn btn-primary btn-sm bg-completed" id="draftmerchantlist">Completed</button></div>';
                }

                return $get;
            })

            ->addColumn('name', function ($row) {
                $fn = $row->first_name != '' ? $row->first_name : '';
                $ln = $row->last_name != '' ? $row->last_name : '';
                return $fn . ' ' . $ln;
            })
            ->addColumn('email', function ($row) {
                return $row->personal_email;
            })
            ->addColumn('user_label_id', function ($row) {
                return $row->user_label_id;
            })
            ->addColumn('mobile', function ($row) {
                return $row->mobile_no;
            })
            ->addColumn('user_name', function ($row) {
                return $row->user_name;
            })->make(true);
    }
    public function getStatus(Request $request)
    {
        return response()->json(['success' => true, 'status' => true, 'message' => 'Status get successfully']);
    }

    public function getMerchantDetails(Request $request, $id)
    {

        $merchant = UserAppxpay::with(['businessDetail.monthlyExpenditure', 'userDocuments.documentType'])->findOrFail($id);
        $businesstypes = DB::table('business_type')->where('id', $merchant->businessDetail->business_type_id)->first();
        $businessCategory = DB::table('business_category')->where('id',  $merchant->businessDetail->business_category_id)->first();
        $businessSubcategory = DB::table('business_sub_category')->where('id', $merchant->businessDetail->business_sub_category_id)->first();
        $listofstate = DB::table('state')->where('id',  $merchant->businessDetail->state_id)->first();
        $data = [
            'merchant' => $merchant,
            'businesstypes' => $businesstypes,
            'businessCategory' => $businessCategory,
            'businessSubcategory' => $businessSubcategory,
            'listofstate' => $listofstate
        ];
        if ($data) {
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Merchant not found.'], 404);
        }
    }
    public function verifyDetails($id)
    {

        $merchant = UserAppxpay::find($id);

        if ($merchant) {

            $serWal = ServiceWallet::create([
                'user_id' => $id,
                'current_balance' => 0,
                'total_service_fund' => 0
            ]);
            if ($merchant->update(['is_verified' => 'Y', 'bg_verified' => 'Y']) && $serWal) {
                return response()->json(['status' => 'Success', 'message' => 'Merchant verified successfully'], 200);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'Something went wrong!!'], 200);
            }
        } else {

            return response()->json(['status' => 'failed', 'message' => 'Merchant not found'], 200);
        }
    }

    public static function encrypt_decrypt($action, $string)
    {

        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key     = 'oM5uPlLgq9r5b4bYqhk0jMz2C8xqudqD';
        $secret_iv      = 'J3uK5nVDjCY5m70q';
        $key = hash('sha256', $secret_key);

        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $secret_iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $secret_iv);
        }

        return $output;
    }

    public static function liveSettlements(Request $request)
    {
        $mlist = UserAppxpay::with('businessDetail')->where('user_role', 4)->get();
        $status = ['0' => 'Ongoing', '1' => 'Completed'];
        $status = ['' => 'Select a Status'] + $status;

        $businessNames = $mlist->mapWithKeys(function ($user) {
            return [$user->id => isset($user->businessDetail) ? $user->businessDetail->company_name : ''];
        })->prepend('Select a Company', '');

        return view('employee.pages.settlements', compact('businessNames', 'status'));
    }
    public static function partnerList(Request $request)
    {

        return view('employee.settings.partners');
    }

    public function addPartner(Request $request)
    {
        $validatedData = $request->validate([
            'partnerName' => 'required|string|max:255',
            'order_prefix' => 'required|string|max:50',
            'serviceFee' => 'required|numeric',
            'pay_in_input' => 'required',
            'pay_out_input' => 'required',
            'payout_service_fee' => 'required',
            'uat_end_point' => 'required|url',
            'prod_end_point' => 'required|url'
        ]);

        $get_exists_ornot = PaymentAggregator::where('order_prefix', '=', $request->order_prefix)->first();

        $order_prefix = isset($get_exists_ornot->order_prefix) ? $get_exists_ornot->order_prefix : "";
        if ($order_prefix == $request->order_prefix) {
            $paymentAggregator = PaymentAggregator::where('order_prefix', $request->order_prefix)->update([
                "name" => $request->partnerName,
                "order_prefix" => $request->order_prefix,
                "total_service_fee" => $request->serviceFee,
                "payin_status" => $request->pay_in_input,
                "payout_status" => $request->pay_out_input,
                "payout_service_fee" => $request->payout_service_fee,
                "uat_end_point" => $request->uat_end_point,
                "prod_end_point" => $request->prod_end_point
            ]);
            $response = [
                'success' => true,
                'message' => 'Payment Aggregator updated successfully.',
            ];
        } else {
            // Create a new PaymentAggregator instance
            $paymentAggregator = new PaymentAggregator();
            $paymentAggregator->name = $validatedData['partnerName'];
            $paymentAggregator->order_prefix = $validatedData['order_prefix'];
            $paymentAggregator->total_service_fee = $validatedData['serviceFee'];
            $paymentAggregator->payin_status = $validatedData['pay_in_input'];
            $paymentAggregator->payout_status = $validatedData['pay_out_input'];
            $paymentAggregator->payout_service_fee = $validatedData['payout_service_fee'];
            $paymentAggregator->active = true; // Assuming active status is default true
            $paymentAggregator->uat_end_point = $request->uat_end_point;
            $paymentAggregator->prod_end_point = $request->prod_end_point;
            $paymentAggregator->save();
            $response = [
                'success' => true,
                'message' => 'Payment Aggregator added successfully.',
            ];
        }
        return response()->json($response);
    }

    public function partnerUpdate(Request $request, $id)
    {
        if ($id) {
            $paymentAggregator = PaymentAggregator::where('id', $id)->get();
            if (count($paymentAggregator) > 0) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Aggrgator updated Successfully!!',
                    'data' => $paymentAggregator[0]
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Not found!!'
                ], 500);
            }
        } else {
            return response()->json(['errors' => 'ID Missing!!'], 500);
        }
    }
    public function UpdatePartner($id, Request $request)
    {
        $paymentAggregator = PaymentAggregator::where('id', $id)->update([
            "name" => $request->partnerName,
            "order_prefix" => $request->order_prefix,
            "total_service_fee" => $request->serviceFee,
            "payin_status" => $request->pay_in_input,
            "payout_status" => $request->pay_out_input,
            "payout_service_fee" => $request->payout_service_fee
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Aggregator updated Successfully!!',

        ], 200);
    }

    public function listPaymentAggregators(Request $request)
    {
        $data = PaymentAggregator::latest()->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('active', function ($row) {
                if ($row->active) {
                    return '<button class="btn btn-danger btn-sm change-status" data-id="' . $row->id . '" data-status="0">Inactive</button>';
                } else {
                    return '<button class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="1">Active</button>';
                }
            })->addColumn('action', function ($row) {
                return '<button class="btn btn-primary" onclick="handleButtonClick(' . $row->id . ')" data-id=' . $row->id . '>Edit</button>';
            })
            ->rawColumns(['active', 'action']) // If 'active' is an HTML column
            ->make(true);
    }

    public function changeStatus(Request $request)
    {
        $paymentAggregator = PaymentAggregator::find($request->id);

        if ($paymentAggregator) {
            $paymentAggregator->active = $request->status;
            $paymentAggregator->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Payment Aggregator not found.'
            ]);
        }
    }




    public function getMerchantsTransactions(Request $request)
    {
        $obj = new UserAppxpay();

        $merchants_list = $obj->getMerchantList();

        return view('employee.transaction.transaction', compact('merchants_list'));
    }


    public function txnExcelExport(Request $request)
    {
        // dd($request);
        $dates = explode('-', $request->datetimes);
        $start_date = $dates[0];
        $end_date = $dates[1];

        $fromdate = Carbon::createFromFormat('m/d/Y H:i:s', trim($start_date))->startOfDay()->format('Y-m-d H:i:s');
        $todate = Carbon::createFromFormat('m/d/Y H:i:s', trim($end_date))->endOfDay()->format('Y-m-d H:i:s');


        $query = DB::table('transactions')
            ->select(
                'transactions.order_id',
                'transactions.amount',
                'transactions.appxpay_txnid',
                'transactions.terminal_id',
                'transactions.status',
                'transactions.created_at',
                'transactions.updated_at',
                'order_overview.payload',
            )
            ->join('order_overview', 'transactions.aggregator_txnid', '=', 'order_overview.txnid')
            // ->where('transactions.mid', $id)
            ->whereBetween('transactions.created_at', [$fromdate, $todate])
            ->orderByDesc('transactions.created_at');

        if ($request->txn_status !== null && $request->txn_status !== ' ' && $request->txn_status !== 'default') {
            $query->where('status', $request->txn_status);
        }

        if ($request->terminal_id !== null && $request->terminal_id !== 'default' && $request->txn_status !== ' ') {
            $query->where('terminal_id', $request->terminal_id);
        }

        $payinTransactions = $query->get();

        $alter_structure =  $payinTransactions->transform(function ($item) {
            $decrypted_response = $this->decryptPayload($item->payload);
            $item->decrypted_payload = !is_null($decrypted_response) ? json_decode($this->decryptPayload($item->payload)) : [];
            $email     = $item->decrypted_payload->mail;
            $mobile_no = $item->decrypted_payload->mobile;
            list($username, $domain) = explode('@', $email);
            $sublength = strlen($username) <= 2 ? 0 : 2;
            $maskedUsername = str_repeat('x', strlen($username) - $sublength) . substr($username, -2);
            $maskedEmail = $maskedUsername . '@' . $domain;
            list($countryCode, $number) = explode('-', $mobile_no);
            $maskedNumber = str_repeat('x', strlen($number) - 4) . substr($number, 6, 10);
            $maskedMobile = $countryCode . '-' . $maskedNumber;
            $item->decrypted_payload->mail =  $maskedEmail;
            $item->decrypted_payload->mobile = $maskedMobile;
            unset($item->payload);
            return $item;
        });

        return Excel::download(new MerchantTransactionExport($alter_structure->toArray()), 'transactions.xlsx');
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



    public function getMyaccountDetails(Request $request)
    {

        $employee = auth()->guard('employee')->user();

        return view('employee.pages.myaccount', [
            'employee' => $employee

        ]);
    }
    public function getVendorDeatils(Request $request)
    {
        return view('employee.pages.vendor');
    }
}
