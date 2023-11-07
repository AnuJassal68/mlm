<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\IncomeSettingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FranchiseController;
use App\Http\Controllers\ForgotPasswordController;

use App\Http\Controllers\adminSupportTicketController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\StateController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/Home', [HomeController::class,'index']);
Route::post('/submit_deposit', [DepositController::class, 'submitDeposit']);
Route::get('/log-in', [LoginController::class, 'showLoginForm']);
Route::any('/login', [LoginController::class, 'login']);
Route::any('/log-out', [LoginController::class, 'logout']);
Route::any('/signup', [LoginController::class, 'signup']);

Route::get('/showChangePasswordForm', [LoginController::class,'showChangePasswordForm'])->name('showChangePasswordForm');
Route::post('/changePassword', [LoginController::class,'changePassword'])->name('changePassword');



Route::get('/how-it-works', [HomeController::class,'howitworks']);
Route::get('/future', [HomeController::class,'future']);
Route::get('/concept', [HomeController::class,'concept']);
Route::get('/about', [HomeController::class,'about']);
Route::get('/faq', [HomeController::class,'faq']);

Route::post('register', [UserController::class,'register'])->name('register');
Route::middleware('auth.dashboard')->group(function () {
    Route::get('dashboard', [UserController::class,'dashboard'])->name('user/dashboard');

    Route::get('profile', [UserController::class,'profile'])->name('profile');
  
    Route::any('updateProfile', [UserController::class,'updateProfile'])->name('updateProfile');

    
    Route::any('/get-balance-info', [DepositController::class, 'getBalanceInfo'])->name('get-balance-info');
    Route::any('/deposit', [DepositController::class, 'index'])->name('deposit');
    Route::any('/re-investment', [DepositController::class, 'reinvestment'])->name('re-investment');
    Route::any('/my-investment', [DepositController::class, 'myinvestment'])->name('my-investment');
    Route::any('/my-incentive', [DepositController::class, 'incentive'])->name('my-incentive');
    Route::any('/my-deposits', [DepositController::class, 'viewPendingDeposits'])->name('my-deposits');
    Route::get('/withdrawlist', [DepositController::class, 'withdrawlist'])->name('withdrawlist');
    Route::any('/directs', [DepositController::class, 'directReferrals'])->name('directs');
    Route::any('/network', [DepositController::class, 'viewNetwork'])->name('network');
    Route::any('/incentive', [DepositController::class, 'dailyincentive'])->name('incentive');
    Route::get('/statement', [DepositController::class, 'statement'])->name('statement');
    Route::any('/level-incentive', [DepositController::class, 'levelincentive'])->name('level-incentive'); 
    Route::any('/withdrawal', [DepositController::class, 'withdrawlview'])->name('withdrawal');
    Route::any('/request-Payments', [DepositController::class, 'requestPayments'])->name('request-withdraw');
    Route::any('/all-statements', [DepositController::class, 'allstatement'])->name('all-statements');
    Route::any('/processDeposit', [DepositController::class, 'processDeposit'])->name('processDeposit');
 //support ticket controller
    Route::any('/new-ticket', [SupportTicketController::class, 'newticket'])->name('new-ticket');
    Route::any('/support-ticket', [SupportTicketController::class, 'supporticket'])->name('support-ticket');
    Route::any('/send-ticket', [SupportTicketController::class, 'sendSupportTicket'])->name('send-ticket');
    Route::get('/filesupport', [SupportTicketController::class, 'filesupport'])->name('filesupport');
    Route::any('/replyToTicket', [SupportTicketController::class, 'replyToTicket'])->name('replyToTicket');
});

Route::any('/forget-password', [ForgotPasswordController::class, 'forgetpasswordpage'])->name('forget-password');
Route::any('/sendPasswordResetEmail', [ForgotPasswordController::class, 'sendPasswordResetEmail'])->name('sendPasswordResetEmail');
//  Route::get('FranchiseController', [UserController::class,'search'])->name('search');
Route::get('/change-password', [ChangePasswordController::class,'showForm'])->name('changepassword');
Route::post('/change-password', [ChangePasswordController::class,'changePassword'])->name('change.password');
Route::any('/adminlogin', [ChangePasswordController::class,'adminlogin'])->name('adminlogin');
Route::any('/adminlogindata', [ChangePasswordController::class,'adminlogindata'])->name('adminlogindata');


