<?php


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;



/*Route::get('/', function (){
    View::addExtension('html','php');
    return View::make('index');
});

Route::get('/sitemap.xml', function (){
    View::addExtension('xml','php');
    return response(file_get_contents(resource_path('views/sitemap.xml')), 200, [
        'Content-Type' => 'application/xml'
    ]);
});

Route::get('/googlea59b5d17aca07eef.html', function (){
    View::addExtension('html','php');
    return View::make('googlea59b5d17aca07eef');
});


$uri = Request::path();


$routes = [
    'about','contact','blog','event','payment-gateway','payment-pages','payment-link','subscription',
    'dev-doc','integration','rpay-mudra','rpay-punji','rpay-tej','rpay-wallet','rpay-epos','rpay-credit-card',
    'rpay-ivr','banking','education','ecommerce','route','invoice','index.html','rpay-doc','disclaimer','privacy'
    ,'term&condition','agreement','covid','saas','upi','customer-stories','partner','customer-stories','adjustment-guide','api'
];

if(in_array($uri,$routes))
{
    Route::get("/".$uri,function () {
        View::addExtension('html','php');
        return View::make('index');
    });
}


Route::get('/pricing', function () {
    View::addExtension('html','php');
    return View::make('index');
});
*/

Route::get('/reload-captcha', function () {
    return captcha_src('flat');
});


Route::get("/chart", function () {
    return View::make("am_chart.amchart1");
});

/* 
 * Merchant Funcionality Controller Routes 
*/
Auth::routes();

Route::get('/', 'MerchantController@index')->name('dashboard');

Route::get('/apitest', 'MerchantController@apitest')->name('apitest');

Route::get('/view_logs', 'VerifyController@viewlog');

Route::post('/mobile-register', 'Auth\RegisterController@mobile_register');

Route::get('/demo', 'VerifyController@demo_page');

Route::post('/demo/request', 'VerifyController@demo_request')->name("test-demo-request");

Route::post('/verify-login', 'Auth\LoginController@verifyLogin');

Route::get('/resend-mobile-otp', 'Auth\RegisterController@resend_mobileOTP');

Route::post('/verify-mobile', 'VerifyController@verify_mobile_number')->name("mverification");

Route::get('/contact', 'VerifyController@contact_us');

Route::get('/forgotpassword', 'VerifyController@forgotpassword');

Route::get('/loginform', 'VerifyController@loginform');

Route::get('/registerform', 'VerifyController@registerform');

Route::post('/contact-us-form', 'VerifyController@appxpay_contactus')->name('contact-us');

Route::post('/subscribe-us', 'VerifyController@appxpay_subscribe')->name('subscribe-us');

Route::get('/blog', 'VerifyController@blog');

Route::get('/blog/{postid}', 'VerifyController@blog_post')->name('blog-post');

Route::get('/event', 'VerifyController@event');

Route::get('/event/{postid}', 'VerifyController@event_post')->name('event-post');

Route::get('/csr/{postid}', 'VerifyController@csr_post')->name('csr-post');

Route::get('/press-release/{postid}', 'VerifyController@pr_post')->name('pr-post');

Route::get('/gallery', 'VerifyController@gallery')->name('gallery');

Route::get('/career', 'VerifyController@career')->name('career');

Route::get('/integration', 'VerifyController@integration')->name('integration');

Route::get('/career/job/description/{id}', 'VerifyController@get_job_description');

Route::post('/career/job/apply', 'VerifyController@store_applicant');

Route::get('/csr', 'VerifyController@csr')->name('csr');

Route::get('/press-release', 'VerifyController@press_release')->name('press-release');

Route::get('/appxpay-qrcode/{id}', 'VerifyController@generate_qrcode');

Route::get('/verify-account/{token}', 'VerifyController@verify_email_account')->name('verify-account');

Route::post('/appxpay-webhook', 'VerifyController@get_webhook_detail');

Route::get('/payout-response', 'VerifyController@payout_response');

Route::get("/session-timeout", 'VerifyController@session_timeout')->name("session-timeout");

Route::middleware(['auth'])->group(function () {

    // Route::get('/mobile-verification',function(){

    //     return view("/merchant/mobverify");
    // });

    Route::get('/business-details', function () {

        $states = App\State::all();
        return view("/merchant/business")->with("states", $states);
    });

    Route::post('/resend-message', 'VerifyController@resend_mobile_sms')->name("resend-message");



    Route::get("/account-locked", 'VerifyController@account_locked');

    Route::post("/update-lastlogin", "VerifyController@session_update")->name("session-update");
});

Route::get('/terms-conditions', 'SupportController@terms_condition');

Route::get('/privacy-policies', 'SupportController@privacy_policies');

Route::get('/service-agreement', 'SupportController@merchant_service');



