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

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;

use App\Http\Controllers\SIDController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AccessManage\rolesManageController;
use App\Http\Controllers\MerchantsOnboardingController;
use App\Http\Controllers\VendorOnboardingController;
use App\Http\Controllers\Admin\SettlementController;
use App\Http\Controllers\EmployeeAuth\LoginController;
use App\Http\Controllers\ManageOverAllAccountsController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\PayoutManagerController;
use App\Http\Controllers\PayoutAdminController;
use App\Http\Controllers\EmployeeController;

Route::any('', function () {
    return redirect('/login');
});

/* 
 * Employee Funcionality Controller Routes
 * 
 */

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


Route::post('/verify-credentials', 'EmployeeAuth\LoginController@verifyLogin');

Route::get('/load-login-forms', [LoginController::class, 'load_login_forms']);

Route::post('/email-verify-otp', [LoginController::class, 'appxpay_verify_email_otp'])->name('appxpay-email-verify');

Route::post('/appxpay/mobile-verify-otp', 'EmployeeAuth\LoginController@appxpay_verify_mobile_otp')->name('appxpay-mobile-verify');

Route::get('/appxpay/ft-password-change/send-otp-mobile', 'EmployeeAuth\LoginController@load_change_password_form')->name('send-otp-mobile');

Route::post('/appxpay/ft-password-change/verify-empmobile-otp', 'EmployeeAuth\LoginController@verify_empmobile_OTP')->name('verify-empmobile-otp');

Route::post('/appxpay/ft-password-change/ftpassword-change', 'EmployeeAuth\LoginController@change_ftemppassword')->name('ftpassword-change');

Route::get('/roles/view', [rolesManageController::class, 'rolesManage'])->name('roles.view');
Route::get('/modules/manage/index', [rolesManageController::class, 'manageModules'])->name('modules.manage.index');
Route::get('/dashboard', 'EmployeeController@index')->name('appxpay-dashboard');
Route::get('/permission/index', [rolesManageController::class, 'permissionConfiguration'])->name('permission.index');
Route::post('/roles/create', [rolesManageController::class, 'store'])->name('roles.store');
Route::get('/roles/list', [rolesManageController::class, 'roleList'])->name('roles.roleList');
Route::post('/module/create', [rolesManageController::class, 'moduleStore'])->name('module.store');

Route::get('/bank/accounts/list', [ManageOverAllAccountsController::class, 'receiveAmount'])->name('accounts.receive.amount');
Route::post('/bank/accounts/store', [ManageOverAllAccountsController::class, 'store'])->name('accounts.store');
Route::get('/add/bank/list', [ManageOverAllAccountsController::class, 'bankList'])->name('bank.list');
Route::get('/service/bank/{id}', [ManageOverAllAccountsController::class, 'getBank'])->name('service.getBankData');
Route::get('/service/bank/update/{id}', [ManageOverAllAccountsController::class, 'statusChange'])->name('service.statusChange');
Route::get('/service/fund/index', [ManageOverAllAccountsController::class, 'serviceFundIndex'])->name('service.fund.receive');
Route::get('/merchant/topup/list', [ManageOverAllAccountsController::class, 'addFundRequestList'])->name('add.servicefound.list');
Route::get('/merchant/service/request/{id}', [ManageOverAllAccountsController::class, 'getRequestData'])->name('merchant.service.getRequestData');
Route::get('/merchant/topup/update/{id}', [ManageOverAllAccountsController::class, 'updateRequestData'])->name('merchant.service.updateRequestData');

Route::get('/merchant/partner/list', [MerchantsOnboardingController::class, 'partnerList'])->name('merchant.partner.list');
Route::get('/partners/list', [MerchantsOnboardingController::class, 'listPaymentAggregators'])->name('partners.list');
Route::get('/partner/update/{id}', [MerchantsOnboardingController::class, 'partnerUpdate'])->name('partner.update.ajax');
Route::post('/partner/add', [MerchantsOnboardingController::class, 'addPartner'])->name('partner.add');
Route::post('/partner/update/{id}', [MerchantsOnboardingController::class, 'UpdatePartner'])->name('partner.update');
Route::post('/partner/change-status', [MerchantsOnboardingController::class, 'changeStatus'])->name('partner.changeStatus');

Route::post('/modules/list', [rolesManageController::class, 'modulesList'])->name('modules.list.store');
Route::get('/modules/list', [rolesManageController::class, 'modulesList'])->name('modules.list');
Route::post('/permission/list', [rolesManageController::class, 'listofpermissions'])->name('roles.permission.list');
Route::post('/permission/update', [rolesManageController::class, 'updatePermission'])->name('roles.permission.update');
Route::any('/appxpaydashboard/ajax', 'EmployeeController@dashboardAjax');
Route::get('/appxpaydashboard/get-terminals', [EmployeeController::class, 'getTerminalsByMerchantId']);

