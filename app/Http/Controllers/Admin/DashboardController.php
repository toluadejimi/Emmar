<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\PosLog;
use App\Models\Terminal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard_data(request $request)
    {

        if (Auth::user()->role == 1 || Auth::user()->role == 2 ) {

            $data['users'] = User::count();
            $data['successful_transactions'] = PosLog::latest()->where('transactionType', 'PURCHASE')->where('status', 1)->sum('amount');
            $data['failed_transactions'] = PosLog::latest()->where('transactionType', 'PURCHASE')->where('status', 0)->sum('amount');
            $data['all_terminals'] = Terminal::count();
            $data['all_banks'] = Bank::count();
            $data['all_transactions'] = PosLog::latest()->take(100)->get();

            return response()->json([
                'status' => true,
                'data' => $data

            ], 200);
        }


        if (Auth::user()->role == 3) {

            $bank_id = User::where('id', Auth::id())->first()->bank_id ?? null;

            if($bank_id == null){
                return response()->json([
                    'status' => false,
                    'message' => "Update Bank Details"
                ], 422);

            }

            $data['users'] = User::where('bank_id', $bank_id)->count();
            $data['successful_transactions'] = PosLog::latest()->where('transactionType', 'PURCHASE')
                ->where(['status'  => 1, 'bank_id' => $bank_id])->sum('amount');

            $data['failed_transactions'] = PosLog::latest()->where('transactionType', 'PURCHASE')
                ->where(['status'  => 0, 'bank_id' => $bank_id])->sum('amount');

            $data['all_terminals'] = Terminal::where('bank_id', $bank_id)->count();
            $data['all_transactions'] = PosLog::latest()->where('bank_id', $bank_id)->take(100)->get();


            return response()->json([
                'status' => true,
                'data' => $data

            ], 200);
        }



        if (Auth::user()->role == 4) {


            if(Auth::id() == null){
                return response()->json([
                    'status' => false,
                    'message' => "Update User Details"
                ], 422);

            }

            $data['successful_transactions'] = PosLog::latest()->where('transactionType', 'PURCHASE')
                ->where(['status'  => 1, 'user_id' => Auth::id()])->sum('amount');

            $data['failed_transactions'] = PosLog::latest()->where('transactionType', 'PURCHASE')
                ->where(['status'  => 0, 'user_id' => Auth::id()])->sum('amount');

            $data['all_terminals'] = Terminal::where('user_id', Auth::id())->count();

            $data['all_transactions'] = PosLog::latest()->where('user_id', Auth::id())->take()->get();


            return response()->json([
                'status' => true,
                'data' => $data

            ], 200);
        }




    }

}
