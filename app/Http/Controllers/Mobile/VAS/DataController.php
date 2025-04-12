<?php

namespace App\Http\Controllers\Mobile\VAS;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function get_data()
    {

        $set = Setting::where('id', 1)->first();
        if($set->bills_provider == "vt_pass"){
            try {

                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://vtpass.com/api/service-variations?serviceID=mtn-data');
                $response = $request->getBody();
                $result = json_decode($response);
                $get_mtn_network = $result->content->variations;

                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://vtpass.com/api/service-variations?serviceID=glo-data');
                $response = $request->getBody();
                $result = json_decode($response);
                $get_glo_network = $result->content->variations;

                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://vtpass.com/api/service-variations?serviceID=airtel-data');
                $response = $request->getBody();
                $result = json_decode($response);
                $get_airtel_network = $result->content->variations;

                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://vtpass.com/api/service-variations?serviceID=etisalat-data');
                $response = $request->getBody();
                $result = json_decode($response);
                $get_9mobile_network = $result->content->variations;

                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://vtpass.com/api/service-variations?serviceID=smile-direct');
                $response = $request->getBody();
                $result = json_decode($response);
                $get_smile_network = $result->content->variations;

                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://vtpass.com/api/service-variations?serviceID=spectranet');
                $response = $request->getBody();
                $result = json_decode($response);
                $get_spectranet_network = $result->content->variations;

                return response()->json([
                    'status' => true,
                    'mtn_data' => $get_mtn_network,
                    'glo_data' => $get_glo_network,
                    'airtel_data' => $get_airtel_network,
                    '9mobile_data' => $get_9mobile_network,
                    'smile_data' => $get_smile_network,
                    'spectranet_data' => $get_spectranet_network,
                ], 200);

            } catch (\Exception$th) {
                return $th->getMessage();
            }
        }


    }

}
