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


class AirtimeController extends Controller
{

    protected $bankOneService, $VTpass;

    public function __construct(BankOneService $bankOneService, VTpassService $VTpass)
    {
        $this->bankOneService = $bankOneService;
        $this->VTpass = $VTpass;

    }


    public function get_airtime_biller()
    {

        $set = Setting::where('id', 1)->first();
        if($set->bills_provider == "vt_pass"){

                $vt_pass_mobile_biller = ['mtn', 'glo', 'airtel', 'etisalat', 'international'];
                return response()->json([
                    'status' => true,
                    'data' => $vt_pass_mobile_biller,
                ], 200);


        }

    }




    public function buy_airtime(Request $request)
    {

        $referenceCode = "EMRVAS-" . random_int(100, 999);
        $sender_account_no = $request->senderAccountNo;
        $amount = $request->Amount;

        $can_transfer = User::where('id', Auth::id())->first()->can_transfer;
        $account_tier = Account::where('user_id', Auth::id())->first()->account_tier;
        $set = Setting::where('id', 1)->first();
        $get_balance = $this->bankOneService->get_balance($sender_account_no);
        $balance = $get_balance['availabe_balance'];
        $final_tranferaable_amount = $amount;
        if($balance < $final_tranferaable_amount){
            return response()->json([
                'status' => false,
                'message' => "Insufficient Funds"
            ], 422);
        }


        if ($set->bill == 0) {
            return response()->json([
                'status' => false,
                'message' => "You can not buy airtime at the moment, Please contact support"
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
            'Amount' => $amount * 100,
            'AccountNumber' => $sender_account_no,
            'RetrievalReference' => $referenceCode,
            'Narration' => "Debit for Airtime",
        ];

        $response = ['ResponseCode' => "00", 'Reference' => "000000000"]; //$this->bankOneService->initiate_customer_debit($data);



        $set = Setting::where('id', 1)->first();
        if($set->bills_provider == "vt_pass"){

            try {

                $apiKey = env('VTPASSAPIKEY');
                $skKey = env('VTPASSSKKEY');
                $requestId = date('YmdHis') . Str::random(4);


                //Deduction

                if($response['ResponseCode'] == "00"){
                    $airtime = $this->VTpass->Pay($requestId, $request->service_id, $amount, $request->phone, $apiKey, $skKey);
                    if ($airtime['status'] == true) {
                        $trx = new Transaction();
                        $trx->trx_ref = $referenceCode;
                        $trx->user_id = Auth::id();
                        $trx->transaction_type = "VAS";
                        $trx->note = "Transaction successful";
                        $trx->vas_type = "airtime";
                        $trx->session_id = $airtime['requestId'];
                        $trx->sender_account_no = $sender_account_no;
                        $trx->debit = $final_tranferaable_amount;
                        $trx->amount = $request->Amount;
                        $trx->status = 1;
                        $trx->save();


                        return response()->json([
                            'status' => true,
                            'message' => 'Airtime Purchase Successful',
                        ]);
                    }
                }

                return response()->json([
                    'status' => false,
                    'message' => "Airtime Purchase failed.",
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
