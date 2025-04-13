<?php

namespace App\Http\Controllers\Mobile\Auth;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Services\BankOneService;
use App\Services\VTPassService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected $bankOneService;

    public function __construct(BankOneService $bankOneService)
    {
        $this->bankOneService = $bankOneService;

    }

    public function get_user_account()
    {

        $account_no = Account::where('user_id', Auth::id())->get()->makeHidden(['created_at', 'updated_at']);
        $all_account = [];
        foreach ($account_no as $data){
            $account = $data->account_number;
            $all_account[] = $this->bankOneService->get_balance($account);
        }

        return response()->json([
            'success' => true,
            'data' => $all_account,

        ]);

    }

}
