<?php

namespace App\Http\Controllers\Mobile\VAS;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Services\BankOneService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AirtimeController extends Controller
{


    protected $bankOneService;

    public function __construct(BankOneService $bankOneService)
    {
        $this->bankOneService = $bankOneService;
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

        $trxref = "VAS" . date('dmhis');
        $sender_account_no = $request->senderAccountNo;
        $amount = $request->Amount;
        $request_id = date('YmdHis') . Str::random(4);
        $referenceCode = "ENK-" . random_int(1000000, 999999999);
        $serviceid = $request->service_id;
        $phone = $request->phone;


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




        try {
            $user = Auth::user();
            $request->validate([
                'service_id' => 'required|string',
                'amount' => 'required|numeric|min:100',
                'phone' => 'required|string',
            ]);

            $apiKey = env('VTPASSAPIKEY');
            $skKey = env('VTPASSSKKEY');
            $requestId = date('YmdHis') . Str::random(4);
            $referenceCode = "EMRVAS-" . random_int(1000000, 999999999);


            //Deduction

            $data = [
                'Amount' => $amount * 100,
                'PayerAccountNumber' => $sender_account_no,
                'TransactionReference' => $trxref,
                'Narration' => "Debit for Airtime",
            ];

            $response = $this->bankOneService->initiate_customer_debit($data);

            if($response['ResponseCode'] == "00"){
                $response = $this->callVTPassApi($requestId, $request->service_id, $amount, $request->phone, $apiKey, $skKey);
                send_notification("VAS AIRTIME Response: " . json_encode($response));
                if ($response->response_description === 'TRANSACTION SUCCESSFUL') {


                    $trx = new Transaction();
                    $trx->trx_ref = $trxref;
                    $trx->user_id = Auth::id();
                    $trx->transaction_type = "VAS";
                    $trx->note = "Transaction successful";
                    $trx->session_id = $response['Reference'];
                    $trx->sender_account_no = $sender_account_no;
                    $trx->fees = $set->transfer_charges;
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
                'status' => $this->failed,
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

}
