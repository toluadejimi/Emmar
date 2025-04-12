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

class DataController extends Controller
{

    protected $bankOneService, $VTpass;

    public function __construct(BankOneService $bankOneService, VTpassService $VTpass)
    {
        $this->bankOneService = $bankOneService;
        $this->VTpass = $VTpass;

    }

    public function get_data()
    {

        $set = Setting::where('id', 1)->first();
        if($set->bills_provider == "vt_pass"){
            try {

                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://vtpass.com/api/service-variations?serviceID=mtn-data');
                $response = $request->getBody();
                $result = json_decode($response);
                $get_mtn_network = $result->content->variations;

                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://vtpass.com/api/service-variations?serviceID=glo-data');
                $response = $request->getBody();
                $result = json_decode($response);
                $get_glo_network = $result->content->variations;

                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://vtpass.com/api/service-variations?serviceID=airtel-data');
                $response = $request->getBody();
                $result = json_decode($response);
                $get_airtel_network = $result->content->variations;

                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://vtpass.com/api/service-variations?serviceID=etisalat-data');
                $response = $request->getBody();
                $result = json_decode($response);
                $get_9mobile_network = $result->content->variations;

                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://vtpass.com/api/service-variations?serviceID=smile-direct');
                $response = $request->getBody();
                $result = json_decode($response);
                $get_smile_network = $result->content->variations;

                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://vtpass.com/api/service-variations?serviceID=spectranet');
                $response = $request->getBody();
                $result = json_decode($response);
                $get_spectranet_network = $result->content->variations;

                return response()->json([
                    'status' => true,
                    'mtn_data' => $get_mtn_network,
                    'glo_data' => $get_glo_network,
                    'airtel_data' => $get_airtel_network,
                    '9mobile_data' => $get_9mobile_network,
                    'smile_data' => $get_smile_network,
                    'spectranet_data' => $get_spectranet_network,
                ], 200);

            } catch (\Exception$th) {
                return $th->getMessage();
            }
        }


    }

    public function buy_data(Request $request)
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
            'Amount' => $amount * 100,
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


                //Deduction

                if($response['ResponseCode'] == "00"){
                    $airtime = $this->VTpass->Pay($requestId, $request->service_id, $request->variation_code, $request->biller_code, $amount, $request->phone, $apiKey, $skKey);
                    if ($airtime['status'] == true) {
                        $trx = new Transaction();
                        $trx->trx_ref = $referenceCode;
                        $trx->user_id = Auth::id();
                        $trx->transaction_type = "VAS";
                        $trx->vas_type = "data";
                        $trx->note = "Transaction successful";
                        $trx->session_id = $airtime['requestId'];
                        $trx->sender_account_no = $sender_account_no;
                        $trx->debit = $final_tranferaable_amount;
                        $trx->amount = $request->Amount;
                        $trx->status = 1;
                        $trx->save();


                        return response()->json([
                            'status' => true,
                            'message' => 'Data Purchase Successful',
                        ]);
                    }
                }

                return response()->json([
                    'status' => false,
                    'message' => "Data Purchase failed.",
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
