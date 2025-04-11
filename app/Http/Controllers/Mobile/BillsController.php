<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Setting;
use App\Models\User;
use App\Services\BankOneService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillsController extends Controller
{
    protected $bankOneService;

    public function __construct(BankOneService $bankOneService)
    {
        $this->bankOneService = $bankOneService;
    }


    public function get_airtime_biller()
    {

        $response = $this->bankOneService->get_airtime_biller();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);


    }

    public function get_data_biller()
    {

        $response = $this->bankOneService->get_data_biller();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);


    }


    public function get_cable_biller()
    {

        $response = $this->bankOneService->get_cable_biller();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);


    }

    public function get_education_biller()
    {

        $response = $this->bankOneService->get_exams_biller();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);


    }


    public function get_waste_biller()
    {

        $response = $this->bankOneService->get_waste_biller();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);


    }


    public function get_betting_biller()
    {

        $response = $this->bankOneService->get_betting_biller();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);


    }


    public function get_electric_biller()
    {

        $response = $this->bankOneService->get_electric_biller();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);


    }

    public function get_associations_society()
    {

        $response = $this->bankOneService->get_associations_sociery_biller();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);


    }


    public function get_tax_biller()
    {

        $response = $this->bankOneService->get_tax_biller();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);


    }


    public function buy_airtime(request $request)
    {

        $amount = $request->amount;
        $biller_id = $request->biller_id;
        $biller_name = $request->biller_name;
        $biller_category_id = $request->biller_category_id;
        $biller_category_name = $request->biller_category_name;
        $customer_phone = $request->customer_phone;
        $customer_email = Auth::user()->email;
        $account_number = $request->account_number;


        $account_tier = Account::where('user_id', Auth::id())->first()->account_tier;
        $set = Setting::where('id', 1)->first();
        $get_balance = $this->bankOneService->get_balance($account_number);
        $balance = $get_balance['availabe_balance'];


        if ($account_tier == "Tier_1") {
            $limit = $set->tier_1_limit;
        } elseif ($account_tier == "Tier_2")
            $limit = $set->tier_2_limit;
        else {
            $limit = $set->tier_3_limit;
        }


        if ($request->amount > $limit) {
            return response()->json([
                'status' => false,
                'message' => "Upgrade your account to transfer more, Please contact support"
            ], 422);
        }



        if($balance < $amount){
            return response()->json([
                'status' => false,
                'message' => "Insufficient Funds"
            ], 422);
        }




        $data = [
            'Amount' => $amount,
            'BillerID' => $biller_id,
            'BillerName' => $biller_name,
            'BillerCategoryID' => $biller_category_id,
            'BillerCategoryName' => $biller_category_name,
            'CustomerPhone' => $customer_phone,
            'CustomerEmail' => $customer_email,
            'AccountNumber' => $account_number,
        ];

        $response = $this->bankOneService->pay_bills($data);


    }


    public function pay_bill(request $request)
    {

        $amount = $request->amount;
        $biller_id = $request->biller_id;
        $biller_name = $request->biller_name;
        $biller_category_id = $request->biller_category_id;
        $biller_category_name = $request->biller_category_name;
        $customer_phone = $request->customer_phone;
        $customer_email = $request->customer_email;
        $account_number = $request->account_number;
        $customer_name = $request->customer_name;



        $can_transfer = User::where('id', Auth::id())->first()->can_transfer;
        $account_tier = Account::where('user_id', Auth::id())->first()->account_tier;
        $set = Setting::where('id', 1)->first();
        $get_balance = $this->bankOneService->get_balance($account_number);
        $balance = $get_balance['availabe_balance'];
        $user_pin = User::where('id', Auth::id())->first()->pin;


        $final_tranferaable_amount = $request->Amount + $set->transfer_charges;
        if($balance < $final_tranferaable_amount){
            return response()->json([
                'status' => false,
                'message' => "Insufficient Funds"
            ], 422);
        }


        if ($set->bank_transfer == 0) {
            return response()->json([
                'status' => false,
                'message' => "You can not transfer at the moment, Please contact support"
            ], 422);
        }




        if ($can_transfer == 0) {
            return response()->json([
                'status' => false,
                'message' => "You have been banned from Bank Transfer, Please contact support"
            ], 422);
        }

        if ($account_tier == "Tier_1") {
            $limit = $set->tier_1_limit;
        } elseif ($account_tier == "Tier_2")
            $limit = $set->tier_2_limit;
        else {
            $limit = $set->tier_3_limit;
        }


        if ($request->amount > $limit) {
            return response()->json([
                'status' => false,
                'message' => "Upgrade your account to transfer more, Please contact support"
            ], 422);
        }






        $data = [
            'Amount' => $amount,
            'BillerID' => $biller_id,
            'BillerName' => $biller_name,
            'BillerCategoryID' => $biller_category_id,
            'BillerCategoryName' => $biller_category_name,
            'CustomerPhone' => $customer_phone,
            'CustomerEmail' => $customer_email,
            'AccountNumber' => $account_number,
            'CustomerName' => $customer_name,
        ];

        $response = $this->bankOneService->pay_bills($data);


    }


}
