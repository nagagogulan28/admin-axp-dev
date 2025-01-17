<?php

namespace App\Http\Controllers\EmployeeAuth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Illuminate\Http\Request;
use App\Classes\ValidationMessage;
use App\Classes\GenerateLogs; 
use App\Http\Controllers\SmsController;
use App\EmpPasswordHistory;
use App\Employee;
use DateTime;
use Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\LoginActivity;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    private $login_attempts_count = 3;

    private $todaydate = "";

    private $password_expiry = "90";

    private $datetime="";
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('ban-mobile');
        //$this->middleware('whitelist-ips');
        $this->todaydate = date('Y-m-d');
        $this->datetime = date('Y-m-d H:i:s');
    }

    public function username()
    {
        return 'employee_username';
    }

    public function showLoginForm()
    {
        return view("employeeauth.login");
    }

    protected function guard()
    {
        return Auth::guard('employee');
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string'
            ]);
    }


    public function verifyLogin(Request $request)
    {   

        if($request->ajax()){
            
            $rules = [
                $this->username() => 'required|string',
                'password' => 'required|string'
            ];
    
            $messages = [
                "employee_username.required"=>"Username field is required",
                "password.required"=>"Password field is required"
            ];
    
            $validator = Validator::make($request->all(), $rules,$messages);
    
            
            if($validator->fails())
            {
                echo json_encode(["status"=>FALSE,"error"=>$validator->errors()]);
    
            }else{
    
                $employee_credentials = $request->except('_token');
                $employee = Employee::where('employee_username',$request['employee_username'])->first();
                if(isset($employee))
                {
                    $validCredentials = Hash::check($request['password'],$employee->getAuthPassword());
                    if ($validCredentials) {
    
                        if($employee->is_account_locked == 'Y')
                        {
                            $message = "Your account got lock temporary due to multiple login attempts <br>contact your department head";
                            echo json_encode(['status'=>FALSE,'message'=>$message]);
    
                        }else{
    
                            $datetime1 = new DateTime($employee->last_password_change);
                            $datetime2 = new DateTime($this->todaydate);
                            $interval = $datetime1->diff($datetime2);
                            $days = $interval->format('%a'); //now do whatever you like with $days
    
                            if($days > $this->password_expiry)
                            {
                                Employee::where('id',$employee->id)->update(['ft_login'=>'Y']);
    
                            }else{
    
                                Employee::where('id',$employee->id)->update(['failed_attempts'=>0]);
                            }
                        
                            Employee::where('id',$employee->id)->update(['twofa'=>'Y']);
                            session(["login_employee_id"=>$employee->id]);
                            session(["employee_credentials"=>$employee_credentials]);
                            session(["employee_details"=>$employee]);
                           
                          
                        //    // Gather login activity details
                        //    $loginActivity = new LoginActivity();
                        //    $loginActivity->log_ipaddress = $request->ip(); // Get the IP address
                        //    $loginActivity->log_device = $request->header('User-Agent'); // Get the User-Agent for device info
                        //    $loginActivity->log_os = php_uname('s') . ' ' . php_uname('r'); // Operating System
                        //    $loginActivity->log_browser = $request->header('User-Agent'); // Get the User-Agent for browser info
                        //    $loginActivity->log_time = now(); // Current timestamp
                        //    $loginActivity->log_employee = $employee->id; // Employee ID
                        //    // Save the login activity
                        //    $loginActivity->save();
                            echo json_encode(['status'=>TRUE]);
                            
                        }
    
                    }else{
    
                        $employee_name = $employee->first_name." ".$employee->last_name;
                        GenerateLogs::employee_login_failed($employee_name);
    
                        if($employee->failed_attempts < $this->login_attempts_count)
                        {
                            Employee::where('id',$employee->id)->update(['failed_attempts'=>$employee->failed_attempts+1]);
                            switch ($employee->failed_attempts) {
                                case '0':
                                    $message = "Only ".($employee->failed_attempts+1)."/3 Passwords Attempts left";
                                    break;
                                case '1':
                                    $message = "Only ".($employee->failed_attempts+1)."/3 Passwords Attempts left";
                                    break;
                                case '2':
                                    $message = "Account will get lock temporary, If you fail this time";
                                    break;
                            }
                            echo json_encode(['status'=>FALSE,'message'=>'You entered wrong credentials <br>'.$message]);
                        }else{
    
                            Employee::where('id',$employee->id)->update(['is_account_locked'=>'Y']);
                            $message = "Your account got locked temporarily,contact your department head";
                            echo json_encode(['status'=>FALSE,'message'=>$message]); 
                        
                        }
                       
                    }
                }else{
    
                    GenerateLogs::login_failed();
                    echo json_encode(['status'=>FALSE,'message'=>'You entered wrong credentials']);
                }
                
                
            }
        }
        
    
    }

    public function load_login_forms(Request $request){
        $employee = Employee::where('id',$request->session()->get("login_employee_id"))->first();
        
        $OTP = mt_rand(99999,1000000);

        // dd($employee);

        //$OTP=123456;
      
        if($employee->ft_login == 'Y')
        {  
            session(["firsttimepasswordOTP"=>$OTP]);

            $mobilemessage = "Hi ".$employee->first_name." ".$employee->last_name.", Use this OTP ".$OTP." for changing AppXpay account password. - AppXpay";
            
            $sms =  new SmsController($mobilemessage,$employee->mobile_no,"1307164793075795400");

            $sms->sendMessage();

            return View::make("employeeauth.employeemodal")->with(["first_time_login"=>$employee->ft_login,"firsttimepasswordOTP"=>$OTP])->render();

        }else if($employee->twofa == 'Y'){

            //sending email to an employee

            
            $full_name = $employee->first_name." ".$employee->last_name;

            $data = array(
                "from"=>env("MAIL_USERNAME", ""),
                "subject"=>"AppXpay Login Alert",
                "view"=>"maillayouts.loginalert",
                "htmldata"=>array(
                    "employee_name"=>$full_name,
                    "otp"=>$OTP
                )
            );
            session(['appxpay-email-verify'=>$OTP]);
            
            //Mail::to($employee->official_email)->send(new SendMail($data));

            $user = [
                'otp' => $OTP,
                'twofa' => $employee->twofa
            ];
            
            return response()->json([
                'status' => 'success',
                'message' => 'User OTP retrieved successfully',
                'data' => $user
            ], 200);
            
            // return response()->json($dataResp);
            
            // return View::make("employeeauth.employeemodal")->with(["second_factory_auth"=>$employee->twofa,"appxpay_email_verify"=> $OTP ])->render();
        
        }
        else if($employee->threefa == 'Y' && $employee->user_type == '1'){

            $full_name = $employee->first_name." ".$employee->last_name;

            $message = "Hi ".$full_name.", \nYour OTP for this session is ".$OTP."\nThank You AppXpay";

            $sms = new SmsController($message,$employee->mobile_no);

            $sms->sendMessage();
            

            session(['appxpay-mobile-verify'=>$OTP]);
            
            return View::make("employeeauth.employeemodal")->with(["third_factory_auth"=>$employee->threefa])->render();

        }
    }

    public function load_change_password_form(){

        return View::make("employeeauth.employeemodal")->with(["change_password_form"=>"Y"])->render(); 
    }

    public function appxpay_verify_email_otp(Request $request)
    {
        //echo "<pre>";print_r($request->email_otp.' '.session('appxpay-email-verify'));exit;
        if($request->email_otp == session('appxpay-email-verify'))
        {
            Employee::where('id',$request->session()->get("login_employee_id"))->update(['twofa'=>'N']);
            //echo "<pre>";print_r($request->session()->get("employee_details"));exit;
            
            if($request->session()->get("employee_details")["user_type"] == "0")
            {
                echo json_encode(["status"=>TRUE,"load_mobile_form"=>TRUE]);

            }else{
               
                 if (Auth::guard('employee')->attempt($request->session()->get("employee_credentials"))) {
                    
                    //echo "<pre>";print_r(Auth::guard('employee')->attempt($request->session()->get("employee_credentials")));die;
                    $employee_name = $request->session()->get("employee_details")["first_name"]." ".$request->session()->get("employee_details")["last_name"];
                    GenerateLogs::login_success($employee_name);

                    $loginEmployeeId = session('login_employee_id');
                    $employeeCredentials = session('employee_credentials');
                    $employeeDetails = session('employee_details');

                    $request->session()->forget('employee_credentials');
                    $request->session()->forget('employee_details');
                    $request->session()->forget('appxpay-email-verify');

                    
                    session(["login_employee_id"=>$loginEmployeeId]);
                    session(["employee_credentials"=>$employeeCredentials]);
                    session(["employee_details"=>$employeeDetails]);

                    return Redirect::to($this->redirectTo)->with([
                        'status' => true,
                        'load_mobile_form' => false
                    ]);
                        
                    // Employee::where('id',$loginEmployeeId)->update(['twofa'=>'Y','failed_attempts'=>0]);
                    // dd("ssss");
                    // echo json_encode(["status"=>TRUE,"load_mobile_form"=>FALSE,"redirect"=>$this->redirectTo]);
                 }
            }

        }else{
            return redirect()->back()->with(["loginstatus"=>FALSE,"message"=>ValidationMessage::$validation_messages['appxpay_wrong_otp']]);
        }
    }

    public function appxpay_verify_mobile_otp(Request $request)
    {
        if($request->mobile_otp == session('appxpay-mobile-verify'))
        {
            Employee::where('id',$request->session()->get("login_employee_id"))->update(['threefa'=>'Y']);
            $employee_name = $request->session()->get("employee_details")["first_name"]." ".$request->session()->get("employee_details")["last_name"];
            if (Auth::guard('employee')->attempt($request->session()->get("employee_credentials"))) {
                GenerateLogs::login_success($employee_name);
                $request->session()->forget('appxpay-email-verify');
                $request->session()->forget('appxpay-mobile-verify');
                $request->session()->forget('employee_credentials');
                $request->session()->forget('employee_details');
                
                echo json_encode(["status"=>TRUE,"redirect"=>$this->redirectTo]);
            }
  
        }else{

            echo json_encode(["status"=>FALSE,"message"=>ValidationMessage::$validation_messages['appxpay_wrong_otp']]);
        }
    }

    public function verify_empmobile_OTP(Request $request){

        if(session("firsttimepasswordOTP") == $request->firsttimepasswordOTP)
        {
            $request->session()->forget('firsttimepasswordOTP');
            echo json_encode(["status"=>TRUE]);
        }else{

            $message = ValidationMessage::$validation_messages["wrong_OTP"];
            echo json_encode(["status"=>FALSE,"message"=>$message]);
        }
    }

    public function change_ftemppassword(Request $request)
    {
        $data = $request->all();
        $rules = [
            "password"=>['required','string','min:8','confirmed','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'],
        ];
        $messages = ['password.regex'=>'Password should contain at-least 1 Uppercase,1 Lowercase,1 Numeric & 1 Special character)'];
        $validator = Validator::make($data, $rules, $messages);

        if($validator->fails())
        {
            echo json_encode(["status"=>FALSE,"errors"=>$validator->errors()]);

        }else{

            $employee = [];
            $employeeObject =  new Employee();
            $password_history = new EmpPasswordHistory();
            $password_exists =  false;

            $employee_existing_passwords = $password_history->get_password_history($request->session()->get("login_employee_id"));
 
            if(!empty($employee_existing_passwords))
            {
                
                foreach ($employee_existing_passwords as $key => $value) {
                    if(Hash::check($request->password,$value->password))
                    {
                        $password_exists = true;
                    }
                }

                if($password_exists){

                    $message = ValidationMessage::$validation_messages["password_same"];
                    echo json_encode(["status"=>FALSE,"message"=>$message]);

                }else{

                    $employee["password"] = bcrypt($request->password);
                    $employee["ft_login"] = "N";
                    $employee["last_password_change"] = $this->datetime;
                    $employee["failed_attempts"] = 0;

                    $update_status = $employeeObject->update_my_details($employee,$request->session()->get("login_employee_id"));
                    
                    $password_array = [
                        'employee_id'=>$request->session()->get("login_employee_id"),
                        'password'=>bcrypt($request->password),
                        'password_change_at'=>$this->datetime,
                    ];
                    $password_history->add_password_history($password_array);

                    if ($update_status) {
                        $request->session()->forget("login_employee_id");
                        $message = ValidationMessage::$validation_messages["ftpassword_update_success"];
                        echo json_encode(["status"=>TRUE,"message"=>$message]);

                    } else {

                        $message = ValidationMessage::$validation_messages["password_update_failed"];
                        echo json_encode(["status"=>FALSE,"message"=>$message]);
                    }

                }

            }else{

                $employee["password"] = bcrypt($request->password);
                $employee["ft_login"] = "N";
                $employee["last_password_change"] = $this->datetime;
                $employee["failed_attempts"] = 0;
                
                $update_status = $employeeObject->update_my_details($employee,$request->session()->get("login_employee_id"));
                
                $password_array = [
                    'employee_id'=>$request->session()->get("login_employee_id"),
                    'password'=>bcrypt($request->password),
                    'password_change_at'=>$this->datetime,
                ];
                $password_history->add_password_history($password_array);

                if ($update_status) {
                    $request->session()->forget("login_employee_id");
                    $message = ValidationMessage::$validation_messages["ftpassword_update_success"];
                    echo json_encode(["status"=>TRUE,"message"=>$message]);

                } else {

                    $message = ValidationMessage::$validation_messages["password_update_failed"];
                    echo json_encode(["status"=>FALSE,"message"=>$message]);
                }
            }
            
        }
    }

    public function sendagain_appxpay_empOTP(Request $request)
    {
        
        if($empObject->mgs_count < 4)
        {
            $employee = new Employee();
                        
            $mobileOTP = mt_rand(99999,999999);

            $number = session("mobile_no");

            $mobilemessage = "Hi ".$empObject->first_name." ".$empObject->last_name.", Use this OTP ".$mobileOTP." for changing AppXpay account password. -AppXpay";
            
            $sms =  new SmsController($mobilemessage,$number,"1307164793075795400");

            $sms->sendMessage();

            $details["mgs_count"] = $empObject->mgs_count+1;
            $update_status = $employee->update_my_details($details);

            if($update_status)
            {
                $message = ValidationMessage::$validation_messages["mobile_message_sent"];
                return redirect()->back()->with(["message"=>$message]);
            }
            
        }else{
            $message = ValidationMessage::$validation_messages["messages_max_limit"];
            return redirect()->back()->with(["message"=>$message,]);
        }
    }



    public function logout(Request $request)
    {

        $employee_name = Auth::guard("employee")->user()->first_name." ".Auth::guard("employee")->user()->last_name;
        GenerateLogs::logout($employee_name);
        $request->session()->forget("links");
        $this->guard('employee')->logout();
        $request->session()->invalidate();
        return redirect('/login');
    }

   
}
