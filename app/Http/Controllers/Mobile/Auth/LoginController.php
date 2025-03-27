<?php

namespace App\Http\Controllers\Mobile\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\BankOneService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Account;


class LoginController extends Controller
{


    protected $bankOneService;

    public function __construct(BankOneService $bankOneService)
    {
        $this->bankOneService = $bankOneService;
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
            'deviceDetails' => 'nullable|string',
        ]);

        $phone_no = preg_replace('/^0/', '', $request->phone);
        $phone = "+234" . $phone_no;

        $user = User::where('phone', $phone)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Phone number not found'
            ], 404);
        }


        if (Auth::attempt(['phone' => $phone, 'password' => $request->password])) {
            $user = Auth::user();
            $user->session_id = Str::random(60);
            $user->device_details = $request->deviceDetails ?? 'Unknown Device';
            $user->save();
            $token = auth()->user()->createToken('API Token')->accessToken;


           $account_no = Account::where('user_id', Auth::id())->get()->makeHidden(['created_at', 'updated_at']);
            $all_account = [];
           foreach ($account_no as $data){
               $account = $data->account_number;
               $all_account[] = $this->bankOneService->get_balance($account);
           }





            $usrr = User::where('id', Auth::id())->first()->makeHidden(['register_under_id', 'created_at','updated_at','session_id']);
            $usrr['token'] = $token;
            $usrr['accounts'] = $all_account;


            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => $usrr
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Incorrect password',
        ], 401);
    }



}
