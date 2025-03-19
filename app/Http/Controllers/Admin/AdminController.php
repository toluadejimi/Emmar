<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\PosLog;
use App\Models\Terminal;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function admin_login(request $request)
    {

        if (Auth::attempt([
            'email' => $request->email, 'password' => $request->password],
            $request->get('remember'))) {

            $user['user'] = Auth::user();
            $user['token'] = auth()->user()->createToken('API Token')->accessToken;

            return response()->json([
                'status' => true,
                'data' => $user

            ], 200);

        } else {

            return response()->json([
                'status' => false,
                'message' => "Email or Password is Incorrect"

            ], 422);

        }

    }


}
