<?php

namespace App\Http\Controllers\Mobile\VAS;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\VTPassService;
use Illuminate\Http\Request;

class CableController extends Controller
{

    protected $VTpass;

    public function __construct(VTPassService $VTpass)
    {
        $this->VTpass = $VTpass;
    }

    public function get_cable_biller()
    {

        $set = Setting::where('id', 1)->first();
        if($set->bills_provider == "vt_pass") {
            try {

                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://vtpass.com/api/service-variations?serviceID=dstv');
                $response = $request->getBody();
                $result = json_decode($response);
                $dstv = $result->content->variations;

                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://vtpass.com/api/service-variations?serviceID=gotv');
                $response = $request->getBody();
                $result = json_decode($response);
                $gotv = $result->content->variations;

                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://vtpass.com/api/service-variations?serviceID=startimes');
                $response = $request->getBody();
                $result = json_decode($response);
                $startimes = $result->content->variations;

                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://vtpass.com/api/service-variations?serviceID=showmax');
                $response = $request->getBody();
                $result = json_decode($response);
                $showmax = $result->content->variations;


                return response()->json([
                    'status' => true,
                    'dstv' => $dstv,
                    'gotv' => $gotv,
                    'startimes' => $startimes,
                    'showmax' => $showmax,
                ], 200);

            } catch (\Exception$th) {
                return $th->getMessage();
            }
        }

    }


    public function validate_cable(request $request)
    {

        $biller_code = $request->biller_code;
        $service_id = $request->service_id;

        $response = $this->VTpass->ValidateCable($biller_code, $service_id);

        if($response['status'] == 1){

            return response()->json([

                'status' => true,
                'customer_name' => $response['data']->Customer_Name ?? null,
                'cable_status' => $response['data']->Status ?? null,
                'due_date' => $response['data']->Due_Date ?? null,
                'customer_no' => $response['data']->Customer_Number ?? null,
                'customer_type' => $response['data']->Customer_Type ?? null,
                'current_bouquet' => $response['data']->Current_Bouquet ?? null,
                'current_bouquet_code' => $response['data']->Current_Bouquet_Code ?? null,
                'renewal_amount' => $response['data']->Renewal_Amount ?? null

        }else{

            return response()->json([
                'status' => false,
                'message' => $response['message'],
            ], 422);


        }



    }

}
