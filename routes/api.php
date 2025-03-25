<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TerminalController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Mobile\Auth\LoginController;
use App\Http\Controllers\Mobile\Auth\RegisterController;
use App\Http\Controllers\Mobile\Face\FaceRecognitionController;
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




Route::group(['middleware' => ['auth:api', 'acess']], function () {

    Route::post('get-banks', [TransferController::class, 'get_banks']);
    Route::post('suggested-banks', [TransferController::class, 'suggested_banks']);



});
