<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Terminal;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TerminalController extends Controller
{
    public function create_terminal(request $request)
    {


        if ($request->user_id == null || $request->serialNumber == null) {

            return response()->json([
                'status' => false,
                'message' => "User ID or Serial Number can not be null"
            ], 422);

        }


        if ($request->bank_id == null) {
            return response()->json([
                'status' => false,
                'message' => "Bank Details can not be null"
            ], 422);

        }


        if (Auth::user()->role == 1 || Auth::user()->role == 2) {


            $ter = Terminal::where('serialNumber', $request->serialNumber)->first() ?? null;
            if ($ter == null) {

                $term = new Terminal();
                $term->tid = $request->tid;
                $term->user_id = $request->user_id;
                $term->bank_id = $request->bank_id;
                $term->ip = $request->ip;
                $term->port = $request->port;
                $term->ssl = $request->ssl;
                $term->compKey1 = $request->compKey1;
                $term->compKey2 = $request->compKey2;
                $term->baseUrl = $request->baseurl;
                $term->logoUrl = $request->logoUrl;
                $term->serialNumber = $request->serialNumber;
                $term->merchantName = $request->merchantName;
                $term->mid = $request->mid;
                $term->merchantaddress = $request->merchantaddress;
                $term->pin = bcrypt($request->pin);
                $term->save();


                try {

                    $curl = curl_init();
                    $data = array(

                        'tid' => $request->tid,
                        'user_id' => $request->user_id,
                        'bank_id' => $request->bank_id,
                        'ip' => $request->ip,
                        'port' => $request->port,
                        'ssl' => $request->ssl,
                        'compKey1' => $request->compKey1,
                        'compKey2' => $request->compKey2,
                        'baseUrl' => "http://etopagency.com:9001/",
                        'logoUrl' => $request->logoUrl,
                        'serialNumber' => $request->serialNumber,
                        'merchantName' => $request->merchantName,
                        'mid' => $request->mid,
                        'merchantaddress' => $request->merchantaddress,
                        'pin' => bcrypt($request->pin),

                    );
                    $post_data = json_encode($data);

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://etopmerchant.com/api/store-terminal',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => $post_data,
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json'
                        ),
                    ));

                    $var = curl_exec($curl);
                    curl_close($curl);


                } catch (QueryException $e) {
                    echo "$e";
                }


                return response()->json([
                    'status' => true,
                    'message' => "Terminal has been created successfully"
                ], 200);

            }


            if ($ter != null) {

                return response()->json([
                    'status' => false,
                    'message' => "Terminal Account Already  Exist"
                ], 422);


            }

        } else {

            return response()->json([
                'status' => false,
                'message' => "You dont have permission to create a terminal"
            ], 422);
        }
    }

    public function create_bulk_terminal(request $request)
    {

        $validator = Validator::make($request->all(), [
            'file' => 'required'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }


        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $filePath = public_path('uploads') . '/' . $fileName;

            $csv = array_map('str_getcsv', file($filePath));
            $keys = array_shift($csv);
            $keys = array_diff($keys, ['compKey1', 'compKey2','accountBalance','ip', 'port', 'ssl']);
            $numColumns = count($keys);
            foreach ($csv as $row) {
                $row = array_diff_key($row, array_flip(['compKey1', 'compKey2', 'accountBalance','ip', 'port', 'ssl']));
                if (count($row) !== $numColumns) {
                    return response()->json([
                        'status' => false,
                        'message' => "CSV file contains rows with different number of columns"
                    ], 422);

                }
            }

            foreach ($csv as $row) {
                $row = array_combine($keys, $row);
                Terminal::create($row);
            }


            return response()->json([
                'status' => true,
                'message' => "CSV file imported successfully"
            ], 200);

        }


        return response()->json([
            'status' => false,
            'message' => "Something went wrong"
        ], 422);

    }

    public function update_terminal(request $request)
    {


        if ($request->user_id == null || $request->serialNumber == null) {

            return response()->json([
                'status' => false,
                'message' => "User ID or Serial Number can not be null"
            ], 422);

        }

        if (Auth::user()->role == 1 || Auth::user()->role == 2) {

            $ter = Terminal::where('serialNumber', $request->serialNumber)->first() ?? null;
            if ($ter != null) {

                Terminal::where('serialNumber', $request->serialNumber)->update(['user_id' => $request->user_id, 'bank_id' => $request->bank_id]);

                try {

                    $curl = curl_init();
                    $data = array(
                        'user_id' => $request->user_id,
                        'serialNumber' => $request->serialNumber,
                        'bank_id' => $request->bank_id,
                    );
                    $post_data = json_encode($data);

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://etopmerchant.com/api/update-terminal',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => $post_data,
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json'
                        ),
                    ));

                    $var = curl_exec($curl);
                    curl_close($curl);


                } catch (QueryException $e) {
                    echo "$e";
                }

                return response()->json([
                    'status' => true,
                    'message' => "Terminal has been successfully updated"
                ], 200);

            } else {

                return response()->json([
                    'status' => false,
                    'message' => "Terminal Not Found"
                ], 422);

            }


        } else {

            return response()->json([
                'status' => false,
                'message' => "You dont have permission to update a terminal"
            ], 422);
        }
    }


    public function view_all_terminal(request $request)
    {



        if (Auth::user()->role == 1 || Auth::user()->role == 2 ) {

            $ter = Terminal::latest()->paginate(50) ?? null;
            if ($ter == null) {
                return response()->json([
                    'status' => false,
                    'message' => "No Terminal Found"
                ], 422);

            }


            return response()->json([
                'status' => true,
                'data' => $ter
            ], 200);

        }

        if (Auth::user()->role == 3 ) {


            $ter = Terminal::latest()->where('bank_id', Auth::user()->bank_id)->paginate('10') ?? null;

            if ($ter == null) {
                return response()->json([
                    'status' => false,
                    'message' => "No Terminal Found"
                ], 422);

            }

            return response()->json([
                'status' => true,
                'data' => $ter
            ], 200);


        }

        if (Auth::user()->role == 4 ) {

            $ter = Terminal::latest()->where('user_id', Auth::id())->paginate(10) ?? null;
            if ($ter == null) {
                return response()->json([
                    'status' => false,
                    'message' => "No Terminal Found"
                ], 422);

            }

            return response()->json([
                'status' => true,
                'data' => $ter
            ], 200);


        }




        return response()->json([
            'status' => false,
            'message' => "No Terminal Found"
        ], 422);


    }


    public function search_terminal(request $request)
    {

        if (Auth::user()->role == 1 || Auth::user()->role == 2) {

            $keyword = $request->keyword;
            $results = Terminal::where('serialNumber', 'LIKE', "%$keyword%")->get();

            return response()->json([

                'status' => true,
                'data' => $results

            ], 200);



        } else {

            return response()->json([
                'status' => false,
                'message' => "You dont have permission to create a terminal"
            ], 422);
        }
    }


    public function get_terminal_by_bank(request $request)
    {

        if (Auth::user()->role == 1 || Auth::user()->role == 2) {

            $results = Terminal::where('bank_id',$request->bank_id)->paginate('50');

            return response()->json([

                'status' => true,
                'data' => $results

            ], 200);



        } else {

            return response()->json([
                'status' => false,
                'message' => "You dont have permission to create a terminal"
            ], 422);
        }
    }

    public function get_terminal_by_bank_export(request $request)
    {

        if (Auth::user()->role == 1 || Auth::user()->role == 2) {

            $results = Terminal::where('bank_id',$request->bank_id)->get();

            return response()->json([

                'status' => true,
                'data' => $results

            ], 200);



        } else {

            return response()->json([
                'status' => false,
                'message' => "You dont have permission to create a terminal"
            ], 422);
        }
    }








}