Route::post('/onboarding/initial', [MerchantsOnboardingController::class, 'store'])->name('store.step.one');
Route::post('/onboarding/business', [MerchantsOnboardingController::class, 'businessStore'])->name('business.step.two');
Route::post('/onboarding/webhook', [MerchantsOnboardingController::class, 'webhookStore'])->name('webhook.step.three');
Route::post('/onboarding/complete', [MerchantsOnboardingController::class, 'onboardingComplete'])->name('onboarding.complete');
Route::any('/onboarding/eqyroksfig/{id?}', [MerchantsOnboardingController::class, 'index'])->name('onboarding.one');
Route::any('/onboarding/aftxjcqenf/{id}', [MerchantsOnboardingController::class, 'steptwo'])->name('onboarding.two');
Route::any('/onboarding/cokxgpauql/{id}', [MerchantsOnboardingController::class, 'stepthree'])->name('onboarding.three');
Route::any('/onboarding/xyhsrpzfip/{id}', [MerchantsOnboardingController::class, 'stepfour'])->name('onboarding.four');
Route::post('/onboarding/business/apps', [MerchantsOnboardingController::class, 'businessAppsStore'])->name('business.apps.store');
Route::get('/onboarding/business/apps/list', [MerchantsOnboardingController::class, 'webAppsList'])->name('web.apps.list');
Route::any('/business/apps/{id}', [MerchantsOnboardingController::class, 'businessAppsGet'])->name('business.get.apps');
Route::post('/appxpay/file/upload', [MerchantsOnboardingController::class, 'uploadFile'])->name('doc.file.upload');
Route::get('/merchant/settlements/list', [MerchantsOnboardingController::class, 'liveSettlements'])->name('live.settlements.list');

//Payout Service Fee
Route::get('/merchant/service/fee', [PayoutManagerController::class, 'commissionIndex'])->name('payout.merchant.commission');
Route::get('/merchant/service/list', [PayoutManagerController::class, 'listCommission'])->name('payout.list.commission');
Route::get('/merchant/slab/{id}', [PayoutManagerController::class, 'slabIndex'])->name('slab.list.commission');
Route::post('/payout/slab/store', [PayoutManagerController::class, 'slabStore'])->name('slab.store.commission');
Route::post('/payout/slab/update', [PayoutManagerController::class, 'slabUpdate'])->name('slab.update.commission');
Route::get('/payout/slab/list', [PayoutManagerController::class, 'listofslabs'])->name('slab.listofslabs.commission');

Route::get('/payout-dashboard', [PayoutAdminController::class, 'index'])->name('payouts.index');
Route::get('/dashboard-statistics', [PayoutAdminController::class, 'dashboardStatistics'])->name('payout.dashboardStatistics');
Route::get('/payout-transactions', [PayoutAdminController::class, 'AdminPayoutTransactions']);
Route::get('/payout-translist', [PayoutAdminController::class, 'PayoutAdminTransactionList'])->name('payout.admintranslist');



// Route::get('/settlement/getoverallsettlementList', 'SettlementController@index');
// Route::post('/settlement/attachReceipt', 'Admin\SettlementController@attachReceipt');
// Route::post('/settlement/markAsPaid', 'Admin\SettlementController@markAsPaid');

Route::get('/settlement/getoverallsettlementList',  [SettlementController::class, 'index']);
Route::post('/settlement/attachReceipt',  [SettlementController::class, 'attachReceipt']);
Route::post('/settlement/markAsPaid',  [SettlementController::class, 'markAsPaid']);
Route::get('/settlement/getattachreceipt/{doc_id}',  [SettlementController::class, 'getattachReceipt']);


Route::get('/settlement/downloadExcel/{id}', [SettlementController::class, 'settlementdownloadExcel']);




Route::post('/onboarding/merchants/list', [MerchantsOnboardingController::class, 'listofmerchants'])->name('merchants.verify.list');

Route::any('/onboarding/jnylavzkrs/{id?}', [VendorOnboardingController::class, 'index'])->name('vendor.onboarding.one');
Route::any('/onboarding/mqkpdfhzkt/{id}', [VendorOnboardingController::class, 'steptwo'])->name('vendor.onboarding.two');
Route::post('/onboarding/vendor/initial', [VendorOnboardingController::class, 'store'])->name('vendor.step.one');
Route::post('/vendor/onboarding/complete', [VendorOnboardingController::class, 'onboardingComplete'])->name('onboarding.vendor.complete');

