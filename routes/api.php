<?php


use App\Http\Controllers\Mobile\Auth\LoginController;
use App\Http\Controllers\Mobile\Auth\ProfileController;
use App\Http\Controllers\Mobile\Auth\RegisterController;
use App\Http\Controllers\Mobile\BillsController;
use App\Http\Controllers\Mobile\Face\FaceRecognitionController;
use App\Http\Controllers\Mobile\TransactionController;
use App\Http\Controllers\Mobile\TransferController;
use App\Http\Controllers\Mobile\VAS\AirtimeController;
use App\Http\Controllers\Mobile\VAS\CableController;
use App\Http\Controllers\Mobile\VAS\DataController;
use App\Http\Controllers\Mobile\VAS\EducationController;
use App\Http\Controllers\Mobile\VAS\InsuranceController;
use App\Http\Controllers\Mobile\VAS\PowerController;
use Illuminate\Support\Facades\Route;


Route::any('notification', [TransactionController::class, 'notification']);



Route::post('verify-phone', [RegisterController::class, 'verify_phone_number']);
Route::post('verify-bvn', [RegisterController::class, 'verifyBvn']);
Route::post('step-1', [RegisterController::class, 'step_1_registration']);
Route::post('save-bvn-info', [RegisterController::class, 'save_info_bvn']);
Route::post('save-image', [RegisterController::class, 'save_face_image']);
Route::post('set-password', [RegisterController::class, 'set_password']);
Route::post('set-pin', [RegisterController::class, 'set_pin']);
Route::post('verify-face', [FaceRecognitionController::class, 'compareFaces']);
Route::post('verify-code', [RegisterController::class, 'verify_code']);
Route::post('login', [LoginController::class, 'login']);
Route::post('name-enquiry', [TransferController::class, 'name_inquary']);



Route::post('verify-face', [FaceRecognitionController::class, 'compareFaces']);





Route::get('get-airtime-biller', [AirtimeController::class, 'get_airtime_biller']);
Route::get('get-data-biller', [DataController::class, 'get_data']);
Route::get('get-cable-biller', [CableController::class, 'get_cable_biller']);
Route::post('validate-cable', [CableController::class, 'validate_cable']);
Route::get('get-exams-biller', [EducationController::class, 'get_education_biller']);
Route::get('get-waste-biller', [BillsController::class, 'get_waste_biller']);
Route::get('get-electric-biller', [PowerController::class, 'power_biller']);
Route::post('validate-biller', [BillsController::class, 'validate_meter']);
Route::get('get-associations-society-biller', [BillsController::class, 'get_associations_society']);
Route::get('get-tax-biller', [BillsController::class, 'get_tax_biller']);
Route::get('get-insurance-biller', [InsuranceController::class, 'get_insurance_biller']);
Route::get('get-ticket-biller', [BillsController::class, 'get_ticket_biller']);



Route::group(['middleware' => ['auth:api', 'acess']], function () {


    //Transfer
    Route::post('get-banks', [TransferController::class, 'get_banks']);
    Route::post('suggested-banks', [TransferController::class, 'suggested_banks']);
    Route::post('recent-transfer', [TransferController::class, 'recent_transfers']);
    Route::post('initiate-bank-transfer', [TransferController::class, 'initiate_transfer']);
    Route::post('initiate-local-transfer', [TransferController::class, 'initiate_local_transfer']);
    Route::post('verify-pin', [TransferController::class, 'verify_pin']);
    Route::post('get-charges', [TransferController::class, 'get_fees']);
    Route::get('get-account', [TransferController::class, 'get_account']);


    //Transactions
    Route::post('transaction-history', [TransactionController::class, 'get_transaction']);
    Route::post('get-date', [TransactionController::class, 'get_transaction_by_date']);
    Route::post('get-single-transaction', [TransactionController::class, 'get_single_transaction']);


    //VAS
    Route::post('buy-airtime', [AirtimeController::class, 'buy_airtime']);
    Route::post('buy-data', [DataController::class, 'buy_data']);
    Route::post('buy-electric', [PowerController::class, 'buy_electric']);
    Route::post('buy-insurance', [DataController::class, 'buy_insurance']);

    //Profile
    Route::get('get-account', [ProfileController::class, 'get_user_account']);








});
