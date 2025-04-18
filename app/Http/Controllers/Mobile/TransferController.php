<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\BankLogo;
use App\Models\Beneficary;
use App\Models\RecentBankDetails;
use App\Models\Setting;
use App\Models\SmsCharge;
use App\Models\Transaction;
use App\Models\User;
use App\Services\BankOneService;
use App\Services\TermiiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TransferController extends Controller
{
    protected $bankOneService;

    public function __construct(BankOneService $bankOneService)
    {
        $this->bankOneService = $bankOneService;
    }


    public function get_banks()
    {

        $response = $this->bankOneService->getCommercialBanks();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);


    }

    public function suggested_banks(request $request)
    {


        $accountNumber = $request->account_number;

        $ck_acc = RecentBankDetails::where('account_number', $accountNumber)->where('user_id', Auth::id())->get()->makeHidden(['id', 'created_at', 'updated_at', 'user_id']) ?? null;
        if ($ck_acc->isNotEmpty()) {

            foreach ($ck_acc as $bank) {
                $bank->bank_logo = url('storage/app/public/bankslogo/' . $bank->bank_logo);
            }


        }


        $possiblePrefixes = [
            substr($accountNumber, 0, 2),  // First 2 digits
            substr($accountNumber, 0, 3),  // First 3 digits
            substr($accountNumber, 0, 4),  // First 4 digits
        ];

        $banks = BankLogo::where(function ($query) use ($possiblePrefixes) {
            foreach ($possiblePrefixes as $prefix) {
                $query->orWhereRaw("JSON_CONTAINS(prefix, ?)", [json_encode($prefix)]);
            }
        })->get()->makeHidden(['id', 'created_at', 'updated_at', 'prefix']);


        if ($banks->isNotEmpty()) {
            foreach ($banks as $bank) {
                $bank->logo = url('storage/app/public/bankslogo/' . $bank->logo);
            }

            return response()->json([
                'success' => true,
                'suggested_banks' => $banks,
                'suggested_account' => $ck_acc ?? []
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => "No matching bank found"
            ]);
        }


    }


    public function recent_transfers(request $request)
    {

        $ck_acc = RecentBankDetails::latest()->where('user_id', Auth::id())->take('10')->get()->makeHidden(['id', 'user_id', 'created_at', 'updated_at']) ?? null;
        if ($ck_acc->isNotEmpty()) {
            foreach ($ck_acc as $bank) {
                $bank->bank_logo = url('storage/app/public/bankslogo/' . $bank->bank_logo);
            }

            return response()->json([
                'status' => true,
                'data' => $ck_acc
            ]);

        }
    }


    public function get_account()
    {

        $account_no = Account::where('user_id', Auth::id())->get()->makeHidden(['created_at', 'updated_at']);
        $all_account = [];
        foreach ($account_no as $data) {
            $account = $data->account_number;
            $all_account[] = $this->bankOneService->get_balance($account);
        }

        $accounts = $all_account;

        return response()->json([
            'success' => true,
            'data' => $accounts
        ]);

    }


    public function get_beneficary()
    {

        $bene = Beneficary::where('user_id', Auth::id())->get()->makeHidden(['created_at', 'updated_at']);
        return response()->json([
            'success' => true,
            'data' => $bene
        ]);

    }

    public function initiate_transfer(request $request)
    {

        $amount = $request->Amount * 100;
        $sender_account_no = $request->senderAccountNo;
        $sender_name = $request->senderName;
        $bank_code = $request->bankCode;
        $receiver_account_no = $request->receiverAccountNo;
        $receiver_name = $request->receiverName;
        $receiver_bank_name = $request->receiverBankName;
        $pin = $request->pin;
        $trxref = "ER" . date('dmhis');


        $can_transfer = User::where('id', Auth::id())->first()->can_transfer;
        $account_tier = Account::where('user_id', Auth::id())->first()->account_tier;
        $set = Setting::where('id', 1)->first();
        $get_balance = $this->bankOneService->get_balance($sender_account_no);
        $balance = $get_balance['availabe_balance'];
        $user_pin = User::where('id', Auth::id())->first()->pin;


        if($request->save_as_beneficiary == true ){


            $ck_bene =  Beneficary::where('acct_no', $sender_account_no)->where('bank_code', $bank_code)->first() ?? null;
            if($ck_bene == null){
                $bene = new Beneficary();
                $bene->user_id = Auth::id();
                $bene->acct_no = $sender_account_no;
                $bene->bank_name = $receiver_bank_name;
                $bene->name = $sender_name;
                $bene->save();
            }

        }



        $final_tranferaable_amount = $request->Amount + $set->transfer_charges;
        if ((int)$balance < $final_tranferaable_amount) {
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
            'PayerAccountNumber' => $sender_account_no,
            'Payer' => $sender_name,
            'ReceiverBankCode' => $bank_code,
            'ReceiverAccountNumber' => $receiver_account_no,
            'ReceiverName' => $receiver_name,
            'TransactionReference' => $trxref,
            'Narration' => $request->narattion ?? "Trf to " . "$receiver_name",
            'NIPSessionID' => $request->session_id,
            'ReceiverBVN' => $request->BVN,
            'ReceiverKYC' => $request->KYC,
        ];

        $response = $this->bankOneService->initiate_bank_transfer($data);


        $status = $response['Status'] ?? null;

      // $status = true;

       // $response = ['SessionID' => "0000", "UniqueIdentifier" => "445555" ];


        if ($status == true) {

            $trx = new Transaction();
            $trx->trx_ref = $trxref;
            $trx->user_id = Auth::id();
            $trx->receiver_bank_code = $bank_code;
            $trx->receiver_bank_name = $receiver_bank_name;
            $trx->receiver_account_no = $receiver_account_no;
            $trx->receiver_name = $receiver_name;
            $trx->sender_name = $sender_name;
            $trx->transaction_type = "Bank_Transfer";
            $trx->note = "Transaction successful";
            $trx->narattion = $request->narattion ?? "Trf to " . "$receiver_name";
            $trx->session_id = $response['SessionID'];
            $trx->UniqueIdentifier = $response['UniqueIdentifier'];
            $trx->sender_account_no = $sender_account_no;
            $trx->fees = $set->transfer_charges;
            $trx->debit = $final_tranferaable_amount;
            $trx->amount = $request->Amount;
            $trx->status = 1;
            $trx->save();

//
            try {

                $get_balance = $this->bankOneService->get_balance($sender_account_no);
                $balance = $get_balance['availabe_balance'];

                $get_account = $sender_account_no;
                $acct = str_repeat('*', strlen($get_account) - 4) . substr($get_account, -4);
                $debit = "N" . number_format($request->Amount, 2);
                $desc = $request->narattion ?? "Trf to " . "$receiver_name";
                $ref = $response['SessionID'];
                $date = \Carbon\Carbon::now()->format('d/m/Y h:i A');
                $bal = "N" . number_format($balance, 2);
                $phone = Auth::user()->phone;
                $message = "Acc: $acct\nDEBIT: $debit\nDesc: $desc\nRef: $ref\nDate: $date\nBal: $bal";


                $set = Setting::where('id', 1)->first();
                if ($set->sms_provider == "africa") {
                    $send_sms = send_sms_africa($phone, $message);
                } else {
                    $smsService = new TermiiService();
                    $send_sms = send_sms_termii($phone, $message);
                    $data = $send_sms->getData(true); // as array

                    if ($data['code'] === 'ok') {
                        User::where('id', Auth::id())->increment('sms_charge', 4);
                        $sms = new SmsCharge();
                        $sms->cost = 4;
                        $sms->channel = "Termii";
                        $sms->message = $message;
                        $sms->message_id = $data['message_id'];
                        $sms->save();

                    }


                }


            } catch (\Exception $e) {
                $message = "SMS DEBIT  Error ====>>>" . $e->getMessage();
                send_notification($message);
            }


            $bank_logo = BankLogo::where('name', $receiver_bank_name)->first()->logo ?? null;
            $rec = new RecentBankDetails();
            $rec->user_id = Auth::id();
            $rec->account_number = $receiver_account_no;
            $rec->account_name = $receiver_name;
            $rec->bank_code = $bank_code;
            $rec->bank_logo = $bank_logo;
            $rec->bank_name = $receiver_bank_name;
            $rec->save();


            return response()->json([
                'status' => true,
                'message' => "Transaction Successful",
                'InstrumentNo' => $trxref

            ], 200);


        } elseif ($status == false) {

            $trx = new Transaction();
            $trx->trx_ref = $trxref;
            $trx->user_id = Auth::id();
            $trx->receiver_bank_code = $bank_code;
            $trx->receiver_bank_name = $receiver_bank_name;
            $trx->receiver_account_no = $receiver_account_no;
            $trx->receiver_name = $receiver_name;
            $trx->sender_name = $sender_name;
            $trx->transaction_type = "Bank_Transfer";
            $trx->note = $response['Message'];
            $trx->sender_account_no = $sender_account_no;
            $trx->fees = 0;
            $trx->debit = $final_tranferaable_amount;
            $trx->amount = $request->Amount;
            $trx->status = 3;
            $trx->save();

            return response()->json([
                'status' => false,
                'message' => "Transaction Failed | " . $response['Message']
            ], 422);


        } else {

            return response()->json([
                'status' => false,
                'message' => "Something went wrong"
            ], 422);


        }


    }


    public function name_inquary(request $request)
    {

        $account_no = $request->accountNumber;
        $code = $request->bankCode;

        $response = $this->bankOneService->name_enquiry($code, $account_no);

        $get_limit = Setting::where('id', 1)->first()->daily_limit ?? 0;
        $today_limit = Transaction::where([
            'user_id' => Auth::id(),
            'status' => 1,
            'transaction_type' => "Bank_Transfer"
        ])->orWhere('transaction_type', 'VAS')->sum('amount');
        $limit = $get_limit - $today_limit ?? 0;


        if ($response['codes'] == 0) {

            return response()->json([
                'status' => false,
                'message' => $response['message']
            ], 422);

        } else {

            return response()->json([
                'status' => true,
                'name' => $response['name'],
                'session_id' => $response['session_id'],
                'BVN' => $response['BVN'],
                'KYC' => $response['KYC'],
                'daily_limit' => $limit
            ]);
        }


    }

    public function verify_pin(request $request)
    {

        $user_pin = User::where('id', Auth::id())->first()->pin;
        $pin = $request->pin;

        if (Hash::check($pin, $user_pin) == false) {
            return response()->json([
                'status' => false,
                'message' => "Incorrect Pin"
            ], 422);
        }


        return response()->json([
            'status' => true,
            'message' => "Pin correct"
        ], 200);


    }


    public function get_fees(request $request)
    {

        $type = $request->type;
        $set = Setting::where('id', 1)->first();
        if ($type == "interbank") {
            return response()->json([
                'status' => true,
                'fee' => $set->transfer_charges ?? 0
            ], 200);
        } elseif ($type == "cable") {

            return response()->json([
                'status' => true,
                'fee' => $set->cable_charges ?? 0
            ], 200);
        }


    }


    public function initiate_local_transfer(request $request)
    {


        $amount = $request->Amount * 100;
        $sender_account_no = $request->senderAccountNo;
        $sender_name = $request->senderName;
        $receiver_account_no = $request->receiverAccountNo;
        $receiver_name = $request->receiverName;
        $receiver_bank_name = $request->receiverBankName;
        $pin = $request->pin;
        $trxref = "ER" . date('dmhis');



        $can_transfer = User::where('id', Auth::id())->first()->can_transfer;
        $account_tier = Account::where('user_id', Auth::id())->first()->account_tier;
        $set = Setting::where('id', 1)->first();
        $get_balance = $this->bankOneService->get_balance($sender_account_no);
        $balance = $get_balance['availabe_balance'];
        $user_pin = User::where('id', Auth::id())->first()->pin;




        $final_tranferaable_amount = $request->Amount;
        if ((int)$balance < $final_tranferaable_amount) {
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
            'FromAccountNumber' => $sender_account_no,
            'Payer' => $sender_name,
            'ToAccountNumber' => $receiver_account_no,
            'RetrievalReference' => $trxref,
            'Narration' => $request->narattion ?? "Trf to " . "$receiver_name",
        ];

        $response = $this->bankOneService->initiate_local_transfer($data);


        $status = $response['Status'] ?? null;


        if ($status == true) {

            $trx = new Transaction();
            $trx->trx_ref = $trxref;
            $trx->user_id = Auth::id();
            $trx->receiver_bank_code = "100962";
            $trx->receiver_bank_name = "EMAAR MICROFINANCEBANK";
            $trx->receiver_account_no = $receiver_account_no;
            $trx->receiver_name = $receiver_name;
            $trx->sender_name = Auth::user()->first_name . " " . Auth::user()->last_name . " " . Auth::user()->other_name;
            $trx->transaction_type = "Local_Bank_Transfer";
            $trx->note = "Transaction successful";
            $trx->session_id = $response['SessionID'];
            $trx->sender_account_no = $sender_account_no;
            $trx->fees = 0;
            $trx->debit = $final_tranferaable_amount;
            $trx->amount = $request->Amount;
            $trx->status = 1;
            $trx->save();


            $bank_logo = BankLogo::where('name', $receiver_bank_name)->first()->logo ?? null;
            $rec = new RecentBankDetails();
            $rec->user_id = Auth::id();
            $rec->account_number = $receiver_account_no;
            $rec->account_name = $receiver_name;
            $rec->bank_code = "100962";
            $rec->bank_logo = $bank_logo;
            $rec->bank_name = "EMAAR MICROFINANCEBANK";
            $rec->save();


            return response()->json([
                'status' => true,
                'message' => "Transaction Successful",
                'trx_id' => $trx->id

            ], 200);


        } elseif ($status == false) {

            $trx = new Transaction();
            $trx->trx_ref = $trxref;
            $trx->user_id = Auth::id();
            $trx->receiver_bank_code = "100962";
            $trx->receiver_bank_name = $receiver_bank_name;
            $trx->receiver_account_no = $receiver_account_no;
            $trx->receiver_name = $receiver_name;
            $trx->sender_name = $sender_name;
            $trx->transaction_type = "Local_Bank_Transfer";
            $trx->note = $response['ResponseMessage'];
            $trx->sender_account_no = $sender_account_no;
            $trx->fees = 0;
            $trx->debit = $final_tranferaable_amount;
            $trx->amount = $request->Amount;
            $trx->status = 3;
            $trx->save();

            return response()->json([
                'status' => false,
                'message' => "Transaction Failed"
            ], 422);


        } else {

            return response()->json([
                'status' => false,
                'message' => "Something went wrong"
            ], 422);


        }


    }


}