//Merchant Register.
Route::group(['prefix' => 'merchant'], function () {

    Route::get('dashboard', 'MerchantController@index')->name('dashboard');

    Route::post('dashboard', 'MerchantController@index');

    Route::get('two-factor-auth', 'MerchantController@two_factor_auth');

    Route::post('two-factor-auth', 'MerchantController@UpdatetwoFactor')->name('merchant.two-factor-auth');

    Route::get('all-invoices/{perpage}', 'MerchantController@invoice');

    Route::post('/merchant/transactions/download-transaction-log', 'MerchantController@download_transaction')->name('download-transactionlog');

    Route::get('new-invoice', 'MerchantController@show_invoice')->name("show-invoice");

    Route::post('add-business', 'VerifyController@store_merchant_info')->name('business');

    Route::get('payments/{perpage}', 'MerchantController@payment');

    Route::get('payment/get/{id}', 'MerchantController@get_payment');

    Route::post('payments', 'MerchantController@payment');

    Route::get('transactions', 'MerchantController@merchant_transaction');

    Route::get('invoices', 'MerchantController@merchant_invoice');

    Route::get('paylinks', 'MerchantController@merchant_paylink');

    Route::get('quicklinks/{perpage}', 'MerchantController@get_quicklinks');

    Route::get('refunds/{perpage}', 'MerchantController@refund');

    Route::get('orders/{perpage}', 'MerchantController@order');

    Route::post('orders', 'MerchantController@order');

    Route::get('order/get/{id}', 'MerchantController@get_order');

    Route::post('refunds', 'MerchantController@refund');

    Route::get('disputes/{perpage}', 'MerchantController@dispute');

    Route::post('disputes', 'MerchantController@dispute');

    Route::get('settlements', 'MerchantController@settlement');

    Route::post('settlements', 'MerchantController@settlement');

    Route::get('get-all-items', 'MerchantController@get_all_items');

    Route::get('items/{perpage}', 'MerchantController@item');

    Route::post('items', 'MerchantController@item');

    Route::get('paylinks/get/{perpage}', 'MerchantController@paylink');

    Route::post('paylinks', 'MerchantController@paylink');

    Route::post('paylink/new', 'MerchantController@store_paylink');

    Route::post('quicklink/add', 'MerchantController@store_quicklink');

    Route::post('paylink/bulk/new', 'MerchantController@store_bulk_paylink');

    Route::post('item/new', 'MerchantController@store_item');

    Route::post('item/bulk/new', 'MerchantController@store_bulk_items');

    Route::get('item/edit/{id}', 'MerchantController@edit_item');

    Route::post('item/update', 'MerchantController@item_update');

    Route::post('item/remove', 'MerchantController@destroy_item');

    Route::get('paylink/edit/{id}', 'MerchantController@paylink_edit');

    Route::post('paylink/update', 'MerchantController@paylink_update');

    Route::post('appmode/change', 'MerchantController@change_appmode');

    Route::get('feed-back', 'MerchantController@merchant_feedback');

    Route::get('help-support', 'MerchantController@merchant_helpsupport');

    Route::get('personal-info', 'MerchantController@merchant_info');

    Route::post('personal-info/save', 'MerchantController@store_merchant_info');

    Route::post('business-info/update', 'MerchantController@update_business_info');

    Route::post('business-logo/update', 'MerchantController@update_business_logo');

    Route::post('business-logo/remove', 'MerchantController@remove_business_logo');

    Route::post('company-info/update', 'MerchantController@update_company_info');

    Route::post('business-details-info/save', 'MerchantController@store_business_details_info');

    Route::get('refer-earn', 'MerchantController@merchant_referearn');

    Route::get('checkmail', 'MerchantController@send_mail');

    Route::get('invoice/new', 'MerchantController@create_invoice')->name('new-invoice');

    Route::post('invoice/add', 'MerchantController@store_invoice');

    Route::get('invoice/edit/{id}', 'MerchantController@edit_invoice');

    Route::post('invoice/update', 'MerchantController@update_invoice');

    Route::get('customers/{perpage}', 'MerchantController@customers');

    Route::get('get-all-customers', 'MerchantController@get_all_customers');

    Route::get('get-customer-info/{id}', 'MerchantController@get_customer_details');

    Route::get('get-customer-address/{id}', 'MerchantController@get_customer_address');

    Route::post('customer-address/add', 'MerchantController@store_customer_address');

    Route::post('customer-address/update', 'MerchantController@update_customer_address');

    Route::post('customer-address/delete', 'MerchantController@delete_customer_address');

    Route::get('customer/state/get-gstcode/{id}', 'MerchantController@get_customer_gst_state_code');

    Route::post('get-sub-category', 'MerchantController@get_business_subcategory');

    Route::post('customer/add', 'MerchantController@store_customer');

    Route::get('customer/edit/{id}', 'MerchantController@edit_customer');

    Route::post('customer/update', 'MerchantController@update_customer');

    Route::post('customer/remove', 'MerchantController@delete_customer');

    Route::get('api/add', 'MerchantController@store_merchant_api');

    Route::get('settings', 'MerchantController@merchant_settings');
    Route::get('settings/getconfigsettings', 'MerchantController@MerchantConfigSettings');

    Route::get('resolution-center', 'MerchantController@resolution_center');

    Route::get('api/get-api', 'MerchantController@get_merchant_api');

    Route::get('api/details/{id}', 'MerchantController@get_api_details');

    Route::get('api/edit/{id}', 'MerchantController@update_merchant_api');

    Route::get('documents/upload/{id}', 'MerchantController@show_document_form');

    Route::post('document-submission', 'MerchantController@verify_documents');

    Route::get('document-submission/remove/{file}/{id}', 'MerchantController@remove_document');

    Route::get('document-submission/success', 'MerchantController@update_activate_docs');

    Route::get('load-activate-forms/{id}', 'MerchantController@activate_forms');

    Route::get('change-app-mode/{id}', 'MerchantController@change_appmode');

    Route::get('disable-popup', 'MerchantController@disable_popup');

    Route::post('reminder/enable', 'MerchantController@enable_reminders');

    Route::get('reminder/get', 'MerchantController@get_merchant_reminder');

    Route::post('reminder/add', 'MerchantController@store_merchant_reminder');

    Route::post('webhook/add', 'MerchantController@store_merchant_webhook');

    Route::get('webhook/get', 'MerchantController@get_webhook_details');

    Route::post('support/add', 'MerchantController@store_merchant_support');

    Route::get('support/get/{perpage}', 'MerchantController@get_merchant_support');

    Route::post('feedback/add', 'MerchantController@store_merchant_feedback');

    Route::get('feedback/get/{perpage}', 'MerchantController@get_merchant_feedback');

    Route::get('product/get/{perpage}', 'MerchantController@get_merchant_product');

    Route::post('product/add', 'MerchantController@store_merchant_product');

    Route::get('product/edit/{id}', 'MerchantController@edit_merchant_product');

    Route::post('product/update', 'MerchantController@update_merchant_product');

    Route::post('product/delete', 'MerchantController@delete_merchant_product');

    Route::get('customer-case/get/{perpage}', 'MerchantController@customer_case');

    Route::get('case-status/merchant/{id}', 'MerchantController@case_details');

    Route::post('comment/add', 'MerchantController@merchant_comment');

    Route::get('notifications', 'MerchantController@get_notifications');

    Route::get('messages', 'MerchantController@get_messages');

    Route::get('merchant-notifications/{perpage}', 'MerchantController@show_notification_table');

    Route::get('merchant-messages/{perpage}', 'MerchantController@show_message_table');

    Route::get('notification/update/{id}', 'MerchantController@update_notification');

    Route::get('tools', 'MerchantController@utilities');

    Route::get('coupon/new', 'MerchantController@coupon');

    Route::get('coupon/edit', 'MerchantController@coupon');

    Route::post('coupon/add', 'MerchantController@store_coupon');

    Route::get('coupons/get/{perpage}', 'MerchantController@get_all_coupon');

    Route::get('coupons/edit/{id}', 'MerchantController@edit_coupon');

    Route::get('new-coupon-id', 'MerchantController@new_coupon_id');

    Route::get('coupon/options', 'MerchantController@get_types_subtypes');

    Route::get('employee', 'MerchantController@employees')->name('merchant-employee');
    Route::get('employee-types', 'MerchantController@employeeTypes')->name('merchant-employee-types');

    Route::get('employee/get/{id}', 'MerchantController@get_employees');

    Route::get('employee/type/{id}', 'MerchantController@get_employee_permissions');

    Route::post('employee/permissions', 'MerchantController@store_type_permissions')->name('store-type-permissions');;

    Route::get('employee/new', 'MerchantController@create_employee')->name('create-employee');

    Route::post('employee/add', 'MerchantController@store_employee')->name('store-employee');

    Route::get('employee/edit/{id}', 'MerchantController@edit_employee')->name('edit-employee');

    Route::post('employee/update', 'MerchantController@update_employee');

    Route::post('employee/reset-password', 'MerchantController@reset_employee_password');

    Route::get('employee/unlock-account/{id}', 'MerchantController@unlock_employee');

    Route::post('employee/account-status', 'MerchantController@update_employee_status');

    Route::get('my-account', 'MerchantController@my_account');

    Route::get('my-account/{id}', 'MerchantController@my_account_tab');

    Route::get('view-all-notifications', 'NotiMessController@view_all_notifications');

    Route::get('merchant/view-all-messages', 'NotiMessController@view_all_messages');

    Route::post('change-password', 'MerchantController@change_password')->name('merchant-change-password');

    Route::post('update-mydetails', 'MerchantController@update_mydetails')->name('update-mydetails');

    Route::get('resend-change-email', 'MerchantController@resend_changeemail');

    Route::get('resend-change-mobile', 'MerchantController@resend_changemobile');

    Route::get('pagination/{submod}-{perpage}', 'MerchantController@merchant_pagination');

    Route::get('search/{submod}/{searchtext}', 'MerchantController@merchant_search');

    Route::get('gstinvoicereport', 'MerchantController@gstinvoicereport');
    Route::get('gstinvoiceId', 'MerchantController@gstinvoiceId');

    Route::post('gstinvoicereport', 'MerchantController@gstinvoicereport');
    Route::post('gstinvoicereportdetails', 'MerchantController@gstinvoicereportdetails');

    Route::get('/gstdetailExcel/{date}', 'MerchantController@gstdetailExcel')->name('gstdetailExcel');

    Route::get('transactionreport', 'MerchantController@transactionreport');

    Route::post('transactionreport', 'MerchantController@transactionReportData');

    Route::get('downloadreport', 'MerchantController@transactionReportDownload');
    Route::get('reportlog', 'MerchantController@transactionReportLog');

    Route::post('createdownloadreport', 'MerchantController@ReportDownloadlink');

    Route::get('paymentreport', 'MerchantController@paymentreport');

    Route::get('salesreport', 'MerchantController@salesreport');

    Route::get('datatable_export', 'MerchantController@datatableExport');

    Route::get('miscellaneous', 'MerchantController@miscellaneous');

    //payout
    Route::get('payout_overview', 'MerchantController@payoutdashboard');
    Route::post('payout_overview', 'MerchantController@payoutdashboard');

    //payoutcontacts
    Route::get('contacts', 'MerchantController@contacts');
    Route::post('add_single_contact', 'MerchantController@addsinglecontact');
    Route::post('add_bulk_contact', 'MerchantController@storebulkcontacts');
    Route::post('delete_contacts', 'MerchantController@deleteContacts');
    Route::get('edit_contacts', 'MerchantController@editContacts');
    Route::post('update_contacts', 'MerchantController@updateContacts');
    Route::get('check_contacts', 'MerchantController@checkContacts');


    Route::get('beneficiaries', 'MerchantController@beneficiaries');

    Route::get('newbeneficiaries', 'MerchantController@newbeneficiaries');
    Route::post('add_single_beneficiary', 'MerchantController@addsinglebeneficiary');
    Route::post('delete_beneficiaries', 'MerchantController@deleteBeneficiaries');
    Route::get('edit_beneficiaries', 'MerchantController@editBeneficiaries');
    Route::post('update_beneficiaries', 'MerchantController@updateBeneficiaries');
    Route::get('make_beneficiaries_group', 'MerchantController@makeBeneficiariesGroup');
    Route::post('post_make_beneficiaries_group', 'MerchantController@postmakeBeneficiariesGroup');

    Route::post('validate_upi', 'MerchantController@validateUpiId');
    Route::post('validate_account_number', 'MerchantController@validateAccountNumber');
    Route::post('add_bulk_beneficiaries', 'MerchantController@storebulkbeneficiaries');
    Route::post('edit_validate_account_number', 'MerchantController@editvalidateAccountNumber');
    Route::post('edit_validate_upi', 'MerchantController@editvalidateUpiId');

    //beneficiary group
    Route::get('beneficiary_groups', 'MerchantController@beneficiaryGroups');
    Route::get('beneficiary_groups/showmembers/{id}', 'MerchantController@showMembers');
    Route::get('beneficiary_groups/removemember/{id}', 'MerchantController@removeMember');

    Route::get('payout', 'MerchantController@payout');

    Route::post('payout', 'MerchantController@payoutTransferMoney');


    Route::get('payout_setting', 'MerchantController@payoutsetting');
    Route::get('generate_payout_apikeys', 'MerchantController@payoutApiKey');
    Route::post('save_ip_whitelist', 'MerchantController@payoutIpWhitelist');

    Route::get('get_api_keys', 'MerchantController@getapikeys');
    Route::get('delete_api_key/{id}', 'MerchantController@deleteApiKey');
    Route::get('delete_ip_address/{id}', 'MerchantController@deleteIpAdress');

    //payout account
    Route::get('payout_account', 'MerchantController@payoutaccount');

    //payout report
    Route::get('payout_reports', 'MerchantController@payoutreport');
    //endpayout


    //fund transfer
    Route::get('fund_transfer', 'MerchantController@fundTransfer');
    Route::get('add_fund', 'MerchantController@addFund');
    //endfund transfer
    Route::post('add_singlerefund', 'MerchantController@addsinglerefund');

    Route::get('transactionInfo', 'MerchantController@transactionInfo')->name('transactionInfo');
    Route::get('transactionRefundInit', 'MerchantController@transactionRefundInitiate')->name('transactionRefundInit');


    Route::get('payout_transaction_info', 'MerchantController@payoutTransactionInfo')->name('payoutTransactionInfo');

    //savepayoutmodule activation request
    Route::post('save_merchant_request', 'MerchantController@merchantRequest');
});





Route::get('/merchantemp/search/{submod}/{searchtext}', 'MerchantEmpController@merchant_search');


Route::get('/download/{file}', function ($file = '') {
    return response()->download(storage_path('app/public/download/' . $file));
});

Route::get('/download/integration/{file}', function ($file = '') {

    if (Storage::disk('integration')->exists($file)) {
        return response()->download(storage_path('app/public/integration/' . $file));
    } else {
        return redirect()->back();
    }
});


Route::get('/download/blog/{file}', function ($file = '') {
    return response()->download(storage_path('app/public/blog/images/' . $file));
});

Route::get('/download/merchant-document/{file}', function ($file = '') {

    $merchant_gid = Auth::user()->merchant_gid;

    return response()->download(storage_path('app/public/merchant/documents/' . $merchant_gid . "/" . $file));
});

