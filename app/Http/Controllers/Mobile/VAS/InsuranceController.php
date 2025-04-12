<?php

namespace App\Http\Controllers\Mobile\VAS;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class InsuranceController extends Controller
{
    public function get_insurance_biller()
    {

        $set = Setting::where('id', 1)->first();
        if($set->bills_provider == "vt_pass") {
            try {

                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://vtpass.com/api/service-variations?serviceID=ui-insure');
                $response = $request->getBody();
                $result = json_decode($response);
                $insurance = $result->content->variations;


                return response()->json([
                    'status' => true,
                    'service_name' => $result->content->ServiceName,
                    'data' => $insurance
                ], 200);



            } catch (\Exception$th) {
                return $th->getMessage();
            }
        }

    }
}
