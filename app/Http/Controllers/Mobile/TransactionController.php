<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Services\BankOneService;
use Barryvdh\DomPDF\Facade\Pdf;
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
           ], 200);



       }else{


           return response()->json([
               'status' => true,
               'data' => $response['message'],
           ], 422);

       }



   }





    public function notification(request $request)
    {

        $message = "Emaar Webhook Notification ===>>>>> \n\n". json_encode($request->all());
        send_notification($message);

    }



    public function downloadReceipt(Request $request)
    {
        $date = now();
        $dateFull = $date->format('d F Y H:i');
        $dateOnly = $date->format('d F Y');

        $pdf = Pdf::loadView('receipt', [
            'date' => $dateFull,
            'dateOnly' => $dateOnly,
            'amount' => '100.00',
            'type' => 'one off payment',
            'beneficiary' => 'TOLULOPE ADEWALE ADEJIMI',
            'beneficiary_account' => '907*****33',
            'beneficiary_bank' => 'PalmPay',
            'sender' => 'TOLULOPE ADEJIMI',
            'sender_account' => '001*****20',
            'sender_bank' => 'Stanbic IBTC Bank',
            'status' => 'successful',
            'narration' => 'TOLULOPE ADEWALE ADEJIMI',
        ]);

        return $pdf->download('transaction_receipt.pdf');
    }

}
