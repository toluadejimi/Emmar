<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\BankLogo;
use App\Services\BankOneService;
use Illuminate\Http\Request;

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

        $request->validate([
            'account_number' => 'required|digits:10'
        ]);

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
                'data' => $banks
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "No matching bank found"
            ]);
        }


    }


}
