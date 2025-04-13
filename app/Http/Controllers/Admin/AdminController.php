<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Card;
use App\Models\PosLog;
use App\Models\Terminal;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function admin_index(request $request)
    {

        $data['total_inflow'] = Transaction::where('status', 2)->sum('credit');
        $data['total_outflow'] = Transaction::where('status', 2)->sum('debit');
        $data['total_profit'] = Transaction::where('status', 2)->sum('fees');
        $data['total_customer'] = User::where('role', 2)->count();
        $data['total_cards'] = Card::where('status', 2)->count();


        $year = now()->year;

        $inflow = array_fill(0, 12, 0);
        $outflow = array_fill(0, 12, 0);

        $inflowData = DB::table('transactions')
            ->selectRaw('MONTH(created_at) as month, SUM(credit) as total_inflow')
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total_inflow', 'month')
            ->toArray();

        // Get outflows per month
        $outflowData = DB::table('transactions')
            ->selectRaw('MONTH(created_at) as month, SUM(debit) as total_outflow')
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total_outflow', 'month')
            ->toArray();

        // Fill inflow/outflow arrays with actual data
        foreach ($inflowData as $month => $value) {
            $inflow[$month - 1] = (float) $value;
        }

        foreach ($outflowData as $month => $value) {
            $outflow[$month - 1] = (float) $value;
        }


        $data['inflow']  = $inflow;
        $data['outflow'] = $outflow;
        $data['transactions'] = Transaction::latest()->paginate('20');


        return view('web.admin.dashboard', $data);
    }


}
