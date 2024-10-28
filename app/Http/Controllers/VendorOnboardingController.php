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
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

class VendorOnboardingController extends Controller
{
    public function index(Request $request, $id = null)
    {
        if ($id) {

            $user = UserAppxpay::where('user_role', 3)->find($id);

            if (!$user) {

                return view("onboarding.vendor.onboardingStepOne");
            }

            return view('onboarding.vendor.onboardingStepOne')->with(["user" => $user, "userUpdate" => true]);
        } else {

            return view("onboarding.vendor.onboardingStepOne");
        }
    }

    public function listFilterVendor(Request $request, $vendorid = null)
    {
        
        $clientView = view('onboarding.merchantsList');

        if ($vendorid) {

            $user = UserAppxpay::where('user_role', 3)->find($vendorid);

            if (!$user) {

                return redirect()->back()->withErrors(['message' => 'Vendor is Not Found!!']);
            }
            
            return $clientView->with(["vendorUser" => $user]);

        } else {

            return $clientView;
        }
    }

    public function listofvendor(Request $request)
    {
        $userAppxpay = UserAppxpay::where('user_role', 3)->with('businessDetail.businessApps')->orderBy('created_at', 'desc')->get();
        return DataTables::of($userAppxpay)
            ->addIndexColumn()
            ->addColumn('bank_details', function ($row) {
                if ($row->is_draft == 'N' && $row->process_level == 2 && $row->bg_verified == 'Y' && $row->is_verified == 'Y') {
                    return View::make('components.bank_details_image', ['id' => $row->id])->render();
                }else{
                    return "-";
                }
            })
            ->addColumn('action', function ($row) {

                $get = '<div class="align-items-center d-flex"><a href="/onboarding/jnylavzkrs/' . $row->id . '"><button type="button" data-id="' . $row->id . '" class="width-btn btn update-webapp btn-primary bg-edit btn-sm mr-2">Edit</button></a>';

                if ($row->bg_verified == 'N' && $row->is_verified == 'N' && $row->is_draft == 'N') {
                    $get =  $get . '<button type="button" data-id="' . $row->id . '" class="width-btn btn update-webapp btn-primary btn-sm view-webapp bg-verify" data-toggle="modal" data-target="#viewWebAppModal" id="viewmerchantlist">Verify</button></div>';
                }

                if ($row->is_draft == 'Y') {
                    $getUrl = '';
                    if ($row->process_level == 1) {
                        $getUrl = '/onboarding/jnylavzkrs/';
                    } else if ($row->process_level == 2) {
                        $getUrl = '/onboarding/mqkpdfhzkt/';
                    }

                    $get =  $get . '<a href="' . $getUrl . $row->id . '"><button type="button" class="width-btn btn btn-primary btn-sm bg-draft" id="draftmerchantlist">Draft</button></a></div>';
                }
                if ($row->is_draft == 'N' && $row->process_level == 2 && $row->bg_verified == 'Y' && $row->is_verified == 'Y') {
                    $get =  $get . '<a href="/merchants/list/index/'.$row->id.'" class="mr-2"><button type="button" class="width-btn btn btn-primary btn-sm bg-draft" id="draftmerchantlist">Merchants</button></a><button type="button" class="width-btn btn btn-primary btn-sm bg-completed" id="draftmerchantlist">Completed</button></div>';
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
            })
            ->rawColumns(['bank_details', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $userId = $request->user_id ?? null;

        $validateFields = [
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
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
            return Redirect::to('onboarding/jnylavzkrs')
                ->with('status', 'error')
                ->with('message', $validator->errors()->first());
        }

        if (isset($request->user_id) && $request->user_id) {
            $user = UserAppxpay::with('businessDetail')->where('user_role', 3)->find($request->user_id);
            if (!$user) {
                return Redirect::to('/onboarding/jnylavzkrs')->with('status', 'error')->with('message', 'User ID is not found!!');
            } else {
                $userUpdate = $user->update([
                    'first_name' => $request->firstname,
                    'last_name' => $request->lastname,
                    'user_name' => $request->userName,
                    'personal_email' => $request->email,
                    'mobile_no' => $request->mobile,
                    'updated_at' => Carbon::now(),
                ]);
                if ($userUpdate) {
                    return Redirect::to('/onboarding/mqkpdfhzkt/' . $request->user_id)->with('status', 'success')->with('message', 'Step One Updated Successfully!!');
                } else {
                    return Redirect::to('/onboarding/jnylavzkrs')->with('status', 'error')->with('message', 'Something went wrong!!');
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
            'user_role' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        if ($user) {
            return Redirect::to('/onboarding/mqkpdfhzkt/' . $user->id)->with('status', 'success')->with('message', 'Step One Completed Successfully!!');
        } else {
            return Redirect::to('/onboarding/jnylavzkrs')->with('status', 'error')->with('message', 'Something went wrong!!');
        }
    }

    public function steptwo(Request $request, $id)
    {
        $businesstype = DB::table('business_type')->get();
        $businessCategory = DB::table('business_category')->get();
        $businessSubcategory = DB::table('business_sub_category')->get();
        $listofstate = DB::table('state')->get();
        $monthlyExpenditure = DB::table('app_option')->where('module', 'merchant_business')->get();

        if ($id) {
            $user = UserAppxpay::with('businessDetail')->find($id);
            if (!$user) {
                return Redirect::to('/onboarding/jnylavzkrs')->with('status', 'error')->with('message', 'User ID is not found!!');
            }
            return view('onboarding.vendor.onboardingStepTwo')->with([
                "user" => $user,
                "state" => $listofstate,
                "businesstype" => $businesstype,
                "businesscategory" => $businessCategory,
                "businesssubcategory" => $businessSubcategory,
                "monthlyExpenditure" => $monthlyExpenditure
            ]);
        } else {
            return Redirect::to('/onboarding/jnylavzkrs')->with('status', 'error')->with('message', 'Something went wrong!!');
        }
    }

    public function onboardingComplete(Request $request)
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
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:255',
            'company_pincode' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state_id' => 'required|integer',
            'country' => 'required|string|max:255',
        ]);

        if (isset($request->user_id)) {
            if ($validator->fails()) {
                return Redirect::to('onboarding/mqkpdfhzkt/' . $request->user_id)
                    ->with('status', 'error')
                    ->with('message', $validator->errors()->first());
            }
        } else {
            return Redirect::to('/onboarding/jnylavzkrs')->with('status', 'error')->with('message', 'User ID Missing OR Something went wrong!!');
        }

        $mandatoryDocumentTypeIds = [10];

        $documents = AppxpayDocument::where('user_id', $request->user_id)->get();

        $mandatoryDocuments = $documents->filter(function ($document) use ($mandatoryDocumentTypeIds) {
            return in_array($document->document_id, $mandatoryDocumentTypeIds);
        });

        if (count($mandatoryDocumentTypeIds) == count($mandatoryDocuments)) {

            $user = UserAppxpay::with('businessDetail')->find($request->user_id);

            if ($user->businessDetail) {
                $businessDetail = $user->businessDetail->update($validator->validated());
            } else {
                $businessDetail = UsersBusinessDetail::create($validator->validated());
            }

            if ($businessDetail && $user->update(['is_draft' => 'N', 'process_level' => 2, 'is_verified' => 'Y', 'bg_verified' => 'Y'])) {
                return Redirect::to('/vendor/list/index')->with('status', 'success')->with('message', 'Vendor Process is Completed!');
            } else {
                return Redirect::to('/onboarding/jnylavzkrs/' . $request->user_id)->with('status', 'error')->with('message', 'Something went wrong!!');
            }
        } else {
            return Redirect::to('/onboarding/mqkpdfhzkt/' . $request->user_id)->with('status', 'error')->with('message', 'Kindly Upload Vendor Agreement Document!');
        }
    }
}
