<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Services\BankOneService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    protected $bankOneService;

    public function __construct(BankOneService $bankOneService)
    {
        $this->bankOneService = $bankOneService;
    }
   public function get_transaction(request $request)
   {




       $accountNumber = $request->account_number;
       $from = $request->from;
       $to = $request->to;

       $response = $this->bankOneService->getTransactions($accountNumber, $from, $to);


       if($response['status'] == 0){
           return response()->json([
               'status' => false,
               'message' => $response['message'],
           ], 422);
       }else{


           return response()->json([
               'status' => true,
               'data' => $response['message'],
           ], 422);

       }



   }


    public function notification(request $request)
    {

        $message = "Emaar Webhook Notification ===>>>>> \n\n". json_encode($request->all);
        send_notification($message);

    }
}
