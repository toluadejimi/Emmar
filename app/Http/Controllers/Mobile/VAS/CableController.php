<?php

namespace App\Http\Controllers\Mobile\VAS;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Services\BankOneService;
use App\Services\VTPassService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CableController extends Controller
{

    protected $bankOneService, $VTpass;

    public function __construct(BankOneService $bankOneService, VTpassService $VTpass)
    {
        $this->bankOneService = $bankOneService;
        $this->VTpass = $VTpass;

    }

    public function get_cable_biller()
    {

        $set = Setting::where('id', 1)->first();
        if($set->bills_provider == "vt_pass") {
            try {

                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://vtpass.com/api/service-variations?serviceID=dstv');
                $response = $request->getBody();
                $result = json_decode($response);
                $dstv = $result->content->variations;

                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://vtpass.com/api/service-variations?serviceID=gotv');
                $response = $request->getBody();
                $result = json_decode($response);
                $gotv = $result->content->variations;

                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://vtpass.com/api/service-variations?serviceID=startimes');
                $response = $request->getBody();
                $result = json_decode($response);
                $startimes = $result->content->variations;

                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://vtpass.com/api/service-variations?serviceID=showmax');
                $response = $request->getBody();
                $result = json_decode($response);
                $showmax = $result->content->variations;


                return response()->json([
                    'status' => true,
                    'dstv' => $dstv,
                    'gotv' => $gotv,
                    'startimes' => $startimes,
                    'showmax' => $showmax,
                ], 200);

            } catch (\Exception$th) {
                return $th->getMessage();
            }
        }

    }


    public function validate_cable(request $request)
    {

        $biller_code = $request->biller_code;
        $service_id = $request->service_id;

        $response = $this->VTpass->ValidateCable($biller_code, $service_id);

        if($response['status'] == 1){

            return response()->json([

                'status' => true,
                'customer_name' => $response['data']->Customer_Name ?? null,
                'cable_status' => $response['data']->Status ?? null,
                'due_date' => $response['data']->Due_Date ?? null,
                'customer_no' => $response['data']->Customer_Number ?? null,
                'customer_type' => $response['data']->Customer_Type ?? null,
                'current_bouquet' => $response['data']->Current_Bouquet ?? null,
                'current_bouquet_code' => $response['data']->Current_Bouquet_Code ?? null,
                'renewal_amount' => $response['data']->Renewal_Amount ?? null
                ]);

        }else{

            return response()->json([
                'status' => false,
                'message' => $response['message'],
            ], 422);


        }



    }


    public function buy_cable(request $request)
    {

        $referenceCode = "EMRVAS-" . random_int(100, 999);
        $sender_account_no = $request->senderAccountNo;
        $amount = $request->Amount;

        $can_transfer = User::where('id', Auth::id())->first()->can_transfer;
        $account_tier = Account::where('user_id', Auth::id())->first()->account_tier;
        $set = Setting::where('id', 1)->first();
        $get_balance = $this->bankOneService->get_balance($sender_account_no);
        $balance = $get_balance['availabe_balance'];

        $final_tranferaable_amount = $amount + $set->eletric_charge;

        if($balance < $final_tranferaable_amount){
            return response()->json([
                'status' => false,
                'message' => "Insufficient Funds"
            ], 422);
        }


        if ($set->bill == 0) {
            return response()->json([
                'status' => false,
                'message' => "You can not buy data at the moment, Please contact support"
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
            'Amount' => $final_tranferaable_amount * 100,
            'AccountNumber' => $sender_account_no,
            'RetrievalReference' => $referenceCode,
            'Narration' => "Debit for Data",
        ];

        $response = ['ResponseCode' => "00", 'Reference' => "000000000"]; //$this->bankOneService->initiate_customer_debit($data);



        $set = Setting::where('id', 1)->first();
        if($set->bills_provider == "vt_pass"){

            try {

                $apiKey = env('VTPASSAPIKEY');
                $skKey = env('VTPASSSKKEY');
                $requestId = date('YmdHis') . Str::random(4);


                if($response['ResponseCode'] == "00"){
                    $cable = $this->VTpass->Pay($requestId, $request->service_id, $request->variation_code, $request->biller_code, $amount, $request->phone, $apiKey, $skKey);
                    if ($cable['status'] == true) {
                        $trx = new Transaction();
                        $trx->trx_ref = $referenceCode;
                        $trx->user_id = Auth::id();
                        $trx->transaction_type = "VAS";
                        $trx->fees = $set->eletric_charge;
                        $trx->vas_type = "cable";
                        $trx->note = "Transaction Successful";
                        $trx->session_id = $cable['requestId'];
                        $trx->sender_account_no = $sender_account_no;
                        $trx->debit = $final_tranferaable_amount;
                        $trx->amount = $request->Amount;
                        $trx->status = 1;
                        $trx->save();


                        return response()->json([
                            'status' => true,
                            'message' => "Transaction Successful",
                        ]);
                    }
                }

                return response()->json([
                    'status' => false,
                    'message' => "Cable Purchase failed.",
                ], 200);

            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'An unexpected error occurred: ' . $e->getMessage(),
                ], 500);
            }

        }




    }


}
