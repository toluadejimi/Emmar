<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StoredataController;
use App\Http\Controllers\Admin\TerminalController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Mobile\Auth\RegisterController;
use App\Http\Controllers\Mobile\Face\FaceRecognitionController;
use Illuminate\Support\Facades\Route;



Route::post('verify-phone', [RegisterController::class, 'verify_phone_number']);
Route::post('verify-bvn', [RegisterController::class, 'verifyBvn']);
Route::post('step-1', [RegisterController::class, 'step_1_registration']);
Route::post('save-bvn-info', [RegisterController::class, 'save_info_bvn']);
Route::post('save-image', [RegisterController::class, 'save_face_image']);
Route::post('set-password', [RegisterController::class, 'set_password']);
Route::post('set-pin', [RegisterController::class, 'set_pin']);
Route::post('verify-face', [FaceRecognitionController::class, 'compareFaces']);




Route::group(['prefix' => 'admin'], function () {
    Route::post('login', [AdminController::class, 'admin_login']);

    Route::group(['middleware' => ['auth:api', 'acess']], function () {


        //Banks
        Route::post('create-bank', [BankController::class, 'create_bank']);
        Route::post('update-bank', [BankController::class, 'update_bank']);
        Route::post('delete-bank', [BankController::class, 'delete_bank']);
        Route::post('search-bank', [BankController::class, 'search_bank']);
        Route::get('get-all-banks', [BankController::class, 'get_all_banks']);
        Route::get('all-banks', [BankController::class, 'all_banks']);


        //Dashboard
        Route::get('dashboard', [DashboardController::class, 'dashboard_data']);


        //User
        Route::post('create-user', [UserController::class, 'create_user']);
        Route::post('update-user', [UserController::class, 'update_user']);
        Route::get('list-users', [UserController::class, 'get_users']);
        Route::get('list-customer-users', [UserController::class, 'get_customer_users']);
        Route::get('list-bank-users', [UserController::class, 'get_bank_users']);
        Route::get('delete-users', [UserController::class, 'delete_user']);
        Route::post('search-users', [UserController::class, 'search_user']);




        //Transaction
        Route::get('get-transactions/{limit}', [TransactionController::class, 'get_all_transactions']);
        Route::get('get-transactions-filter/{limit}', [TransactionController::class, 'get_transactions_by_filter']);
        Route::get('export-transactions', [TransactionController::class, 'export_transactions']);





        Route::post('transaction-filter', [TransactionController::class, 'get_all_transaction_by_filter']);


        //Terminal
        Route::post('create-terminal', [TerminalController::class, 'create_terminal']);
        Route::post('update-terminal', [TerminalController::class, 'update_terminal']);
        Route::get('view-terminal', [TerminalController::class, 'view_all_terminal']);
        Route::any('delete-terminal', [TerminalopController::class, 'delete_terminal']);
        Route::post('search-terminal', [TerminalController::class, 'search_terminal']);
        Route::post('import-terminal', [TerminalController::class, 'create_bulk_terminal']);
        Route::get('export-terminal-bank', [TerminalController::class, 'get_terminal_by_bank_export']);
        Route::get('get-terminal-bank', [TerminalController::class, 'get_terminal_by_bank']);







    });


});


Route::group(['prefix' => 'v1'], function () {

    Route::any('virtual-notify',  [VirtualAccountController::class,'virtual_cash_in']);
    Route::any('get-details', [TerminalopController::class, 'get_terminal_details']);
    Route::any('initiate-transaction', [PosTrasnactionController::class, 'EtopPosLogs']);
    Route::any('get-logged-data', [PosTrasnactionController::class, 'get_logged_data']);
    Route::post('reset-pin', [TerminalopController::class, 'reset_pin']);
    Route::post('verify-pin', [TerminalopController::class, 'verify_pin']);
    Route::any('get-all-logged-data', [PosTrasnactionController::class, 'get_all_by_serial_logged_data']);
    Route::any('get-all-transactions', [PosTrasnactionController::class, 'get_all_transaction']);


    Route::get('get-bank-transactions', [PosTrasnactionController::class, 'get_bank_transactions']);



    //UP TRNANSFER
    Route::any('notification', [PayByTransferController::class, 'webhook_notification']);
    Route::post('register-merchant', [PayByTransferController::class, 'register_merchant']);


    Route::any('get-all-logged-data/get-all-transactions', [PosTrasnactionController::class, 'get_all_transaction_by_filter']);
    Route::any('complete-transaction', [PosTrasnactionController::class, 'EtopPos']);


    //IBDC
    Route::any('get-disco', [IbdcController::class, 'get_meter_disco']);
    Route::any('validate-ibdc-meter', [IbdcController::class, 'validate_ibdc_meter']);
    Route::any('buy-power', [IbdcController::class, 'buy_power']);
    Route::any('buy-token', [IbdcController::class, 'buy_token']);
    Route::any('reprint-token', [IbdcController::class, 'reprint_token']);
    Route::any('retry-token', [IbdcController::class, 'retry_token']);
    Route::any('get-ibdc-transactions', [IbdcController::class, 'get_all_transaction']);











});

