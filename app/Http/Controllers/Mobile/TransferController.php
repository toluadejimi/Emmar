<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\BankLogo;
use App\Models\RecentBankDetails;
use App\Models\Setting;
use App\Models\User;
use App\Services\BankOneService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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


    public function initiate_transfer(request $request)
    {


        $can_transfer = User::where('id', Auth::id())->first()->can_transfer;
        $account_tier = Account::where('user_id', Auth::id())->first()->account_tier;
        $set = Setting::where('id', 1)->first();

        if($set->bank_transfer == 0){
            return response()->json([
                'status' => false,
                'message' => "You can not transfer at the moment, Please contact support"
            ], 422);
        }

        if($can_transfer == 0){
            return response()->json([
                'status' => false,
                'message' => "You have been banned from Bank Transfer, Please contact support"
            ], 422);
        }

        if($account_tier == "Tier_1"){
            $limit = $set->tier_1_limit;
        }elseif($account_tier == "Tier_2")
            $limit = $set->tier_2_limit;
        else{
            $limit = $set->tier_3_limit;
        }


        if($request->amount > $limit){
            return response()->json([
                'status' => false,
                'message' => "Upgrade your account to transfer more, Please contact support"
            ], 422);
        }




        $data = [
            'TransactionTrackingRef' => $TransactionTrackingRef,
            'AccountOpeningTrackingRef' => $TransactionTrackingRef,
            'ProductCode' => '101',
            'LastName' => $user->last_name,
            'FirstName' => $user->first_name,
            'OtherNames' => $user->other_name,
            'BVN' => $user->bvn,
            'PhoneNo' => $user->phone_no,
            'Gender' => $user->gender,
            'DateOfBirth' => $user->dob,
            'Email' => $user->email,
            'AccountTier' => '1',
            'AccountOfficerCode' => '003',
        ];

        $response = $this->bankOneService->createAccount($data, $user_id);



    }



    public function name_inquary(request $request)
    {

        $account_no = $request->accountNumber;
        $code = $request->bankCode;

        $response = $this->bankOneService->name_enquiry($code, $account_no);


        if($response['codes'] == 0){

            return response()->json([
                'status' => false,
                'message' => $response['message']
            ],422);

        }else{

            return response()->json([
                'status' => true,
                'name' => $response['name']
            ]);
        }


    }


}
