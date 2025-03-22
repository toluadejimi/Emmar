<?php

namespace App\Http\Controllers\Mobile\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{


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


            $usrr = User::where('id', Auth::id())->first()->makeHidden(['register_under_id', 'created_at','updated_at','session_id']);
            $usrr['token'] = $token;
            $usrr['accounts'] = get_account(Auth::id());




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
