<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankController extends Controller
{
    public function create_bank(request $request)
    {
        if (Auth::user()->role == 1 || Auth::user()->role == 2) {


            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = $file->getClientOriginalName();
                $destinationPath = public_path() . 'upload/image';
                $request->image->move(public_path('upload/image'), $fileName);
                $file_url = url('') . "/public/upload/image/$fileName";

                $bank = new Bank();
                $bank->name = $request->name;
                $bank->email = $request->email;
                $bank->image = $file_url;
                $bank->save();

                $usr = new User();
                $usr->first_name = $request->name;
                $usr->last_name = $request->name;
                $usr->email = $request->email;
                $usr->role = 3;
                $usr->bank_id = $bank->id;
                $usr->password = bcrypt('123456');
                $usr->save();

                try {

                    $curl = curl_init();
                    $data = array(
                        'email' => $request->email,
                        'name' => $request->name,
                        'image' => $file_url

                    );
                    $post_data = json_encode($data);

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://etopmerchant.com/api/create-bank',
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
                    'data' => $bank,
                    'message' => "Bank Created Successfully "
                ], 200);

            }


            $bank = new Bank();
            $bank->name = $request->name;
            $bank->email = $request->email;
            $bank->save();


            $usr = new User();
            $usr->first_name = $request->name;
            $usr->last_name = $request->name;
            $usr->email = $request->email;
            $usr->role = 3;
            $usr->bank_id = $bank->id;
            $usr->password = bcrypt('123456');
            $usr->save();


            try {

                $curl = curl_init();
                $data = array(
                    'email' => $request->email,
                    'name' => $request->name,

                );
                $post_data = json_encode($data);

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://etopmerchant.com/api/create-bank',
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
                'data' => $bank,
                'message' => "Bank Created Successfully "
            ], 200);

        } else {

            return response()->json(['status' => true,
                'message' => "You don't have permission"

            ], 422);

        }
    }


    public
    function search_bank(request $request)
    {

        if (Auth::user()->role == 1 || Auth::user()->role == 2) {

            $keyword = $request->keyword;
            $results = Bank::where('name', 'LIKE', "%$keyword%")->get();

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


    public
    function get_all_banks(request $request)
    {

        if (Auth::user()->role == 1 || Auth::user()->role == 2) {

            $results = Bank::latest()->paginate(50);
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



    public
    function all_banks(request $request)
    {

        if (Auth::user()->role == 1 || Auth::user()->role == 2) {

            $results = Bank::latest()->get();
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


    public
    function delete_bank(request $request)
    {
        if (Auth::user()->role == 1 || Auth::user()->role == 2) {

            Bank::where('id', $request->id)->delete();

            return response()->json([
                'status' => true,
                'message' => "Bank updated successfully "
            ], 200);

        } else {

            return response()->json([
                'status' => true,
                'message' => "You don't have permission"
            ], 422);

        }
    }


}