Route::middleware(['admin'])->group(function () {

    Route::any('/admindashboard', [ChangePasswordController::class,'admindashboard'])->name('admindashboard');
    Route::any('/activitylogindex', [ChangePasswordController::class,'activitylogindex'])->name('activitylogindex');
    //admin support controller routes

    Route::any('/support', [adminSupportTicketController::class, 'supportticket'])->name('support');
    Route::get('/support-closed', [adminSupportTicketController::class, 'supportclosed'])->name('support-closed');
    Route::get('/get-ticket-details/{ticketId}', [adminSupportTicketController::class,'getTicketDetails'])->name('get.ticket.details');
    // Route::post('/update-ticket', [adminSupportTicketController::class, 'updateTicketMessage']);
    Route::get('{id}/edit', [adminSupportTicketController::class, 'edit'])->name('profile.edit');
    Route::any('/submit-client/{id}', [adminSupportTicketController::class, 'submitClient'])->name('submit-client');
    Route::delete('/delete-user/{id}', [adminSupportTicketController::class, 'destroy'])->name('delete-user');
    Route::any('/process-ticket', [adminSupportTicketController::class, 'processTicket'])->name('process.ticket');
    Route::post('/move-to-pending', [adminSupportTicketController::class, 'moveToPending'])->name('moveToPending');
    Route::get('/get-closed-ticket-data/{ticketId}', [adminSupportTicketController::class, 'getClosedTicketData'])->name('get-closed-ticket-data');
    Route::get('/support-pending', [adminSupportTicketController::class, 'supportpending'])->name('support-pending');
    Route::any('/update-ticket-message/{id}', [adminSupportTicketController::class, 'updateTicketMessage'])->name('update-ticket-message');

    //country/cities/state controller routes
    Route::get('/countries', [CountryController::class, 'index']);
    Route::get('/countries/addedit/{id?}', [CountryController::class, 'addEdit'])->name('add');
    Route::post('/countries/addedit/{id?}', [CountryController::class, 'save']);
    Route::any('/teams', [CountryController::class, 'teams'])->name('teams');
    Route::any('/stateindex/{id}', [StateController::class, 'stateindex'])->name('states');
    Route::any('/statestatus', [StateController::class, 'statestatus'])->name('statestatus');
    Route::any('/stateadd/{id}', [StateController::class, 'stateadd'])->name('stateadd');
    Route::any('/stateupdate/{id}', [StateController::class, 'stateupdate'])->name('stateupdate');
    Route::any('/cities/{id?}', [StateController::class, 'cities'])->name('cities');
    Route::any('/addcities/{id?}', [StateController::class, 'addcities'])->name('addcities');
    Route::any('/citiesstatus', [StateController::class, 'citiesstatus'])->name('citiesstatus');
    Route::any('/citiesupdate/{id}', [StateController::class, 'citiesupdate'])->name('citiesupdate');

    //manage user controller routes

    Route::any('/userlist', [FranchiseController::class, 'userlist'])->name('userlist');
    Route::post('/usersearch', [FranchiseController::class, 'usersearch'])->name('usersearch');
    Route::any('/agentsStatus', [FranchiseController::class, 'agentsStatus'])->name('agent');
    Route::any('/generateExcel', [FranchiseController::class, 'generateExcel'])->name('generateExcel');
    //notification controller routes
    Route::get('notifications', [NotificationController::class,'notificationindex'])->name('notifications.index');
    Route::get('notifications/addedit/{id?}', [NotificationController::class,'showAddEditForm'])->name('notifications.edit');
    Route::post('/notification/update/{id?}', [NotificationController::class, 'update']);
    Route::post('notifications/activate', [NotificationController::class,'activateSelected'])->name('notifications.activate');
    Route::post('notifications/deactivate', [NotificationController::class,'deactivateSelected'])->name('notifications.deactivate');
    Route::post('notifications/delete', [NotificationController::class,'deleteSelected'])->name('notifications.delete');

    Route::any('/payoutindex', [PayoutController::class, 'payoutindex'])->name('paid-list');
    Route::post('/pay/{id}', [PayoutController::class, 'pay']);
  
    Route::post('/payouts/search', [PayoutController::class, 'search'])->name('payouts.search');
    Route::any('/payouts/excel', [PayoutController::class, 'excel'])->name('payouts.excel');
    Route::post('/payouts/reset', [PayoutController::class, 'reset'])->name('payouts.reset');
    Route::any('/setting-index', [SettingController::class, 'settingindex'])->name('setting-index');
    Route::post('/setting', [SettingController::class, 'updateSettings'])->name('settings.update');
    Route::get('/income-setting', [IncomeSettingController::class, 'showIncomeSettingsForm'])->name('income-setting');
    Route::post('/income-setting', [IncomeSettingController::class, 'updateIncomeSettings'])->name('update-setting');
});
Route::any('/logout', [ChangePasswordController::class,'logout'])->name('logout');