Route::get('/download/merchant-logo/{file}', function ($file = '') {
    if (file_exists(public_path('/storage/merchantlogos/' . $file))) {
        return response()->download(public_path('/storage/merchantlogos/' . $file));
    }
});

/* 
 * Merchant Support controllers Routes
*/

Route::get('/support', 'SupportController@index')->name('support');

Route::get('/support/case-status/customer/{id}', 'SupportController@case_details');

Route::post('/support/new', 'SupportController@store_support')->name("new-support");

Route::post('/support/customer/comment/add', 'SupportController@customer_comment');

Route::get('/support/case/comment/get/{id}', 'SupportController@get_comment');

/* 
 * Merchant Payment & Invoice Dynamic Controller Routes
*/

Route::get('/t/p/s-p/{id}', 'DynamicLinkController@test_smartpay');

Route::get('/t/i/s-p/{id}', 'DynamicLinkController@test_invsmartpay');

Route::post('/smart-pay/test-request-payment', 'DynamicLinkController@test_request_payment')->name("test-request-payment");

Route::post('/test/pay/smart-pay/response', 'DynamicLinkController@test_smartpay_response')->name("test-paylink-response");

Route::post('/test/inv/smart-pay/response', 'DynamicLinkController@test_invsmartpay_response')->name("test-invoice-response");

Route::get('/p/s-p/{id}', 'DynamicLinkController@live_smartpay');

Route::get('/i/s-p/{id}', 'DynamicLinkController@live_invsmartpay');

Route::post('/pay/smart-pay/response', 'DynamicLinkController@live_smartpay_response')->name("live-paylink-response");

Route::post('/inv/smart-pay/response', 'DynamicLinkController@live_invsmartpay_response')->name("live-invoice-response");

Route::post('/smart-pay/live-request-payment', 'DynamicLinkController@live_request_payment')->name("live-request-payment");

Route::get('/test-screen/email', function () {
    return view('maillayouts.documentcorrection');
});

/*
 * Merchant Payment Pages   
 */

Route::get('/payment-pages/{id}/view', 'MerchantController@payment_page')->name('create-payment-page');
Route::post('/payment-pages/store/page-detail', 'MerchantController@store_pagedetail')->name('store-page-details');
Route::post('/payment-pages/store/page-input-detail', 'MerchantController@store_page_inputdetail')->name('store-page-inputdetail');
Route::get('/payment-pages/get-all-pages/{id}', 'MerchantController@get_all_page_details');
Route::get('/payment-pages/edit/{id}', 'MerchantController@edit_page_details')->name('edit-payment-page');
Route::post('/payment-pages/remove', 'MerchantController@change_page_status');

Route::get('/pp/v/{id}', 'DynamicLinkController@live_payment_pagelink')->name('payment-page');
Route::get('/t/pp/v/{id}', 'DynamicLinkController@test_payment_pagelink')->name('test-payment-page');
Route::post('/payment-page/request-payment', 'DynamicLinkController@do_payment')->name('request-payment');
Route::get('/easebuzz/hash', 'DynamicLinkController@encryptdata');
Route::post('/payment-page/response', 'DynamicLinkController@live_payment_page_response')->name('payment-pageresponse');
Route::post('/payment-page/test/response', 'DynamicLinkController@test_payment_page_response')->name('test-payment-pageresponse');



/* 
 * Merchant Employee Funcionality Controller Routes
 * 
 * 
*/
Route::group(['prefix' => 'merchant/employee'], function () {

    Route::get('logout', 'Auth\LoginMerchantEmployee@logout')->name("emplogout");
    Route::get('transactions', 'MerchantEmpController@index');
    Route::get('get-transactions/{id}', 'MerchantEmpController@get_transaction');
    Route::get('paylinks', 'MerchantEmpController@showpaylink');
    Route::get('payment/get/{id}', 'MerchantEmpController@get_payment');
    Route::get('get-paylinks/{id}', 'MerchantEmpController@get_paylink');
    Route::post('paylink/add', 'MerchantEmpController@store_paylink');
    Route::get('paylink/edit/{id}', 'MerchantEmpController@edit_paylink');
    Route::post('paylink/update', 'MerchantEmpController@update_paylink');
    Route::get('get-quick-paylinks/{id}', 'MerchantEmpController@get_quick_paylink');
    Route::post('quicklink/add', 'MerchantEmpController@store_quicklink');
    Route::get('login-activity-log', 'MerchantEmpController@show_merchatemp_logactivity');
    Route::get('merchant-emp-login-log/{id}', 'MerchantEmpController@merchatemp_logactivity');
    Route::get('pagination/{submod}-{perpage}', 'MerchantEmpController@merchant_pagination');
});
/* 
 * Employee Funcionality Controller Routes
 * 
*/

Route::group(['prefix' => 'appxpay'], function () {

    // Login Routes...
    Route::get('login', ['as' => 'appxpay.login', 'uses' => 'EmployeeAuth\LoginController@showLoginForm']);
    Route::post('login', ['uses' => 'EmployeeAuth\LoginController@login']);
    Route::post('logout', ['as' => 'appxpay.logout', 'uses' => 'EmployeeAuth\LoginController@logout']);

    // Registration Routes...
    Route::get('register', ['as' => 'appxpay.register', 'uses' => 'EmployeeAuth\RegisterController@showRegistrationForm']);
    Route::post('register', ['uses' => 'EmployeeAuth\RegisterController@register']);

    // Password Reset Routes...
    Route::get('password/reset', ['as' => 'appxpay.password.reset', 'uses' => 'EmployeeAuth\ForgotPasswordController@showLinkRequestForm']);
    Route::post('password/email', ['as' => 'appxpay.password.email', 'uses' => 'EmployeeAuth\ForgotPasswordController@sendResetLinkEmail']);
    Route::get('password/reset/{token}', ['as' => 'appxpay.password.reset.token', 'uses' => 'EmployeeAuth\ResetPasswordController@showResetForm']);
    Route::post('password/reset', ['uses' => 'EmployeeAuth\ResetPasswordController@reset']);

    Route::get("/session-timeout", 'VerifyController@emp_session_timeout');

    Route::post("/update-lastlogin", "VerifyController@emp_session_update")->name("emp-session-update");

    Route::get('admin-forget-password', ['uses' => 'EmployeeAuth\ForgotPasswordController@admin_forget_password']);
});

Route::get('/appxpay/generateid', function () {
    return "appxpay-" . Str::random(8);
});

Route::post('/appxpay/verify-credentials', 'EmployeeAuth\LoginController@verifyLogin');

Route::get('/appxpay/load-login-forms', 'EmployeeAuth\LoginController@load_login_forms');

Route::post('/appxpay/email-verify-otp', 'EmployeeAuth\LoginController@appxpay_verify_email_otp')->name('appxpay-email-verify');

Route::post('/appxpay/mobile-verify-otp', 'EmployeeAuth\LoginController@appxpay_verify_mobile_otp')->name('appxpay-mobile-verify');

Route::get('/appxpay/ft-password-change/send-otp-mobile', 'EmployeeAuth\LoginController@load_change_password_form')->name('send-otp-mobile');

Route::post('/appxpay/ft-password-change/verify-empmobile-otp', 'EmployeeAuth\LoginController@verify_empmobile_OTP')->name('verify-empmobile-otp');

Route::post('/appxpay/ft-password-change/ftpassword-change', 'EmployeeAuth\LoginController@change_ftemppassword')->name('ftpassword-change');

Route::get('/appxpay/dashboard', 'EmployeeController@index')->name('appxpay-dashboard');

Route::get('/appxpay/dashboardGraphData', 'EmployeeController@dashboardTransactionGraph')->name('dashboardTransactionGraph');

Route::get('/appxpay/dashboard_transactionstats', 'EmployeeController@dashboardTransactionStats')->name('dashboardTransactionStats');

//payout
Route::get('/appxpay/payout_dashboard', 'EmployeeController@payoutDashboard')->name('payoutDashboard');
Route::get('/appxpay/payout_dashboard_transactionstats', 'EmployeeController@payoutdashboardTransactionStats')->name('payoutdashboardTransactionStats');
Route::get('/appxpay/payout_dashboard_graph', 'EmployeeController@payoutDashboardGraph')->name('payoutDashboardGraph');
Route::get('/appxpay/price_setting', 'EmployeeController@priceSetting')->name('priceSetting');
Route::get('/appxpay/price_setting/{id}', 'EmployeeController@priceSettingofUser')->name('priceSettingofUser');
Route::post('/appxpay/save_price_setting', 'EmployeeController@savePriceSetting')->name('savePriceSetting');
Route::post('/appxpay/delete_price_setting', 'EmployeeController@deletePriceSetting')->name('deletePriceSetting');
Route::post('/appxpay/edit_price_setting', 'EmployeeController@editPriceSetting')->name('editPriceSetting');
Route::get('/appxpay/routing_config', 'EmployeeController@routingConfig')->name('routingConfig');
Route::post('/appxpay/save_routing_config', 'EmployeeController@saveRoutingConfig')->name('saveRoutingConfig');
Route::post('/appxpay/delete_routing_config', 'EmployeeController@deleteRoutingConfig')->name('deleteRoutingConfig');
Route::post('/appxpay/edit_routing_config', 'EmployeeController@editRoutingConfig')->name('editRoutingConfig');

Route::get('/appxpay/payout_transactions', 'EmployeeController@payoutTransacations')->name('payouttransactions');
Route::get('/appxpay/payout_get_transactions', 'EmployeeController@getPayouttransactions')->name('getPayouttransactions');
Route::get('/appxpay/payout_transaction_info', 'EmployeeController@payoutTransactionInfo')->name('payoutTransactionInfo');
Route::get('/appxpay/update_payout_transaction_status', 'EmployeeController@updatePayoutTransactionStatus')->name('updatePayoutTransactionStatus');
//endpayout

Route::get('/appxpay/email-otp', 'VerifyController@appxpay_email_otp')->name('appxpay-email');

Route::get('/appxpay/ft-password-change', 'VerifyController@firsttime_passwordchange')->name('appxpay-ft-password');

Route::get('/appxpay/mobile-otp', 'VerifyController@appxpay_mobile_otp')->name('appxpay-mobile');

