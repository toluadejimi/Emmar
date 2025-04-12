<?php
namespace App\Services;

class VTPassService
{
    public function callVTPassApi($requestId, $serviceId, $amount, $phone, $apiKey, $skKey)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://vtpass.com/api/pay',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'request_id' => $requestId,
                'serviceID' => $serviceId,
                'amount' => $amount,
                'phone' => $phone,
            ),
            CURLOPT_HTTPHEADER => array(
                "api-key: $apiKey",
                "secret-key: $skKey",
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }

    public function Validate($biller_code, $service_id){


        try {

            $api_key = env('VTPASSAPIKEY');
            $sk_key = env('VTPASSSKKEY');

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-service.vtpass.com/api/merchant-verify',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'serviceID' => $service_id,
                    'billersCode' => $biller_code,
                ),
                CURLOPT_HTTPHEADER => array(
                    //"Authorization: Basic $auth=",
                    "api-key: $api_key",
                    "secret-key: $sk_key",
                    'Cookie: laravel_session=eyJpdiI6IlBkTGc5emRPMmhyQVwvb096YkVKV2RnPT0iLCJ2YWx1ZSI6IkNvSytPVTV5TW52K2tBRlp1R2pqaUpnRDk5YnFRbEhuTHhaNktFcnBhMFRHTlNzRWIrejJxT05kM1wvM1hEYktPT2JKT2dJWHQzdFVaYnZrRytwZ2NmQT09IiwibWFjIjoiZWM5ZjI3NzBmZTBmOTZmZDg3ZTUxMDBjODYxMzQ3OTkxN2M4YTAxNjNmMWY2YjAxZTIzNmNmNWNhOWExNzJmOCJ9',
                ),
            ));

            $var = curl_exec($curl);
            curl_close($curl);

            $var = json_decode($var);

            dd($var, $service_id, $biller_code);


            return response()->json([


            ], 200);

        } catch (\Exception$th) {
            return $th->getMessage();
        }


    }

}
