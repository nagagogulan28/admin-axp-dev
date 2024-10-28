<?php

namespace App\Http\Controllers\AccessManage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

class rolesManageController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rolesManage(Request $request)
    {
        $id = 'appxpay-7WRwwggm';
        $navigation = new Navigation();
        if (!empty($id)) {
            $sublinks = $navigation->get_sub_links($id);
        }

        // $sublink_name = session('sublinkNames')[$id];

        $businesstype = DB::table('business_type')->get();
        $businessCategory = DB::table('business_category')->get();
        $businessSubcategory = DB::table('business_sub_category')->get();
        $monthlyExpenditure = DB::table('app_option')->where('module', 'merchant_business')->get();
        $merchant = DB::table('app_option')->where('module', 'merchant_business')->get();

        return view("manage_access.rolesmanage")->with([
            "sublinks" => $sublinks,
            "businesstype" => $businesstype, "businesscategory" => $businessCategory, "businesssubcategory" => $businessSubcategory,
            "monthlyExpenditure" => $monthlyExpenditure
        ]);

        return view("manage_access.rolesmanage")->with([
            "sublinks" => $sublinks, "sublink_name" => $sublink_name,
            "businesstype" => $businesstype, "businesscategory" => $businessCategory, "businesssubcategory" => $businessSubcategory,
            "monthlyExpenditure" => $monthlyExpenditure
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function updatePermission(Request $request)
     {
         // Validation rules
         $rules = [
             'rowId' => 'required|integer',
             'updateData' => 'required|array',
         ];
     
         // Perform validation
         $validator = Validator::make($request->all(), $rules);
     
         // Check if validation fails
         if ($validator->fails()) {
             return response()->json([
                 'status' => 'error',
                 'message' => 'Something went wrong!! Please Contact Admin!!'
             ], 422); // Use 422 Unprocessable Entity for validation errors
         }
     
         // Update the role
         $update = Rolespermissions::where('id', $request->rowId)->update($request->updateData);
     
         // Check if the role was successfully updated
         if ($update) {
             return response()->json([
                 'status' => 'success',
                 'message' => 'Permission updated successfully'
             ], 200);
         } else {
             return response()->json([
                 'status' => 'error',
                 'message' => 'Something went wrong!! Please Contact Admin!!'
             ], 500);
         }
     }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listofpermissions(Request $request)
    {
        $id = $request->input('referPermission_id');
        $listofpermissions = Rolespermissions::where('role_id', $id)->with('moduledata')->get();
        return DataTables::of($listofpermissions)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                return $row->moduledata->modules ?? '';
            })->make(true);
            // ->addColumn('status', function ($row) {
            //     return $row->moduledata->status;
            // })->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function manageModules()
    {
        $getRoles = Role::get();
        $mainMenuCollection = Permissions::where('modulesType',0)->get();

        return view("manage_access.modulesmanage", compact('getRoles','mainMenuCollection'));
    } 

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function permissionConfiguration()
    {
        $getRoles = Role::get();

        return view("manage_access.permissionmanage", compact('getRoles'));
    }    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function modulesList(Request $request)
    {
        $listofRoles = Permissions::with('userdata')->get();

        return DataTables::of($listofRoles)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<button type="button" data-id="' . $row->id . '" class="btn ip-delete btn-primary btn-sm">Delete</button>';
            })
            ->addColumn('name', function ($row) {
                return $row->name;
            })
            ->addColumn('status', function ($row) {
                return $row->status ?? '';
            })
            ->addColumn('created_by', function ($row) {
                return $row->created_by ?? '';
            })
            ->rawColumns(['checkbox', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function roleList(Request $request)
    {
        $listofRoles = Role::with('userdata')->get();

        return DataTables::of($listofRoles)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<button type="button" data-id="' . $row->id . '" class="btn ip-delete btn-primary btn-sm">Delete</button>';
            })
            ->addColumn('modules', function ($row) {
                return $row->modules;
            })
            ->addColumn('status', function ($row) {
                return $row->status ?? '';
            })
            ->addColumn('created_by', function ($row) {
                return $row->created_by ?? '';
            })
            ->rawColumns(['checkbox', 'action'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'role_name' => 'required',
            'status' => 'required',
        ]);

        if ($validate->fails()) {
            return Redirect::to('/roles/view')->with('status', 'error')->with('message', $validate->errors());
        }

        $check = Role::where("name", $request->role_name)->get();

        if (count($check) > 0) {
            return Redirect::to('/roles/view')->with('status', 'error')->with('message', 'Role Name Already Exist!!');
        }

        $insert = Role::create([
            "name" => $request->role_name,
            "status" => 1,
            "created_by" => auth()->guard("employee")->user()->id,
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now(),
        ]);

        if ($insert->id) {
            $getPermissions = Permissions::orderBy('id', 'asc')->pluck('id')->toArray();
            foreach ($getPermissions as $roles => $id) {
                $insertData[] = [
                    "role_id" => $insert->id,
                    "module_id" => $id,
                    "module_create" => 0,
                    "module_edit" => 0,
                    "module_delete" => 0,
                    "module_view" => 0,	
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ];
            }
            $rolesPermission = Rolespermissions::insert($insertData);
            if($rolesPermission){
                return Redirect::to('/roles/view')->with('status', 'success')->with('message', 'Role Added Successfully!!');
            }else{
                return Redirect::to('/roles/view')->with('status', 'error')->with('message', 'Something went wrong!! Please Contact Admin!!');
            }
        } else {
            return Redirect::to('/roles/view')->with('status', 'error')->with('message', 'Something went wrong!! Please Contact Admin!!');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function moduleStore(Request $request)
    {
        
        $validate = Validator::make($request->all(), [
            'module_name' => 'required',
            'menu_type' => 'required',
            'main_menu_order' => 'required',
            'menu_icon' => 'required',
            'status' => 'required'
        ]);
        
        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!! Please Contact Admin!!'
            ], 422); 
        }

        $check = Permissions::where("modules", $request->module_name)->get();
        
        if (count($check) > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Module Name Already Exist!!'
            ], 422);
        }
        
        $module_type = $request->menu_type == "mainMenu" ? 0 : 1;

        if ($module_type == 1 && isset($request->parentMenuId) && $request->parentMenuId != '') {
            $parentId = $request->parentMenuId;
        }else{
            $parentId = 0;
        }

        $parentId = $module_type == 0 ? 0 : $request->parentMenuId;

        $reqData = [
            "modules" => $request->module_name,
            "parentMenuId" => $parentId,
            "modulesType" => $module_type,
            "menuOrder" => $request->main_menu_order,
            "menuIcon" => $request->module_name,
            "status" => $request->status,
            "created_by" => auth()->guard("employee")->user()->id,
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now(),
        ];
        
        $insert = Permissions::create($reqData);

        if ($insert->id) {
            $getRoles = Role::orderBy('id', 'asc')->pluck('id')->toArray();
            foreach ($getRoles as $roles => $id) {
                $insertData[] = [
                    "role_id" => $id,
                    "module_id" => $insert->id,
                    "module_create" => 0,
                    "module_edit" => 0,
                    "module_delete" => 0,
                    "module_view" => 0,	
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ];
            }
            $rolesPermission = Rolespermissions::insert($insertData);
            if($rolesPermission){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Module Added Successfully!!'
                ], 200);
            }else{
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
