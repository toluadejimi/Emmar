<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\BankLogo;
use App\Models\RecentBankDetails;
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

//        $request->validate([
//            'account_number' => 'required|digits:10'
//        ]);


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
                $bank->url = url('storage/app/public/bankslogo/' . $bank->url);
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
                'success' => true,
                'data' => $ck_acc
            ]);

        }
    }


}
