<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
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
           ], 200);

       }



   }
   public function get_single_transaction(request $request)
   {



       $id = $request->InstrumentNo;

       $trx = Transaction::where('trx_ref', $id)->first() ?? null;
       if($trx == null){
           return response()->json([
               'status' => false,
               'message' => "Transaction not found",
           ], 401);
       }

       $get_date = $trx->created_at;
       $date = \Carbon\Carbon::parse($get_date)->format('Y-m-d');

       $response = $this->bankOneService->get_single_transaction($id, $date);


       if($response['status'] == 0){
           return response()->json([
               'status' => false,
               'message' => $response['message'],
           ], 200);

       }else{


           return response()->json([
               'status' => true,
               'data' => $response['message'],
           ], 200);

       }



   }





    public function notification(request $request)
    {

        $message = "Emaar Webhook Notification ===>>>>> \n\n". json_encode($request->all());
        send_notification($message);

    }



    public function downloadReceipt(Request $request)
    {

        $id = $request->InstrumentNo;

        $trx = Transaction::where('trx_ref', $id)->first() ?? null;
        if($trx == null){
            return response()->json([
                'status' => false,
                'message' => "Transaction not found",
            ], 401);
        }


        $date = $trx->created_at;
        $dateFull = $date->format('d F Y H:i');
        $dateOnly = $date->format('d F Y');

        if($trx->transaction_type == "Bank_Transfer"){
            $type = "Bank Transfer";
        }elseif($trx->transaction_type == "VAS" && $trx->vas_type == "airtime"){
            $type = "Airtime";
        }else{
            $type = "BILLS";
        }

        if($trx->status == 1){
            $status = "Successful";
        }elseif($trx->status == 0){
            $status = "Pending";
        }else{
            $status = "Failed";
        }

        $pdf = Pdf::loadView('receipt', [
            'date' => $dateFull,
            'dateOnly' => $dateOnly,
            'amount' => number_format($trx->amount, 2),
            'type' => $type,
            'beneficiary' => $trx->receiver_name,
            'beneficiary_account' => $trx->receiver_account_no,
            'beneficiary_bank' => $trx->receiver_bank_name,
            'sender' => $trx->sender_name,
            'status' => $status,
            'narration' => $trx->narattion,
        ]);

        return $pdf->download('transaction_receipt.pdf');
    }

}
