<?php
namespace App\Services\VTPass;

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
}