Route::post('/appxpay/verify-employee-email', 'EmployeeAuth\ForgotPasswordController@verify_email');

Route::get('/appxpay/load-email-form', 'EmployeeAuth\ForgotPasswordController@load_email_form');

Route::post('/appxpay/employee-verify-email-otp', 'EmployeeAuth\ForgotPasswordController@verify_email_otp');

Route::get('/appxpay/load-mobile-form', 'EmployeeAuth\ForgotPasswordController@load_mobile_form');

Route::post('/appxpay/employee-verify-mobile-otp', 'EmployeeAuth\ForgotPasswordController@verify_mobile_otp');

Route::get('/appxpay/admin-reset-password', 'EmployeeAuth\ForgotPasswordController@load_reset_password_form');

Route::post('/appxpay/reset-admin-password', 'EmployeeAuth\ResetPasswordController@reset_admin_password');

Route::post('/appxpay/send-again-mobile-otp', 'VerifyController@sendagain_appxpay_empOTP')->name('send-again-mobile-otp');

/**
 * 
 * Accounting Module Routing Starts here
 */

Route::get('/appxpay/account/payable-management/{id}', 'EmployeeController@account')->name('account-payable');

Route::get('/appxpay/account/payable-management/supplier-invoice/create', 'EmployeeController@show_supplier_invoice')->name('new-supplier-invoice');

Route::get('/appxpay/account/receivable-management/{id}', 'EmployeeController@account')->name('account-receivable');

Route::get('/appxpay/account/fixed-assets-accounting/{id}', 'EmployeeController@account')->name('account-fixed-assets');

Route::get('/appxpay/account/global-taxation-solution/{id}', 'EmployeeController@account')->name('account-global-tax');

Route::get('/appxpay/account/global-taxation-solution/tax-settlement/create', 'EmployeeController@show_tax_settlement')->name('create-tax-settlement');

Route::get('/appxpay/account/global-taxation-solution/tax-settlement/get/{id}', 'EmployeeController@get_tax_settlement')->name('get-tax-settlement');

Route::post('/appxpay/account/global-taxation-solution/tax-settlement/new', 'EmployeeController@store_tax_settlement')->name('new-tax-settlement');

Route::get('/appxpay/account/global-taxation-solution/tax-adjustment/create', 'EmployeeController@show_tax_adjustment')->name('create-tax-adjustment');

Route::get('/appxpay/account/global-taxation-solution/tax-adjustment/get/{id}', 'EmployeeController@get_tax_adjustment')->name('get-tax-adjustment');

Route::post('/appxpay/account/global-taxation-solution/tax-adjustment/new', 'EmployeeController@store_tax_adjustment')->name('new-tax-adjustment');

Route::get('/appxpay/account/global-taxation-solution/tax-payment/create', 'EmployeeController@show_tax_payment')->name('create-tax-payment');

Route::get('/appxpay/account/global-taxation-solution/tax-payment/get/{id}', 'EmployeeController@get_tax_payment')->name('get-tax-payment');

Route::post('/appxpay/account/global-taxation-solution/tax-payment/new', 'EmployeeController@store_tax_payment')->name('new-tax-payment');

Route::get('/appxpay/account/account-charts/{id}', 'EmployeeController@account')->name('get-chart');

Route::get('/appxpay/account/book-keeping/{id}', 'EmployeeController@account')->name('get-book-keeping');


/**
 * Account Purchase Order CRUD Operations Routes
 */

Route::get('/appxpay/account/purchase-order/get-supplier/{id}', 'EmployeeController@get_selected_supplier_info')->name('get-supplier');
Route::get('/appxpay/account/payable-management/purchasae-order/get-all-purchase-order/{id}', 'EmployeeController@get_purchase_order')->name('get-all-purchase-order');
Route::get('/appxpay/account/payable-management/purchase-order/create', 'EmployeeController@show_purchase_order')->name('create-purchase-order');
Route::post('/appxpay/account/payable-management/purchase-order/new', 'EmployeeController@store_purchase_order')->name('new-purchase-order');
Route::post('/appxpay/account/payable-management/purchase-order/update', 'EmployeeController@update_purchase_order')->name('update-purchase-order');
Route::get('/appxpay/account/payable-management/purchase-order/edit/{id}', 'EmployeeController@edit_purchase_order')->name('edit-purchase-order');

/**
 * Account Supplier Order CRUD Operations Routes
 */
Route::get('/appxpay/account/payable-management/suporder-invoice/get-purchase-order-items/{id}', 'EmployeeController@get_purchase_order_items')->name('get-purchase-order-items');
Route::get('/appxpay/account/payable-management/suporder-invoice/get-all-suporder-invoice/{id}', 'EmployeeController@get_suporder_invoice')->name('get-all-suporder-invoice');
Route::get('/appxpay/account/payable-management/suporder-invoice/create', 'EmployeeController@show_suporder_invoice')->name('create-suporder-invoice');
Route::post('/appxpay/account/payable-management/suporder-invoice/new', 'EmployeeController@store_suporder_invoice')->name('new-suporder-invoice');
Route::post('/appxpay/account/payable-management/suporder-invoice/update', 'EmployeeController@update_suporder_invoice')->name('update-suporder-invoice');
Route::get('/appxpay/account/payable-management/suporder-invoice/edit/{id}', 'EmployeeController@edit_suporder_invoice')->name('edit-suporder-invoice');

/**
 * Account Expense Invoice Suppliers CRUD Operations Routes
 */
Route::get('/appxpay/account/payable-management/supexp-invoice/get-all-supexp-invoice/{id}', 'EmployeeController@get_supexp_invoice')->name('get-all-suppexp-invoice');
Route::get('/appxpay/account/payable-management/supexp-invoice/create', 'EmployeeController@show_supexp_invoice')->name('create-supexp-invoice');
Route::post('/appxpay/account/payable-management/supexp-invoice/new', 'EmployeeController@store_supexp_invoice')->name('new-supexp-invoice');
Route::post('/appxpay/account/payable-management/supexp-invoice/update', 'EmployeeController@update_supexp_invoice')->name('update-supexp-invoice');
Route::get('/appxpay/account/payable-management/supexp-invoice/edit/{id}', 'EmployeeController@edit_supexp_invoice')->name('edit-supexp-invoice');

/**
 * Account Suppliers Credit Debit Note CRUD Operations Routes
 */
Route::get('/appxpay/account/payable-management/debit-note/get-all-supcd-note/{id}', 'EmployeeController@get_supcd_note')->name('get-all-supcd-note');
Route::get('/appxpay/account/payable-management/debit-note/create', 'EmployeeController@show_debit_note')->name('new-debit-note');
Route::post('/appxpay/account/payable-management/debit-note/new', 'EmployeeController@store_supcd_note')->name('store-supcd-note');
Route::get('/appxpay/account/payable-management/debit-note/edit/{id}', 'EmployeeController@edit_supcd_note')->name('edit-supcd-note');
Route::post('/appxpay/account/payable-management/debit-note/update', 'EmployeeController@update_supcd_note')->name('update-supcd-note');

/**
 * Account Purchase Order CRUD Operations Routes
 */

Route::get('/appxpay/account/receivable-management/sales-order/get-customer/{id}', 'EmployeeController@get_selected_customer_info')->name('get-customer-info');
Route::get('/appxpay/account/receivable-management/sales-order/get-all-sales-order/{id}', 'EmployeeController@get_sales_order')->name('get-all-sales-order');
Route::get('/appxpay/account/receivable-management/sales-order/create', 'EmployeeController@show_sales_order')->name('create-sales-order');
Route::post('/appxpay/account/receivable-management/sales-order/new', 'EmployeeController@store_sales_order')->name('new-sales-order');
Route::post('/appxpay/account/receivable-management/sales-order/update', 'EmployeeController@update_sales_order')->name('update-sales-order');
Route::get('/appxpay/account/receivable-management/sales-order/edit/{id}', 'EmployeeController@edit_sales_order')->name('edit-sales-order');

/**
 * Account Customer Order Invoice CRUD Operations Routes
 */
Route::get('/appxpay/account/receivable-management/custorder-invoice/get-sales-order-items/{id}', 'EmployeeController@get_sales_order_items')->name('get-sales-order-items');
Route::get('/appxpay/account/receivable-management/custorder-invoice/get-all-custorder-invoice/{id}', 'EmployeeController@get_custorder_invoice')->name('get-all-custorder-invoice');
Route::get('/appxpay/account/receivable-management/custorder-invoice/create', 'EmployeeController@show_custorder_invoice')->name('create-custorder-invoice');
Route::post('/appxpay/account/receivable-management/custorder-invoice/new', 'EmployeeController@store_custorder_invoice')->name('new-custorder-invoice');
Route::post('/appxpay/account/receivable-management/custorder-invoice/update', 'EmployeeController@update_custorder_invoice')->name('update-custorder-invoice');
Route::get('/appxpay/account/receivable-management/custorder-invoice/edit/{id}', 'EmployeeController@edit_custorder_invoice')->name('edit-custorder-invoice');

/**
 * Account Customer Credit Debit Note CRUD Operations Routes
 */
Route::get('/appxpay/account/receivable-management/debit-note/get-all-custcd-note/{id}', 'EmployeeController@get_custcd_note')->name('get-all-custcd-note');
Route::get('/appxpay/account/receivable-management/debit-note/create', 'EmployeeController@show_custcd_note')->name('new-custcd-note');
Route::post('/appxpay/account/receivable-management/debit-note/new', 'EmployeeController@store_custcd_note')->name('store-custcd-note');
Route::get('/appxpay/account/receivable-management/debit-note/edit/{id}', 'EmployeeController@edit_custcd_note')->name('edit-custcd-note');
Route::post('/appxpay/account/receivable-management/debit-note/update', 'EmployeeController@update_custcd_note')->name('update-custcd-note');


/**
 * Account Invoice Items CRUD Operations Routes 
 */
Route::get('/appxpay/account/fixed-asset/get-all-assets/{id}', 'EmployeeController@get_all_assets')->name('get-all-assets');

