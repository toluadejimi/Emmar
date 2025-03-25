<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
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


}
