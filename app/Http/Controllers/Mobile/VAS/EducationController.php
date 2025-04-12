<?php

namespace App\Http\Controllers\Mobile\VAS;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function get_education_biller()
    {

        $set = Setting::where('id', 1)->first();
        if($set->bills_provider == "vt_pass") {

            try {

                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://vtpass.com/api/service-variations?serviceID=waec-registration');
                $response = $request->getBody();
                $result = json_decode($response);
                $datawaec = $result->content->variations ?? null;
                if($datawaec == null){
                    $waec = $result->content->errors;
                }else{
                    $waec = $result->content->variations;
                }





                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://vtpass.com/api/service-variations?serviceID=waec');
                $response = $request->getBody();
                $result = json_decode($response);
                $datawaec_result = $result->content->variations ?? null;
                if($datawaec_result == null){
                    $waecresult = $result->content->errors;
                }else{
                    $waecresult = $result->content->variations;
                }

                $client = new \GuzzleHttp\Client();
                $request = $client->get('https://vtpass.com/api/service-variations?serviceID=jamb');
                $response = $request->getBody();
                $result = json_decode($response);
                $datajamb = $result->content->variations;
                if($datajamb == null){
                    $jamb = $result->content->errors;
                }else{
                    $jamb = $result->content->variations;
                }


                return response()->json([
                    'status' => true,
                    'waec_exam' => $waec,
                    'waec_result_checker' => $waecresult,
                    'jamb_exam' => $jamb,
                ], 200);

            } catch (\Exception$th) {
                return $th->getMessage();
            }

        }

    }
}