Route::post('/appxpay/account/fixed-asset/new', 'EmployeeController@store_asset')->name('add-new-asset');

Route::get('/appxpay/account/fixed-asset/edit/{id}', 'EmployeeController@edit_asset')->name('edit-asset');

Route::post('/appxpay/account/fixed-asset/update', 'EmployeeController@update_asset')->name('update-asset');

Route::get('/appxpay/account/fixed-asset/get-all-capital-assets/{id}', 'EmployeeController@get_all_capital_assets')->name('get-all-capital-assets');

Route::get('/appxpay/account/fixed-asset/get-all-depreciate-assets/{id}', 'EmployeeController@get_all_depreciate_assets')->name('get-all-depreciate-assets');

Route::get('/appxpay/account/fixed-asset/get-all-sale-assets/{id}', 'EmployeeController@get_all_sale_assets')->name('get-all-sale-assets');

Route::post('/appxpay/account/fixed-asset/capital/update', 'EmployeeController@update_capital_asset')->name('update-capital-asset');

Route::post('/appxpay/account/fixed-asset/depreciate/update', 'EmployeeController@update_depreciate_asset')->name('update-depreciate-asset');

Route::post('/appxpay/account/fixed-asset/sale/update', 'EmployeeController@update_sale_asset')->name('update-sale-asset');

Route::get('/appxpay/account/vouchers/get-all-vouchers/{id}', 'EmployeeController@get_all_vouchers')->name('get-all-vouchers');

Route::post('/appxpay/account/voucher/new', 'EmployeeController@store_voucher')->name('add-new-voucher');

Route::get('/appxpay/account/voucher/edit/{id}', 'EmployeeController@edit_voucher')->name('edit-voucher');

Route::post('/appxpay/account/voucher/update', 'EmployeeController@update_voucher')->name('update-voucher');

Route::get('/appxpay/account/invoice/{id}', 'EmployeeController@account')->name('invoice');

/**
 * Account Invoice Invoices CRUD Operations Routes
 */
Route::get('/appxpay/account/invoice/invoices/get-all-invoices/{id}', 'EmployeeController@get_all_invoices')->name('get-all-invoices');

Route::get('/appxpay/account/invoice/invoices/get-all-items-options', 'EmployeeController@get_all_item_options')->name('get-all-item-options');

Route::get('/appxpay/account/invoice/invoice/get-customer-info/{id}', 'EmployeeController@get_customer_details')->name('get-customer-details');

Route::get('/appxpay/account/invoice/invoices/get-all-customer-options', 'EmployeeController@get_all_customer_options')->name('get-all-customer-options');

Route::post("/appxpay/account/invoice/invoice/customer-address/add", 'EmployeeController@store_customer_address')->name('add-new-customer-address');

Route::post("/appxpay/account/invoice/invoice/customer-address/update", 'EmployeeController@update_customer_address')->name('update-customer-address');

Route::get("/appxpay/account/invoice/invoices/show", 'EmployeeController@show_invoice')->name('show-new-invoice');

Route::post('/appxpay/account/invoice/invoices/new', 'EmployeeController@store_invoice')->name('add-new-invoice');

Route::get('/appxpay/account/invoice/invoices/edit/{id}', 'EmployeeController@edit_invoice')->name('edit-invoice');

Route::post('/appxpay/account/invoice/invoices/update', 'EmployeeController@update_invoice')->name('update-invoice');

Route::post('/appxpay/account/invoice/invoices/destroy', 'EmployeeController@destroy_invoice')->name('destroy-invoice');

/**
 * Account Invoice Items CRUD Operations Routes
 */
Route::get('/appxpay/account/invoice/items/get-all-items/{id}', 'EmployeeController@get_all_items')->name('get-all-items');

Route::post('/appxpay/account/invoice/item/new', 'EmployeeController@store_item')->name('add-new-item');

Route::get('/appxpay/account/invoice/item/edit/{id}', 'EmployeeController@edit_item')->name('edit-item');

Route::post('/appxpay/account/invoice/item/update', 'EmployeeController@update_item')->name('update-item');

Route::post('/appxpay/account/invoice/item/destroy', 'EmployeeController@destroy_item')->name('destroy-item');

Route::get('/appxpay/account/invoice/item/get-item-options', 'EmployeeController@get_item_options')->name('get-item-options');

/**
 * Account Invoice Customers CRUD Operations Routes
 */
Route::get('/appxpay/account/invoice/customers/get-all-customers/{id}', 'EmployeeController@get_all_customers')->name('get-all-customers');

Route::post('/appxpay/account/invoice/customer/new', 'EmployeeController@store_customer')->name('add-new-customer');

Route::get('/appxpay/account/invoice/customer/edit/{id}', 'EmployeeController@edit_customer')->name('edit-customer');

Route::post('/appxpay/account/invoice/customer/update', 'EmployeeController@update_customer')->name('update-customer');

Route::post('/appxpay/account/invoice/customer/destroy', 'EmployeeController@destroy_customer')->name('destroy-customer');

/**
 * Account Invoice Suppliers CRUD Operations Routes
 */
Route::get('/appxpay/account/invoice/suppliers/get-all-suppliers/{id}', 'EmployeeController@get_all_suppliers')->name('get-all-suppliers');

Route::post('/appxpay/account/invoice/supplier/new', 'EmployeeController@store_supplier')->name('add-new-supplier');

Route::get('/appxpay/account/invoice/supplier/edit/{id}', 'EmployeeController@edit_supplier')->name('edit-supplier');

Route::post('/appxpay/account/invoice/supplier/update', 'EmployeeController@update_supplier')->name('update-supplier');

Route::post('/appxpay/account/invoice/supplier/destroy', 'EmployeeController@destroy_supplier')->name('destroy-supplier');


Route::get('/appxpay/get-chart-options', 'EmployeeController@get_chart_options');

Route::get('/appxpay/edit-chart-record/{id}', 'EmployeeController@edit_chart_record');

Route::get('/appxpay/account/charts-account/get-chart/{id}', 'EmployeeController@get_allchart_details')->name('get-all-chart');

Route::post('/appxpay/account/charts-account/add', 'EmployeeController@store_accountchart')->name('store-chart');

Route::get('/appxpay/finance/payable-management/{id}', 'EmployeeController@finance')->name('finance-payable');
Route::get('/appxpay/finance/receivable-management/{id}', 'EmployeeController@finance')->name('finance-receivable');

/**
 * 
 * Finance Payable Management Supplier Pay Batch Entry routing starts here
 */
Route::get('/appxpay/finance/payable-management/supplier-paybatch/get/{id}', 'EmployeeController@get_supp_paybatch');
Route::get('/appxpay/finance/payable-management/supplier-paybatch/create', 'EmployeeController@show_supp_paybatch')->name('supp-paybatch-show');
Route::post('/appxpay/finance/payable-management/supplier-paybatch/add', 'EmployeeController@store_supp_paybatch')->name('supp-paybatch-add');
Route::get('/appxpay/finance/payable-management/supplier-paybatch/edit/{id}', 'EmployeeController@edit_supp_paybatch')->name('supp-paybatch-edit');
Route::post('/appxpay/finance/payable-management/supplier-paybatch/update', 'EmployeeController@update_supp_paybatch')->name('supp-paybatch-update');

/**
 * 
 * Finance Sundry Payment Entry routing starts here
 */
Route::get('/appxpay/finance/payable-management/sundry-payment/get/invoice-no/{id}', 'EmployeeController@get_invoice_no');
Route::get('/appxpay/finance/payable-management/sundry-payment/get/{id}', 'EmployeeController@get_sundry_payment');
Route::get('/appxpay/finance/payable-management/sundry-payment/create', 'EmployeeController@show_sundry_payment')->name('sundry-payment-show');
Route::post('/appxpay/finance/payable-management/sundry-payment/add', 'EmployeeController@store_sundry_payment')->name('sundry-payment-add');
Route::get('/appxpay/finance/payable-management/sundry-payment/edit/{id}', 'EmployeeController@edit_sundry_payment')->name('sundry-payment-edit');
Route::post('/appxpay/finance/payable-management/sundry-payment/update', 'EmployeeController@update_sundry_payment')->name('sundry-payment-update');
/**
 * 
 * Finance Payable management Bank starts here
 */
Route::get('/appxpay/finance/payable-management/bank/get/{id}', 'EmployeeController@get_banks_info');
Route::post('/appxpay/finance/payable-management/bank/add', 'EmployeeController@store_bank_info')->name('bank-add');
Route::get('/appxpay/finance/payable-management/bank/edit/{id}', 'EmployeeController@edit_bank_info')->name('bank-edit');
Route::post('/appxpay/finance/payable-management/bank/update', 'EmployeeController@update_bank_info')->name('bank-update');

/**
 * 
 * Finance Contra Entry routing starts here
 */
Route::get('/appxpay/finance/payable-management/contra-entry/get/{id}', 'EmployeeController@get_contra_entry');
Route::get('/appxpay/finance/payable-management/contra-entry/create', 'EmployeeController@show_contra_entry')->name('contra-entry-show');
Route::post('/appxpay/finance/payable-management/contra-entry/add', 'EmployeeController@store_contra_entry')->name('contra-entry-add');
Route::get('/appxpay/finance/payable-management/contra-entry/edit/{id}', 'EmployeeController@edit_contra_entry')->name('contra-entry-edit');
Route::post('/appxpay/finance/payable-management/contra-entry/update', 'EmployeeController@update_contra_entry')->name('contra-entry-update');

/**
 * 
 * Finance Receivable management Customer Direct Entry starts here
 */
