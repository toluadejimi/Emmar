<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class HomeController extends Controller
{

    public function index()
    {
        return view('web.auth.welcome');

    }

    public function log_out()
    {

        Auth::logout();
        return redirect('/');

    }

    public function login_now(request $request)
    {



        $credentials = request([
            'email', 'password'
        ]);


        if (!auth()->attempt($credentials)) {
            return back()->with('error', 'Email or password incorrect');
        }

        if(Auth::user()->role == 0){
            return redirect('admin-dashboard');
        }else{
            return redirect('user-dashboard');

        }



    }


}
