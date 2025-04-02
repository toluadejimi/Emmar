<?php


use App\Http\Controllers\Mobile\Auth\LoginController;
use App\Http\Controllers\Mobile\Auth\RegisterController;
use App\Http\Controllers\Mobile\Face\FaceRecognitionController;
use App\Http\Controllers\Mobile\TransactionController;
use App\Http\Controllers\Mobile\TransferController;
use Illuminate\Support\Facades\Route;



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




Route::group(['middleware' => ['auth:api', 'acess']], function () {

    Route::post('get-banks', [TransferController::class, 'get_banks']);
    Route::post('suggested-banks', [TransferController::class, 'suggested_banks']);
    Route::post('recent-transfer', [TransferController::class, 'recent_transfers']);
    Route::post('initiate-bank-transfer', [TransferController::class, 'initiate_transfer']);
    Route::post('verify-pin', [TransferController::class, 'verify_pin']);
    Route::post('get-charges', [TransferController::class, 'get_fees']);
    Route::get('get-account', [TransferController::class, 'get_account']);


    //Transactions
    Route::post('transaction-history', [TransactionController::class, 'get_transaction']);
    Route::post('get-date', [TransactionController::class, 'get_transaction_by_date']);



});