Route::get('/appxpay/finance/receivable-management/cust-dreceipt-entry/get/invoice-no/{id}', 'EmployeeController@get_saleinvoice_no');
Route::get('/appxpay/finance/receivable-management/cust-dreceipt-entry/get/{id}', 'EmployeeController@get_cust_dreceipt_entry');
Route::get('/appxpay/finance/receivable-management/cust-dreceipt-entry/create', 'EmployeeController@show_cust_dreceipt_entry')->name('cust-dreceipt-entry-show');
Route::post('/appxpay/finance/receivable-management/cust-dreceipt-entry/add', 'EmployeeController@store_cust_dreceipt_entry')->name('cust-dreceipt-entry-add');
Route::get('/appxpay/finance/receivable-management/cust-dreceipt-entry/edit/{id}', 'EmployeeController@edit_cust_dreceipt_entry')->name('cust-dreceipt-entry-edit');
Route::post('/appxpay/finance/receivable-management/cust-dreceipt-entry/update', 'EmployeeController@update_cust_dreceipt_entry')->name('cust-dreceipt-entry-update');
/**
 * 
 * Finance Receivable management Sundry receipt Entry starts here
 */
Route::get('/finance/receivable-management/sundry-receipt/get/{id}', 'EmployeeController@get_sundry_receipt');
Route::get('/appxpay/finance/receivable-management/sundry-receipt/create', 'EmployeeController@show_sundry_receipt')->name('sundry-receipt-show');
Route::post('/appxpay/finance/receivable-management/sundry-receipt/add', 'EmployeeController@store_sundry_receipt')->name('sundry-receipt-add');
Route::get('/appxpay/finance/receivable-management/sundry-receipt/edit/{id}', 'EmployeeController@edit_sundry_receipt')->name('sundry-receipt-edit');
Route::post('/appxpay/finance/receivable-management/sundry-receipt/update', 'EmployeeController@update_sundry_receipt')->name('sundry-receipt-update');

/**
 * 
 * Settlement Transaction Module routing starts here 
 */

Route::post("/appxpay/settlement/get-all-transactions", 'EmployeeController@get_transactions_bydate');

Route::get('/appxpay/settlement/transactions/{id}', 'EmployeeController@adjustment')->name('settlement-transactions');

Route::get('/appxpay/settlement/add/new', 'EmployeeController@store_adjustment_view')->name('add-new-settlement');

Route::post('/appxpay/settlement/generate', 'EmployeeController@generate_adjustment')->name('generate-adjustment');

Route::post('/appxpay/settlement/add', 'EmployeeController@store_adjustment')->name('add-settlement');

Route::get('/appxpay/settlement/get', 'EmployeeController@get_adjustment_detail');

Route::post('/appxpay/settlement/proceed-adjustment', 'EmployeeController@proceed_adjustment');

Route::get('/appxpay/settlement/get-merchants-transactions/{id}', 'EmployeeController@get_merchant_transactions');

Route::post('/appxpay/settlement/transactions-details', 'EmployeeController@get_transactions_details');

Route::post('/appxpay/settlement/get-vendor-adjustments', 'EmployeeController@get_vendor_adjustments');

Route::post('/appxpay/settlement/appxpay-adjustment', 'EmployeeController@appxpay_adjustment');

Route::post('/appxpay/settlement/get-appxpay-adjustments', 'EmployeeController@get_appxpay_adjustments');

Route::post('/appxpay/settlement/download-transaction-data', 'EmployeeController@download_transaction')->name('download-transactiondata');

/**
 * Settlement ChargeBack Dispute Resolution starts here
 */

Route::get('/appxpay/settlement/cdr/{id}', 'EmployeeController@adjustment')->name('cdr-home');
Route::get('/settlement/chargeback-dispute-refund/get/{id}', 'EmployeeController@get_cdr_info');
Route::get('/appxpay/settlement/chargeback-dispute-refund/create', 'EmployeeController@show_cdr_info')->name('cdr-show');
Route::post('/appxpay/settlement/chargeback-dispute-refund/add', 'EmployeeController@store_cdr_info')->name('cdr-add');
Route::get('/appxpay/settlement/chargeback-dispute-refund/edit/{id}', 'EmployeeController@edit_cdr_info')->name('cdr-edit');
Route::post('/appxpay/settlement/chargeback-dispute-refund/update', 'EmployeeController@update_cdr_info')->name('cdr-update');

Route::get('/appxpay/settlement/reports/{id}', 'EmployeeController@adjustment');

Route::get('/appxpay/settlement/settings/{id}', 'EmployeeController@adjustment');

Route::get('/appxpay/technical/l2-tickets/{id}', 'EmployeeController@technical')->name('technical-payable');

Route::get('/appxpay/technical/transactions', 'EmployeeController@transactions')->name('transactions');
Route::get('/technical/searchtransactions', 'EmployeeController@gettransactions')->name('gettransactions');

Route::get('/appxpay/technical/findvendortransactionstatus', 'EmployeeController@findvendortransactionstatus')->name('findvendortransactionstatus');
Route::get('/appxpay/technical/updatetransactionstatus', 'EmployeeController@updateTransactionStatus')->name('updateTransactionStatus');

Route::get('/appxpay/technical/transactionInfo', 'EmployeeController@transactionInfo')->name('transactionInfo');

Route::get('/appxpay/technical/merchant_services', 'EmployeeController@merchantServices')->name('merchantTransactionPermission');
Route::post('/appxpay/technical/add_merchant_services', 'EmployeeController@addMerchantServices')->name('addMerchantServices');
Route::post('/appxpay/technical/edit_merchant_services', 'EmployeeController@editMerchantServices')->name('editMerchantServices');

Route::get('/appxpay/technical/merchant_request_listing', 'EmployeeController@merchantRequestListings')->name('merchantRequestListings');
Route::post('/appxpay/technical/merchant_request_status_update', 'EmployeeController@merchantRequestStatusUpdate')->name('merchantRequestStatusUpdate');

Route::post('/appxpay/technical/save_vendor_keys', 'EmployeeController@saveVendorkeys')->name('saveVendorkeys');

Route::post('/appxpay/technical/delete_vendor_keys', 'EmployeeController@deleteVendorKeys')->name('deleteVendorKeys');

Route::get('/appxpay/technical/merchantList', 'EmployeeController@merchantListWhenSavingVendor')->name('merchantListWhenSavingVendor');

Route::get('/appxpay/technical/work-status/{id}', 'EmployeeController@technical')->name('technical-payable');

Route::get('/appxpay/networking/network-status/{id}', 'EmployeeController@network')->name('networking-payable');

/**
 * Technical Menu Routes starts here
 */
Route::get('/technical/get-merchant-charges/{perpage}', 'EmployeeController@get_merchant_charges');
Route::get('/technical/get-apporved-merchants/{perpage}', 'EmployeeController@get_approved_merchants');
Route::get('/appxpay/technical/make-merchant-live/{id}', 'EmployeeController@make_approved_merchant_live');
Route::get('/appxpay/technical/change-merchant-status/{id}/{status}', 'EmployeeController@change_approved_merchant_status');
Route::get('/appxpay/technical/get-merchant-charge/{recordid}', 'EmployeeController@get_merchant_charge');
Route::get('/appxpay/technical/get-merchant-business-type/{merchantid}', 'EmployeeController@get_merchant_bussinesstype');


Route::post('/appxpay/technical/merchant-charge/add', 'EmployeeController@addupdate_merchant_charge');
Route::post('/appxpay/technical/change-merchant-password', 'EmployeeController@changeMerchantPassword');

Route::get('/appxpay/technical/get-adjustment-charges/{perpage}', 'EmployeeController@get_adjustment_charges');
Route::post('/appxpay/technical/adjustment-charge/add-update', 'EmployeeController@addupdate_adjustment_charge');
Route::get('/appxpay/technical/get-adjustment-charge/{perpage}', 'EmployeeController@get_adjustment_charge');

Route::get('/appxpay/technical/get-merchant-routes/{id}', 'EmployeeController@get_merchant_routes');
Route::post('/appxpay/technical/add-merchant-route', 'EmployeeController@store_merchant_route');
Route::get('/appxpay/technical/get-merchant-route/{id}', 'EmployeeController@get_merchant_route');

Route::get('/appxpay/technical/appxpay-getroutes/{perpage}', 'EmployeeController@get_appxpay_route');
Route::post('/appxpay/technical/appxpay-add-route', 'EmployeeController@add_appxpay_route');
Route::get('/appxpay/technical/appxpay-edit-route/{id}', 'EmployeeController@edit_appxpay_route')->name('appxpay-route');
Route::post('/appxpay/technical/appxpay-update-route', 'EmployeeController@update_appxpay_route');

/**
 * 
 * Support Menu routing starts here
 */

Route::get('/appxpay/support/client-desk/{id}', 'EmployeeController@support')->name('support-payable');
Route::post('/appxpay/support/call-list/merchant-support/add', 'EmployeeController@store_merchant_support')->name('add-merchant-support');
Route::get('/appxpay/support/merchant-status/{id}', 'EmployeeController@support')->name('support-payable');
Route::get('/appxpay/support/call-list/{id}', 'EmployeeController@support')->name('support-payable');
Route::get('/appxpay/support/merchant/support-list', 'EmployeeController@get_merchant_support')->name('merchant-support');
Route::get('/appxpay/support/merchant/status', 'EmployeeController@get_merchant_status')->name('merchant-status');
Route::post('/appxpay/support/call-list/support/new', 'EmployeeController@store_callsupport')->name('call-support');
Route::get('/appxpay/support/call-list/support/get', 'EmployeeController@get_callsupport')->name('get-callsupport');
Route::get('/appxpay/support/merchant/locked-accounts/get', 'EmployeeController@get_merchant_locked_accounts');
Route::get('/appxpay/support/merchant/unlock-account/{merchantid}', 'EmployeeController@merchant_unlock');

/**
 * 
 * Marketing Menu routing starts here
 */

Route::get('/appxpay/marketing/offline-marketing/{id}', 'EmployeeController@marketing')->name('marketing-online');
Route::get('/appxpay/marketing/online-marketing/{id}', 'EmployeeController@marketing')->name('marketing-offline');

Route::post('/appxpay/marketing/add-post', 'EmployeeController@store_post');
Route::get('/appxpay/marketing/get-all-posts', 'EmployeeController@get_all_post');
Route::get('/appxpay/marketing/edit-post/{id}', 'EmployeeController@edit_post');
Route::post('/appxpay/marketing/update-post', 'EmployeeController@update_post');
Route::post('/appxpay/marketing/remove-post', 'EmployeeController@remove_post');
Route::get('/appxpay/marketing/remove-post-image/{imagename}', 'EmployeeController@remove_post_image');

