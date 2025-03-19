<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PosLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{

    public function export_transactions(request $request)
    {


        if (Auth::user()->role == 1 || Auth::user()->role == 2) {

            $rrn = $request->rrn;
            $startofday = $request->from;
            $endofday = $request->to;

            $type = $request->type;






            if ($startofday != null && $endofday != null) {

                $data = PosLog::latest()->whereBetween('createdAt', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])
                    ->get() ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }

            if ($startofday == null && $endofday == null && $rrn != null) {

                $data = PosLog::where('RRN', $rrn)->get() ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }


            if ($startofday != null && $endofday == null) {

                $data = PosLog::latest()->whereDate('createdAt', $startofday)->get() ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }

            if ($startofday == null && $endofday != null) {

                $data = PosLog::latest()->wheredate('createdAt', $endofday)->get() ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }




            if ($rrn != null && $startofday != null && $endofday != null) {

                $data = PosLog::whereBetween('createdAt', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])
                    ->where([
                        'RRN' => $rrn,
                    ])->get() ?? null;


                if ($data->isEmpty()) {

                    return response()->json([
                        'success' => true,
                        'data' => [],
                    ], 200);


                }

                return response()->json([
                    'success' => false,
                    'transaction' => "No data Found",

                ], 200);


            }

        }


        if (Auth::user()->role == 3) {

            $rrn = $request->rrn;
            $startofday = $request->from;
            $endofday = $request->to;



            $type = $request->type;



            if ($startofday != null && $endofday != null) {

                $data = PosLog::latest()->whereBetween('createdAt', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])
                    ->where('bank_id', Auth::user()->bank_id)->get() ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }

            if ($startofday == null && $endofday == null && $rrn != null) {

                $data = PosLog::where('RRN', $rrn)->where('bank_id', Auth::user()->bank_id)->get() ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }


            if ($startofday != null && $endofday == null) {

                $data = PosLog::latest()->whereDate('createdAt', $startofday)->where('bank_id', Auth::user()->bank_id)->get() ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }

            if ($startofday == null && $endofday != null) {

                $data = PosLog::latest()->wheredate('createdAt', $endofday)->where('bank_id', Auth::user()->bank_id)->get() ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }




            if ($rrn != null && $startofday != null && $endofday != null) {

                $data = PosLog::whereBetween('createdAt', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])
                    ->where([
                        'RRN' => $rrn,
                        'bank_id' => Auth::user()->bank_id
                    ])->get() ?? null;


                if ($data->isEmpty()) {

                    return response()->json([
                        'success' => true,
                        'data' => [],
                    ], 200);


                }

                return response()->json([
                    'success' => false,
                    'transaction' => "No data Found",

                ], 200);


            }

        }



        return response()->json([
            'success' => true,
            'transaction' => [],

        ], 200);

    }



    public function get_all_transactions($limit)
    {


        if (Auth::user()->role == 1 || Auth::user()->role == 2) {


            if($limit == null){
                return response()->json([
                    'status' => false,
                    'message' => "Add Transaction limit to your request"
                ], 422);

            }

            $get_all_transaction = PosLog::latest()->take($limit)->paginate(50);
            return response()->json([
                'status' => true,
                'data' => $get_all_transaction
            ], 200);

        }


        if (Auth::user()->role == 3) {

            if($limit == null){
                return response()->json([
                    'status' => false,
                    'message' => "Add Transaction limit to your request"
                ], 422);

            }
            $get_all_transaction = PosLog::latest()->where('bank_id',Auth::user()->bank_id )->take($limit)->paginate(50);
            return response()->json([
                'status' => true,
                'data' => $get_all_transaction
            ], 200);

        }


        if (Auth::user()->role == 4) {

            if($limit == null){
                return response()->json([
                    'status' => false,
                    'message' => "Add Transaction limit to your request"
                ], 422);

            }
            $get_all_transaction = PosLog::latest()->where('user_id',Auth::id() )->take($limit)->paginate(50);
            return response()->json([
                'status' => true,
                'data' => $get_all_transaction
            ], 200);

        }else{

            return response()->json([
                'status' => false,
                'message' => "You dont have permission"
            ], 422);


        }




    }



    public function get_transactions_by_filter(request $request, $limit)
    {


        if (Auth::user()->role == 1 || Auth::user()->role == 2) {

            $rrn = $request->rrn;
            $startofday = $request->from;
            $endofday = $request->to;



            if ($limit == null) {
                $limit1 = 50;
            } else {
                $limit1 = $limit;
            }
            $type = $request->type;






            if ($startofday != null && $endofday != null) {

                $data = PosLog::latest()->whereBetween('createdAt', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])
                    ->take($limit1)->paginate(50) ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }

            if ($startofday == null && $endofday == null && $rrn != null) {

                $data = PosLog::where('RRN', $rrn)->take($limit)->paginate(50) ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }


            if ($startofday != null && $endofday == null) {

                $data = PosLog::latest()->whereDate('createdAt', $startofday)->take($limit)->paginate(50) ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }

            if ($startofday == null && $endofday != null) {

                $data = PosLog::latest()->wheredate('createdAt', $endofday)->take($limit)->paginate(50) ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }




            if ($rrn != null && $startofday != null && $endofday != null) {

                $data = PosLog::whereBetween('createdAt', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])
                    ->where([
                        'RRN' => $rrn,
                    ])->take($limit)->paginate(50) ?? null;


                if ($data->isEmpty()) {

                    return response()->json([
                        'success' => true,
                        'data' => [],
                    ], 200);


                }

                return response()->json([
                    'success' => false,
                    'transaction' => "No data Found",

                ], 200);


            }

        }


        if (Auth::user()->role == 3) {

            $rrn = $request->rrn;
            $startofday = $request->from;
            $endofday = $request->to;


            if ($limit == null) {
                $limit1 = 50;
            } else {
                $limit1 = $limit;
            }
            $type = $request->type;



            if ($startofday != null && $endofday != null) {

                $data = PosLog::latest()->whereBetween('createdAt', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])
                    ->take($limit1)->where('bank_id', Auth::user()->bank_id)->paginate(50) ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }

            if ($startofday == null && $endofday == null && $rrn != null) {

                $data = PosLog::where('RRN', $rrn)->where('bank_id', Auth::user()->bank_id)->take($limit)->paginate(50) ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }


            if ($startofday != null && $endofday == null) {

                $data = PosLog::latest()->whereDate('createdAt', $startofday)->where('bank_id', Auth::user()->bank_id)->take($limit)->paginate(50) ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }

            if ($startofday == null && $endofday != null) {

                $data = PosLog::latest()->wheredate('createdAt', $endofday)->where('bank_id', Auth::user()->bank_id)->take($limit)->paginate(50) ?? null;

                return response()->json([
                    'success' => true,
                    'data' => $data,

                ], 200);


            }




            if ($rrn != null && $startofday != null && $endofday != null) {

                $data = PosLog::whereBetween('createdAt', [$startofday . ' 00:00:00', $endofday . ' 23:59:59'])
                    ->where([
                        'RRN' => $rrn,
                         'bank_id' => Auth::user()->bank_id
                    ])->take($limit)->paginate(50) ?? null;


                if ($data->isEmpty()) {

                    return response()->json([
                        'success' => true,
                        'data' => [],
                    ], 200);


                }

                return response()->json([
                    'success' => false,
                    'transaction' => "No data Found",

                ], 200);


            }

        }



        return response()->json([
            'success' => true,
            'transaction' => [],

        ], 200);


    }

}
