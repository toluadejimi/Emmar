<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\PosLog;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function create_user(request $request)
    {


        if (Auth::user()->role != 1) {
            return response()->json([
                'status' => false,
                'message' => "You dont have permission to create a user"
            ], 422);
        }

        if (Auth::user()->status != 1) {
            return response()->json([
                'status' => false,
                'message' => "You dont have permission to create a user"
            ], 422);
        }


        $usr_status = User::where('email', $request->email)->first()->email ?? null;
        if ($usr_status == $request->email) {
            return response()->json([
                'status' => false,
                'message' => "User Already Exist"
            ], 422);
        } else {


            $user = new User();
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->role = $request->role;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->password = bcrypt($request->password);
            $user->hos_no = $request->hos_no;
            $user->gender = $request->gender;
            $user->address_line1 = $request->address_line1;
            $user->state = $request->state;
            $user->lga = $request->lga;
            $user->save();


            try {

                $curl = curl_init();
                $data = array(
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'role' => $request->role,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'hos_no' => $request->hos_no,
                    'gender' => $request->gender,
                    'address_line1' => $request->address_line1,
                    'state' => $request->state,
                    'lga' => $request->lga,

                );
                $post_data = json_encode($data);

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://etopmerchant.com/api/store-user',
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
                'data' => $user,
                'message' => "User Created Successfully"
            ], 200);

        }


    }


    public function get_users(request $request)
    {

        if (Auth::user()->role == 1 || Auth::user()->role == 2) {
            $users = User::latest()->paginate(50);
            return response()->json([
                'status' => true,
                'data' => $users
            ], 200);

        } else {

            return response()->json([
                'status' => false,
                'message' => "You dont have permission to create a terminal"
            ], 422);
        }
    }


    public function get_customer_users(request $request)
    {

        if (Auth::user()->role == 1 || Auth::user()->role == 2) {
            $users = User::latest()->where('role', 4)->paginate(50);
            return response()->json([
                'status' => true,
                'data' => $users
            ], 200);

        } else {

            return response()->json([
                'status' => false,
                'message' => "You dont have permission to create a terminal"
            ], 422);
        }
    }


    public function get_bank_users(request $request)
    {

        if (Auth::user()->role == 1 || Auth::user()->role == 2) {
            $users = User::where('role', 3)->latest()->paginate(50);
            return response()->json([
                'status' => true,
                'data' => $users
            ], 200);

        } else {

            return response()->json([
                'status' => false,
                'message' => "You dont have permission to create a terminal"
            ], 422);
        }
    }


    public function update_user(request $request)
    {

        if (Auth::user()->role == 1 || Auth::user()->role == 2) {

            $users = User::where('id', $request->id())->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'bank_id' => $request->bank_id,

            ]);

            try {

                $curl = curl_init();
                $data = array(
                    'id' => $request->id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'bank_id' => $request->bank_id,

                );
                $post_data = json_encode($data);

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://etopmerchant.com/api/update-user',
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
                'data' => $users
            ], 200);



        } else {

            return response()->json([
                'status' => false,
                'message' => "You dont have permission to create a terminal"
            ], 422);
        }
    }

    public function delete_user(request $request)
    {

        if (Auth::user()->role == 1 || Auth::user()->role == 2) {

            User::where('id', $request->id)->delete();

            try {

                $curl = curl_init();
                $data = array(
                    'id' => $request->id,
                );
                $post_data = json_encode($data);

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://etopmerchant.com/api/delete-user',
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
                'message' => "User deleted successfully"
            ], 200);



        } else {

            return response()->json([
                'status' => false,
                'message' => "You dont have permission to create a terminal"
            ], 422);
        }
    }


    public function get_all_by_serial_logged_data(request $request)
    {

        $SerialNo = $request->header('serialnumber');
        $account_balance = user_balance($SerialNo);


        $data = PosLog::select('Id', 'terminalID', 'amount', 'transactionType', 'RRN', 'pan', 'cardName', 'serialNO', 'responseCode',
            'respCode')->where('SerialNo', $SerialNo)->get() ?? null;

        if ($data == null) {
            $message = 'Transaction not found';
            error_response($message);
        }

        return response()->json([
            'success' => true,
            'loggedData' => null,
            'allLoggedData' => $data,
            'error' => null,
        ], 200);


    }


    public function search_user(request $request)
    {

        if (Auth::user()->role == 1 || Auth::user()->role == 2) {

            $keyword = $request->keyword;
            $results = User::where('first_name', 'LIKE', "%$keyword%")->get();

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