Route::post('/onboarding/vendor/list', [VendorOnboardingController::class, 'listofvendor'])->name('vendor.complete.list');

Route::get('/merchants/list/index/{vendorid?}', [VendorOnboardingController::class, 'listFilterVendor'])->name('redirect.merchant.list');

Route::get('/vendor/list/index', function () {
    return view('onboarding.vendor.vendorsList');
});

Route::post('/validate-email', [MerchantsOnboardingController::class, 'validateEmail'])->name('validate.email');
Route::post('/validate-mobile', [MerchantsOnboardingController::class, 'validateMobile'])->name('validate.mobile');
Route::post('/validate-username', [MerchantsOnboardingController::class, 'validateUsername'])->name('validate.username');
Route::get('/dashboardGraphData', 'EmployeeController@dashboardTransactionGraph')->name('dashboardTransactionGraph');

Route::get('/dashboard_transactionstats', 'EmployeeController@dashboardTransactionStats')->name('dashboardTransactionStats');
Route::get('/excel-export-successratio', 'EmployeeController@excelExportSuccessratio')->name('excel.export.successratio');


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


Route::post('/appxpay/settlement/download-transaction-data', 'EmployeeController@download_transaction')->name('download-transactiondata');

Route::get('/settlement/getoverallsettlementList', 'Admin\SettlementController@index');

Route::post('/appxpay/settlement/file_upload', 'Admin\SettlementController@settlementFileUpload')->name('settlement-fileupload');

Route::get('/settlement/getcompletedsettlementList', 'Admin\SettlementController@SettlementList')->name('getcompletedsettlementList');

Route::any('/appxpay/settlement/markasPaid/{settlement_id}', 'Admin\SettlementController@PaidStatusUpdate')->name('PaidStatusUpdate');


/**
 * 
 * My Account Menu Route Code Starts here
 */

Route::get('/my-account', 'EmployeeController@my_account')->name("my-account");

Route::post('/appxpay/my-account/personal-details/update', 'EmployeeController@update_mydetails')->name("my-details-update");

Route::post('/appxpay/my-account/request-password-change', 'EmployeeController@request_password_change')->name("my-password-change");

Route::post('/appxpay/my-account/verify-email-OTP', 'EmployeeController@verify_emailOTP')->name("verify-emailOTP");

Route::post('/appxpay/my-account/verify-mobile-OTP', 'EmployeeController@verify_mobileOTP')->name("verify-mobileOTP");

Route::post('/appxpay/my-account/change-password', 'EmployeeController@change_password')->name("change-password");



Route::get('transactionslist/data', 'TransactionsController@getTransactionsList')->name('transactions.data.list');


Route::get('/merchant/details/Verify/{id}', [MerchantsOnboardingController::class, 'verifyDetails'])->name('merchant.verifyDetails');
Route::get('merchant/details/{id}', [MerchantsOnboardingController::class, 'getMerchantDetails'])->name('merchant.details');
Route::any('/merchants/transactions', [MerchantsOnboardingController::class, 'getMerchantsTransactions'])->name('merchants.transactions');
Route::any('/employee/pages/myaccount', [MerchantsOnboardingController::class, 'getMyaccountDetails'])->name('myaccounts.details');
Route::any('/employee/profile', [MerchantsOnboardingController::class, 'myProfiles'])->name('employee.profile');
Route::any('/employee/vendor', [MerchantsOnboardingController::class, 'getVendorDeatils'])->name('employee.vendor');
Route::post('/paymentlist/export',  [TransactionsController::class, 'txnExcelExport'])->name('merchant.txnExport');




// routes/web.php


Route::get('/payout/addfund', [PayoutAdminController::class, 'showAddFund'])->name('payout.addfund');
Route::get('payout/topup/list', [PayoutAdminController::class, 'walletFundRequestList'])->name('add.wallet.list');

Route::get('/payout/service/request/{id}', [PayoutAdminController::class, 'getRequestData'])->name('merchant.wallet.getRequestData');

Route::get('/payout/topup/update/{id}', [PayoutAdminController::class, 'updateRequestData'])->name('merchant.addfund.updateRequestData');

Route::get('/payout/service/fund/index', [PayoutAdminController::class, 'serviceFundIndex'])->name('addwallet.fund.receive');

Route::get('/appxpaydashboard/payout-get-terminals', [PayoutAdminController::class, 'getTerminalsByMerchantId']);


Route::get('/login-activities', [EmployeeController::class, 'getLoginActivities'])->name('login.activities');
