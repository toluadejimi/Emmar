<?php

namespace App\Http\Controllers\Mobile\VAS;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Power;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Services\BankOneService;
use App\Services\VTPassService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PowerController extends Controller
{
    protected $bankOneService, $VTpass;

    public function __construct(BankOneService $bankOneService, VTPassService $VTpass)
    {
        $this->bankOneService = $bankOneService;
        $this->VTpass = $VTpass;

    }


    public function power_biller()
   {
       $set = Setting::where('id', 1)->first();
       if ($set->bills_provider == "vt_pass") {

           $get_billers = Power::all()->makeHidden(['sn_code', 'name', 'vas_provider']);

           return response()->json([
               'status' => true,
               'data' => $get_billers
           ]);

       }



   }


   public function buy_electric(request $request)
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
                   $electric = $this->VTpass->Pay($requestId, $request->service_id, $request->variation_code, $request->biller_code, $amount, $request->phone, $apiKey, $skKey);
                   if ($electric['status'] == true) {
                       $trx = new Transaction();
                       $trx->trx_ref = $referenceCode;
                       $trx->user_id = Auth::id();
                       $trx->transaction_type = "VAS";
                       $trx->fees = $set->eletric_charge;
                       $trx->vas_type = "electric";
                       $trx->note = "Token: ".$electric['token'];
                       $trx->session_id = $electric['requestId'];
                       $trx->sender_account_no = $sender_account_no;
                       $trx->debit = $final_tranferaable_amount;
                       $trx->amount = $request->Amount;
                       $trx->status = 1;
                       $trx->save();


                       return response()->json([
                           'status' => true,
                           'data' => $electric,
                       ]);
                   }
               }

               return response()->json([
                   'status' => false,
                   'message' => "Electric Token Purchase failed.",
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