Route::get('/appxpay/merketing/contact/get/{id}', 'EmployeeController@get_contact_lead');

Route::get('/appxpay/merketing/subscribe/get/{id}', 'EmployeeController@get_subscribe_list');
Route::get('/appxpay/merketing/gallery/get/{id}', 'EmployeeController@get_gallery_image');
Route::post('/appxpay/merketing/gallery/add', 'EmployeeController@store_image');
Route::get('/appxpay/merketing/gallery/edit/{id}', 'EmployeeController@edit_image');
Route::get('/appxpay/marketing/remove-gallery-image/{imagename}', 'EmployeeController@remove_gallery_image');
Route::post('/appxpay/marketing/gallery/update', 'EmployeeController@update_image');

Route::post('/appxpay/marketing/event/add-post', 'EmployeeController@store_event_post');
Route::get('/appxpay/marketing/event/get-all-posts', 'EmployeeController@get_all_event_post');
Route::get('/appxpay/marketing/event/edit-post/{id}', 'EmployeeController@edit_event_post');
Route::post('/appxpay/marketing/event/update-post', 'EmployeeController@update_event_post');
Route::post('/appxpay/marketing/event/remove-post', 'EmployeeController@remove_event_post');
Route::get('/appxpay/marketing/event/remove-post-image/{imagename}', 'EmployeeController@remove_event_post_image');

Route::post('/appxpay/marketing/csr/add-post', 'EmployeeController@store_csr_post');
Route::get('/appxpay/marketing/csr/get-all-posts', 'EmployeeController@get_all_csr_post');
Route::get('/appxpay/marketing/csr/edit-post/{id}', 'EmployeeController@edit_csr_post');
Route::post('/appxpay/marketing/csr/update-post', 'EmployeeController@update_csr_post');
Route::post('/appxpay/marketing/csr/remove-post', 'EmployeeController@remove_csr_post');
Route::get('/appxpay/marketing/csr/remove-post-image/{imagename}', 'EmployeeController@remove_csr_post_image');

Route::post('/appxpay/marketing/pr/add-post', 'EmployeeController@store_pr_post');
Route::get('/appxpay/marketing/pr/get-all-posts', 'EmployeeController@get_all_pr_post');
Route::get('/appxpay/marketing/pr/edit-post/{id}', 'EmployeeController@edit_pr_post');
Route::post('/appxpay/marketing/pr/update-post', 'EmployeeController@update_pr_post');
Route::post('/appxpay/marketing/pr/remove-post', 'EmployeeController@remove_pr_post');
Route::get('/appxpay/marketing/pr/remove-post-image/{imagename}', 'EmployeeController@remove_pr_post_image');

/**
 * 
 * Sales Menu Routing starts here
 * 
 */

Route::get('/appxpay/sales/lead-status/{id}', 'EmployeeController@sales')->name('sales-payable');
Route::post('/appxpay/sales/salesheet/new', 'EmployeeController@store_sale')->name('store-salessheet');
Route::post('/appxpay/sales/dailysheet/new', 'EmployeeController@store_daily')->name('store-salessheet');
Route::get('/appxpay/sales/leadsalesheet/get', 'EmployeeController@get_lead_sales')->name('get-leadsale');
Route::get('/appxpay/sales/dailysalesheet/get', 'EmployeeController@get_daily_sales')->name('get-dailysale');
Route::get('/appxpay/sales/salesheet/get', 'EmployeeController@get_sales')->name('get-salesheet');
Route::get('/appxpay/sales/leadsalesheet/edit/{id}', 'EmployeeController@edit_leadsale')->name('edit-leadsale');
Route::get('/appxpay/sales/dailysalesheet/edit/{id}', 'EmployeeController@edit_dailysale')->name('edit-dailysale');
Route::get('/appxpay/sales/salesheet/edit/{id}', 'EmployeeController@edit_sales')->name('edit-salessheet');
Route::post('/appxpay/sales/field-lead-salesheet/get', 'EmployeeController@get_field_lead_sales')->name('get-field-lead-salessheet');
Route::get('/appxpay/sales/field-daily-salesheet/get', 'EmployeeController@get_field_daily_sales')->name('get-field-daily-salessheet');
Route::get('/appxpay/sales/field-salesheet/get', 'EmployeeController@get_field_sales')->name('get-fieldsalessheet');

Route::get('/appxpay/sales/merchant-transactions/{id}', 'EmployeeController@sales')->name('sales-payable');
Route::post('/appxpay/sales/fieldsalesheet/new', 'EmployeeController@store_fieldsale')->name('store-fieldsalessheet');

Route::get('/appxpay/sales/field-lead-salesheet/edit/{id}', 'EmployeeController@edit_fieldsales')->name('edit-field-leadsalessheet');
Route::get('/appxpay/sales/field-daily-salesheet/edit/{id}', 'EmployeeController@edit_fieldsales')->name('edit-field-dailysalessheet');
Route::get('/appxpay/sales/fieldsalesheet/edit/{id}', 'EmployeeController@edit_fieldsales')->name('edit-fieldsalessheet');

Route::get('/appxpay/sales/merchant-commercials/{id}', 'EmployeeController@sales')->name('sales-payable');

Route::get('/appxpay/sales/product-modes/{id}', 'EmployeeController@sales')->name('sales-payable');
Route::get('/appxpay/sales/merchant-commercials/show/{id}', 'EmployeeController@show_merchant_charges');
Route::get('/appxpay/sales/transaction-breakup/{merchantid}', 'EmployeeController@get_transaction_breakup');
Route::get('/appxpay/sales/get/campaiagn/{perpage}', 'EmployeeController@get_campaigns');
Route::post('/appxpay/sales/campaiagn', 'EmployeeController@campaign');

/**
 * 
 * Risk Complaince Menu Routing starts here
 * 
 */
Route::get('/appxpay/risk-complaince/merchant-document/{id}', 'EmployeeController@risk_complaince')->name('merchant-document');
//Route::post('/appxpay/add_merchant/', 'EmployeeController@addmerchant')->name('add_merchant');
Route::any('/appxpay/add_merchant/', 'EmployeeController@addmerchant')->name('add_merchant');
Route::get('/appxpay/get_business_subcategories/', 'EmployeeController@getSubCategory')->name('getSubCategorys');

Route::get('/appxpay/risk-complaince/merchant-document/verify/get-merchant-doc-detail/{perpage}', 'EmployeeController@get_merchant_docs')->name('get-all-merchant-doc-detail');
Route::get('/appxpay/risk-complaince/merchant-document/verify/create/{id}', 'EmployeeController@show_merchant_docs_status')->name('new-merchant-doc');
Route::get('/appxpay/risk-complaince/merchant-document/view/{id}', 'EmployeeController@show_merchant_docs')->name('show_merchant_docs');
Route::post('/appxpay/risk-complaince/merchant-document/verify/new', 'EmployeeController@store_merchant_docs_status')->name('store-merchant-doc-status');
Route::post('/appxpay/risk-complaince/merchant-document/verify/update', 'EmployeeController@update_merchant_docs_status')->name('update-merchant-doc-status');
Route::post('/appxpay/risk-complaince/merchant-details/verify/update', 'EmployeeController@update_merchant_details_status')->name('update-merchant-details-status');
Route::post('/appxpay/risk-complaince/merchant-document/send-report', 'EmployeeController@merchant_docs_report')->name('merchant-doc-report');
Route::get('/appxpay/risk-complaince/merchant/details/{id}', 'EmployeeController@merchant_detail')->name('merchant-detail');


Route::get('/appxpay/risk-complaince/merchant-document/merchantadd', 'EmployeeController@merchantadd');

  


Route::get('/document-verify/download/merchant-document/{email}/{file}', function ($merchant_email, $file) {
    if (file_exists(storage_path('app/public/merchant/documents/' . $merchant_email . "/" . $file))) {
        return response()->download(storage_path('app/public/merchant/documents/' . $merchant_email . "/" . $file));
    } else {
        return redirect()->back();
    }
});

Route::post('/appxpay/risk-complaince/merchant/document/upload', 'EmployeeController@merchant_document_upload');
Route::post('/appxpay/risk-complaince/merchant/document/remove', 'EmployeeController@merchant_document_remove');

Route::get('/appxpay/risk-complaince/merchant/extra-documents/get/{perpapage}', 'EmployeeController@get_merchant_extdocuments');
Route::get('/appxpay/risk-complaince/merchant/extra-document/get/{id}', 'EmployeeController@get_merchant_extdocument')->name('extra-document');
Route::post('/appxpay/risk-complaince/merchant/extra-document/upload', 'EmployeeController@merchant_extdocument_upload');
Route::get('/appxpay/risk-complaince/merchant/extra-document/download/{file}', function ($file) {
    if (file_exists(storage_path('app/public/merchant/extradocuments/' . $file))) {
        return response()->download(storage_path('app/public/merchant/extradocuments/' . $file));
    } else {
        return redirect()->back();
    }
})->name('download-extra-doc');

Route::get('/appxpay/risk-complaince/background-verification/verify/get-merchant-business-details/{id}', 'EmployeeController@get_merchant_business_detail')->name('get-all-merchant-bussdetails');
Route::get('/appxpay/risk-complaince/background-verification/{id}', 'EmployeeController@risk_complaince')->name('background-check');
Route::post('/appxpay/risk-complaince/background-verification/verify/get-sub-category', 'EmployeeController@get_business_subcategory');
Route::get('/risk-complaince/background-verification/verify/get-verified-merchants/{perpage}', 'EmployeeController@get_verified_merchant')->name('get-all-verified-merchant');
Route::get('/appxpay/risk-complaince/background-verification/verify/create', 'EmployeeController@show_merchant_verify')->name('new-verify-merchant');
Route::post('/appxpay/risk-complaince/background-verification/verify/new', 'EmployeeController@store_merchant_verify')->name('store-verify-merchant');
Route::get('/appxpay/risk-complaince/background-verification/verify/edit/{id}', 'EmployeeController@edit_merchant_verify')->name('edit-verify-merchant');
Route::post('/appxpay/risk-complaince/background-verification/verify/update', 'EmployeeController@update_merchant_verify')->name('update-verify-merchant');

