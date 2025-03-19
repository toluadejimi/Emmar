<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\PosLog;
use App\Models\Terminal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StoredataController extends Controller
{

    public function store_user(request $request)
    {

        $user = new User();
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->role = $request->role;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->password = bcrypt($request->password);
        $user->hos_no = $request->hos_no;
        $user->gender = $request->gender;
        $user->address_line1 = $request->address_line1;
        $user->state = $request->state;
        $user->lga = $request->lga;
        $user->save();

        return response([
            'status' => true,
            'message'=>'data stored'
        ], 200);


    }

    public function store_terminal(request $request)
    {
        $term = new Terminal();
        $term->tid = $request->tid;
        $term->user_id = $request->user_id;
        $term->bank_id = $request->bank_id;
        $term->ip = $request->ip;
        $term->port = $request->port;
        $term->ssl = $request->ssl;
        $term->compKey1 = $request->compKey1;
        $term->compKey2 = $request->compKey2;
        $term->baseUrl = "http://etopagency.com:9001/";
        $term->logoUrl = $request->logoUrl;
        $term->serialNumber = $request->serialNumber;
        $term->merchantName = $request->merchantName;
        $term->mid = $request->mid;
        $term->merchantaddress = $request->merchantaddress;
        $term->pin = $request->pin;
        $term->save();

        return response([
            'status' => true,
            'message'=>'data stored'
        ], 200);


    }

    public function update_terminal(request $request)
    {

        Terminal::where('serialNumber', $request->serialNumber)->update(['user_id' => $request->user_id,'bank_id' => $request->bank_id, ]);
        return response([
            'status' => true,
            'message'=>'data updated'
        ], 200);


    }

    public function update_user(request $request)
    {

        User::where('id', $request->id())->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'bank_id' => $request->bank_id,
            'phone' => $request->phone,
        ]);


        return response([
            'status' => true,
            'message'=>'data updated'
        ], 200);


    }

    public function store_transaction(request $request)
    {

        $current_time = time();
        $one_hour_later = $current_time + 3600; // 3600 seconds = 1 hour
        $created_at = date('Y-m-d H:i:s', $one_hour_later);


        $trasnaction = new PosLog();
        $trasnaction->RRN = $request->RRN;
        $trasnaction->STAN = $request->STAN;
        $trasnaction->accountBalance = $request->accountBalance;
        $trasnaction->acquiringInstitutionIdCode = $request->acquiringInstitutionIdCode;
        $trasnaction->authCode = $request->authCode;
        $trasnaction->cardCardSequenceNum = $request->cardCardSequenceNum;
        $trasnaction->cardExpireData = $request->cardExpireData;
        $trasnaction->forwardingInstCode = $request->forwardingInstCode;
        $trasnaction->merchantNo = $request->merchantNo;
        $trasnaction->amount = $request->amount;
        $trasnaction->accountType = $request->accountType;
        $trasnaction->tid = $request->tid;
        $trasnaction->merchantName = $request->merchantName;
        $trasnaction->pan = $request->pan;
        $trasnaction->pinBlock = $request->pinBlock;
        $trasnaction->receiptNumber = $request->receiptNumber;
        $trasnaction->respCode = $request->respCode;
        $trasnaction->responseMessage = $request->responseMessage;
        $trasnaction->status = $request->status;
        $trasnaction->successResponse = $request->successResponse;
        $trasnaction->systemTraceAuditNo = $request->systemTraceAuditNo;
        $trasnaction->terminalId = $request->terminalId;
        $trasnaction->transactionDate = $request->transactionDate;
        $trasnaction->transactionDateTime = $request->transactionDateTime;
        $trasnaction->transactionTime = $request->transactionTime;
        $trasnaction->transactionType = $request->transactionType;
        $trasnaction->cardName = $request->cardName;
        $trasnaction->SerialNo = $request->SerialNo;
        $trasnaction->createdAt= $created_at;
        $trasnaction->updatedAt= $created_at;
        $trasnaction->user_id= $request->user_id;
        $trasnaction->save();

        return response([
            'status' => true,
            'message'=>'data stored'
        ], 200);


    }


    public function create_bank(request $request)
    {
        $bank = new Bank();
        $bank->name = $request->name;
        $bank->email = $request->email;
        $bank->image = $request->image;

        $bank->save();

        $usr = new User();
        $usr->first_name = $request->name;
        $usr->last_name = $request->name;
        $usr->email = $request->email;
        $usr->role = 3;
        $usr->bank_id = $bank->id;
        $usr->password = bcrypt('123456');
        $usr->save();

        return response([
            'status' => true,
            'message'=>'data stored'
        ], 200);


    }

    public function update_bank(request $request)
    {
        Bank::where('id', $request->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'image' => $request->image,
        ]);

        return response([
            'status' => true,
            'message'=>'successful'
        ], 200);


    }


    public function delete_bank(request $request)
    {
        Bank::where('id', $request->id)->delete();
        return response([
            'status' => true,
            'message'=>'successful'
        ], 200);





    }

    public function delete_user(request $request)
    {
        User::where('id', $request->id)->delete();
        return response([
            'status' => true,
            'message'=>'successful'
        ], 200);





    }



    public function store_pos_transaction(request $request)
    {
        $current_time = time();
        $one_hour_later = $current_time + 3600; // 3600 seconds = 1 hour
        $created_at = date('Y-m-d H:i:s', $one_hour_later);

        $trasnaction = new InstantSettlement();
        $trasnaction->RRN = $request->RRN;
        $trasnaction->cardExpireData = $request->cardExpireData;
        $trasnaction->merchantNo = $request->merchantNo;
        $trasnaction->amount = $request->amount;
        $trasnaction->accountType = $request->accountType;
        $trasnaction->tid = $request->tid;
        $trasnaction->merchantName = $request->merchantName;
        $trasnaction->pan = $request->pan;
        $trasnaction->pinBlock = $request->pinBlock;
        $trasnaction->receiptNumber = $request->receiptNumber;
        $trasnaction->respCode = $request->respCode;
        $trasnaction->responseMessage = $request->responseMessage;
        $trasnaction->status = $request->status;
        $trasnaction->successResponse = $request->successResponse;
        $trasnaction->systemTraceAuditNo = $request->systemTraceAuditNo;
        $trasnaction->terminalId = $request->terminalId;
        $trasnaction->transactionDate = $request->transactionDate;
        $trasnaction->transactionDateTime = $request->transactionDateTime;
        $trasnaction->transactionTime = $request->transactionTime;
        $trasnaction->transactionType = $request->transactionType;
        $trasnaction->cardName = $request->cardName;
        $trasnaction->SerialNo = $request->SerialNo;
        $trasnaction->createdAt= $created_at;
        $trasnaction->updatedAt= $created_at;
        $trasnaction->user_id= $request->user_id;
        $trasnaction->save();

        return response([
            'status' => true,
            'message'=>'data stored'
        ], 200);


    }









}