Route::get('/appxpay/risk-complaince/grievence-cell/{id}', 'EmployeeController@risk_complaince')->name('risk-complaince-payable');
Route::get('/risk-complaince/grievence-cell/get/all-cases/{perpage}', 'EmployeeController@get_all_cust_cases')->name('get-all-cases');
Route::get('/appxpay/risk-complaince/grievence-cell/get/cases-details/{id}', 'EmployeeController@get_case_details')->name('get-case-details');
Route::post('/appxpay/risk-complaince/grievence-cell/comment/add', 'EmployeeController@customer_comment')->name('add-case-comment');
Route::post('/appxpay/risk-complaince/grievence-cell/case/update', 'EmployeeController@update_customer_case')->name('case-update');
Route::get('/appxpay/risk-complaince/banned-products/{id}', 'EmployeeController@risk_complaince')->name('risk-complaince-payable');

/**
 * 
 * Legal Menu Routing starts here
 * 
 */

Route::get('/appxpay/legal/customer-case/{id}', 'EmployeeController@legal')->name('legal-payable');
Route::get('/appxpay/legal/customer-case/get/{id}', 'EmployeeController@get_legal_cases')->name('legal-cases');
Route::get('/appxpay/legal/customer-case/case/create', 'EmployeeController@show_legal_case')->name('show-legal-case');
Route::post('/appxpay/legal/customer-case/case/add', 'EmployeeController@store_legal_case')->name('store-legal-case');
Route::get('/appxpay/legal/customer-case/case/edit', 'EmployeeController@edit_legal_case')->name('edit-legal-case');
Route::post('/appxpay/legal/customer-case/case/update', 'EmployeeController@update_legal_case')->name('update-legal-case');

Route::get('/appxpay/legal/capital/{id}', 'EmployeeController@legal')->name('legal-payable');
Route::get('/appxpay/legal/express-case/{id}', 'EmployeeController@legal')->name('legal-payable');
Route::get('/appxpay/legal/pos-case/{id}', 'EmployeeController@legal')->name('legal-payable');
Route::get('/appxpay/legal/wallet-gullak-sanddok/{id}', 'EmployeeController@legal')->name('legal-payable');
Route::get('/appxpay/legal/credit-card-case/{id}', 'EmployeeController@legal')->name('legal-payable');
Route::get('/appxpay/legal/ivr-pay-case/{id}', 'EmployeeController@legal')->name('legal-payable');

/**
 * 
 * HRM Menu Route Code Starts here
 */

Route::get('/appxpay/hrm/employee-details/{id}', 'EmployeeController@hrm')->name('hrm-payable');
Route::get('/appxpay/hrm/nda/{id}', 'EmployeeController@hrm')->name('hrm-payable');
Route::get('/appxpay/hrm/bvf/{id}', 'EmployeeController@hrm')->name('hrm-payable');
Route::get('/appxpay/hrm/employee-attendance/{id}', 'EmployeeController@hrm')->name('hrm-payable');
Route::get('/appxpay/hrm/payroll/{id}', 'EmployeeController@hrm')->name('hrm-payable');
Route::get('/appxpay/hrm/performance-appraisal/{id}', 'EmployeeController@hrm')->name('hrm-payable');
Route::get('/appxpay/hrm/confidentiality-agreement/{id}', 'EmployeeController@hrm')->name('hrm-payable');
Route::get('/appxpay/hrm/career/{id}', 'EmployeeController@hrm')->name('hrm-career');

Route::get('/appxpay/hrm/get-employees', 'EmployeeController@get_all_employees')->name('get.employees');
Route::get('/appxpay/hrm/employee-accesses/{id}', 'EmployeeController@employeeAccess')->name('employeeAccess');
Route::post('/appxpay/hrm/edit-employee-accesses/', 'EmployeeController@editemployeeAccess')->name('editemployeeAccess');
Route::get('/appxpay/hrm/employee-details/edit/{id}', 'EmployeeController@edit_employee')->name('edit.employee');
Route::post('/appxpay/hrm/get-employees/update', 'EmployeeController@update_employee');
Route::post('/appxpay/hrm/get-employees/add', 'EmployeeController@store_employee');
Route::post('/appxpay/hrm/get-employees/delete', 'EmployeeController@delete_employee');

Route::post('/appxpay/hrm/bvf/add-personal-profile', 'EmployeeController@store_personal');
Route::post('/appxpay/hrm/bvf/add-contact-details', 'EmployeeController@store_contact_details');
Route::post('/appxpay/hrm/bvf/add-reference-details', 'EmployeeController@store_reference_details');

Route::get('/appxpay/hrm/nda/get-nda/{id}', 'EmployeeController@get_employee_nda_doc')->name('nda-file');
Route::post('/appxpay/hrm/nda/upload-file', 'EmployeeController@upload_nda_form');
Route::get('/appxpay/hrm/conagree/get-conagree/{id}', 'EmployeeController@get_employee_ca_doc')->name('ca-file');
Route::post('/appxpay/hrm/ca/upload-file', 'EmployeeController@upload_ca_form');

Route::get('/appxpay/hrm/payroll/payslip/form', 'EmployeeController@emp_payslip')->name('payslip');
Route::get('/appxpay/hrm/payroll/payslip/get-form/{id}', 'EmployeeController@emp_payslip_from')->name('payslip-from');
Route::post('/appxpay/hrm/payroll/payslip/add', 'EmployeeController@store_payslip')->name('add-payslip');
Route::get('/appxpay/hrm/payroll/payslip/edit/{id}', 'EmployeeController@edit_payslip')->name('edit-payslip');
Route::get('/appxpay/hrm/payroll/payslip/get', 'EmployeeController@get_payslip')->name('get-payslip');

Route::get('/appxpay/hrm/career/job/get/{id}', 'EmployeeController@get_job');
Route::post('/appxpay/hrm/career/job/add', 'EmployeeController@store_job');
Route::get('/appxpay/hrm/career/job/edit/{id}', 'EmployeeController@edit_job');
Route::post('/appxpay/hrm/career/job/update', 'EmployeeController@update_job');
Route::post('/appxpay/hrm/career/job/change-status', 'EmployeeController@update_job_status');

Route::get('/appxpay/hrm/career/applicant/get/{id}', 'EmployeeController@get_applicants');
Route::post('/appxpay/hrm/career/applicant/update', 'EmployeeController@update_applicant_status');

Route::get('/download/applicant/resume/{file}', function ($file = '') {
    return response()->download(public_path('storage/applicants/' . $file));
});

/**
 * 
 * Merchanr Menu Route Code Starts here
 */

Route::get('/appxpay/merchant/transactions/{id}', 'EmployeeController@admin_merchant');
Route::get('/appxpay/merchant/transaction/methods/{id}', 'EmployeeController@admin_merchant');
Route::get('/appxpay/merchant/details/{id}', 'EmployeeController@admin_merchant');
Route::get('/appxpay/merchant/routes/{id}', 'EmployeeController@admin_merchant');
Route::get('/appxpay/merchant/cases/{id}', 'EmployeeController@admin_merchant');
Route::get('/appxpay/merchant/adjustments/{id}', 'EmployeeController@admin_merchant');

Route::post('/appxpay/merchant/no-of-transactions', 'EmployeeController@no_of_transactions');
Route::post('/appxpay/merchant/transaction-amount', 'EmployeeController@transaction_amount');

Route::get('/merchant/get-all-merchants/{perpage}', 'EmployeeController@get_all_merchants');
Route::get('/appxpay/merchant/get-all-merchant-cases', 'EmployeeController@get_all_cases');
Route::get('/appxpay/merchant/get-all-adjustments', 'EmployeeController@get_all_adjustments');

Route::get('/merchant/no-of-paylinks', 'EmployeeController@no_of_paylinks');
Route::get('/appxpay/merchant/no-of-invoices', 'EmployeeController@no_of_invoices');

/**
 * 
 * My Account Menu Route Code Starts here
 */

Route::get('/appxpay/my-account', 'EmployeeController@my_account')->name("my-account");

Route::post('/appxpay/my-account/personal-details/update', 'EmployeeController@update_mydetails')->name("my-details-update");

Route::post('/appxpay/my-account/request-password-change', 'EmployeeController@request_password_change')->name("my-password-change");

Route::post('/appxpay/my-account/verify-email-OTP', 'EmployeeController@verify_emailOTP')->name("verify-emailOTP");

Route::post('/appxpay/my-account/verify-mobile-OTP', 'EmployeeController@verify_mobileOTP')->name("verify-mobileOTP");

Route::post('/appxpay/my-account/change-password', 'EmployeeController@change_password')->name("change-password");

Route::get('/appxpay/merchant/get-login-activities', 'EmployeeController@login_activities');

/**
 * 
 * My Account Menu Route Code Starts here
 */
Route::get('/appxpay/work-status', 'EmployeeController@show_workstatus')->name("show-status");

Route::get('/appxpay/work-status/get/{id}', 'EmployeeController@get_workstatus')->name("get-work-status");

Route::post('/appxpay/work-status/add', 'EmployeeController@store_workstatus')->name("store-work-status");

Route::get('/appxpay/work-status/edit/{id}', 'EmployeeController@edit_workstatus')->name("edit-work-status");

Route::post('/appxpay/work-status/update', 'EmployeeController@update_workstatus')->name("update-work-status");

//appxpay Angular Related Routings

Route::post('/appxpay/contact-us', 'VerifyController@appxpay_contactus');

Route::get('/appxpay/pagination/{submod}-{perpage}', 'EmployeeController@employee_pagination');

Route::get('/appxpay/emp/search/{submod}/{searchtext}', 'EmployeeController@employee_search');

//testing
Route::get('/test', 'MerchantController@graph_success_rate');


Route::group(['prefix' => 'invoice'], function () {
    Route::get('/demo', 'InvoiceController@demo')->name('demo');
    Route::get('/recipt/{id}', 'InvoiceController@recipt')->name('recipt');
});

