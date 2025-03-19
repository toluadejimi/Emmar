<?php


use App\Models\BusinessSetting;
use App\Models\Terminal;
use App\Models\Zone;
use App\Services\AfricasTalkingService;
use App\Services\TermiiService;
use Illuminate\Support\Facades\Http;



if (!function_exists('uptoken')) {

    function uptoken()
    {


        $upapikey = env('UPAPIKEY');
        $upurl = env('UPURL');
        $data = array(

            "requestType" => "inbound",
            "data" => [
                "email" => env('UPEMAIL'),
                "password" => env('UPPASS'),
            ],

        );

        $post_data = json_encode($data);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "$upurl",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                "X-Api-Key: $upapikey",
                'Content-Type: application/json'
            ),
        ));

        $var = curl_exec($curl);
        curl_close($curl);
        $var = json_decode($var);
        $status = $var->status ?? null;

        $token = $var->data->accessToken;

        if ($status == true) {
            return $token;
        }


    }
}


if (!function_exists('GetTermainalDetails')) {

    function GetTerminalDetails($SerialNo)
    {
        $details = Terminal::where('serialNumber', $SerialNo)->first() ?? null;
        unset($details->id);
        unset($details->createdAt);
        unset($details->updatedAt);
        return $details;

    }
}


if (!function_exists('error_response')) {

    function error_response($message)
    {
        return response()->json([
            'success' => false,
            'terminal' => null,
            'terminals' => null,
            'error' => $message,
        ], 200);
    }
}


if (!function_exists('error_pin_response')) {

    function error_pin_response($message)
    {
        return response()->json([
            'success' => false,
            'error' => $message,
        ], 200);
    }
}


if (!function_exists('user_balance')) {

    function user_balance($SerialNo)
    {
        $balance = Terminal::where('serialNumber', $SerialNo)->first()->accountBalance ?? null;
        return $balance;

    }
}


if (!function_exists('send_notification')) {

    function send_notification($message)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.telegram.org/bot8194613340:AAE6p179L1bim6PThWNjKq9cWS1P8O5X_Qk/sendMessage?chat_id=1316552414',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'chat_id' => "1316552414",
                'text' => $message,

            ),
            CURLOPT_HTTPHEADER => array(),
        ));

        $var = curl_exec($curl);
        curl_close($curl);

        $var = json_decode($var);
    }
}


function generateHash($vendor_code, $meter, $reference_id, $disco, $amount, $access_token, $pub_key, $priv_key)
{
    $combined_string = $vendor_code . "|" . $reference_id . "|" . $meter . "|" . $disco . "|" . $amount . "|" . $access_token . "|" . $pub_key;
    $computed_hash = hash_hmac("sha1", $combined_string, $priv_key);

    return $computed_hash;
}


function generateHashVerify($vendor_code, $meterNo, $trx, $disco_type, $pub_key, $priv_key)
{
    $combined_string = $vendor_code . "|" . $trx . "|" . $meterNo . "|" . $disco_type . "|" . $pub_key;
    $computed_hash = hash_hmac("sha1", $combined_string, $priv_key);

    return $computed_hash;
}


function get_token($meterNo, $disco_type)
{

    $url = env('IBDCURL');
    $trx = "EIBD" . random_int(0000000000, 9999999999);
    $pub_key = env('IBDCPUBKEY');
    $priv_key = env('IBDCPRIVKEY');
    $trx = str_pad(mt_rand(0, 999999999999), 12, '0', STR_PAD_LEFT); // Generate a 12-digit reference ID

    $vendor_code = env('IBDCVENDORCODE');

    $hash = generateHashVerify($vendor_code, $meterNo, $trx, $disco_type, $pub_key, $priv_key);


    $databody = array();
    $body = json_encode($databody);
    $curl = curl_init();
    curl_setopt_array($curl, array(

        CURLOPT_URL => $url . "get_meter_info.php?vendor_code=$vendor_code&reference_id=$trx&meter=$meterNo&disco=$disco_type&response_format=json&hash=$hash",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Content-Type: application/json',
        ),
    ));


    $var = curl_exec($curl);
    curl_close($curl);
    $var = json_decode($var);
    $status = $var->status ?? null;
    $message = $var->message ?? null;

    if ($status == "00" && $message = "OK") {
        return $var->access_token;
    } else {
        return $var->message ?? null;
    }


}


function geofence($lat, $lng, $serial_no)
{

    $deviceLat = $lat;
    $deviceLng = $lng;

    $zone_id = Terminal::where('serialNumber', $serial_no)->first()->geo_fence_id;
    $data['zone'] = Zone::where('id', $zone_id)->first();
    $geofenceCoordinates = [
        [
            'lat' => $data['zone']->lat_1 ?? 0,
            'lng' => $data['zone']->lng_1 ?? 0,
        ],
        [
            'lat' => $data['zone']->lat_2 ?? 0,
            'lng' => $data['zone']->lng_2 ?? 0,
        ],
        [
            'lat' => $data['zone']->lat_3 ?? 0,
            'lng' => $data['zone']->lng_3 ?? 0,
        ],
        [
            'lat' => $data['zone']->lat_4 ?? 0,
            'lng' => $data['zone']->lng_4 ?? 0,
        ]
    ];



    $isInside = isLocationInsideGeofence($deviceLat, $deviceLng, $geofenceCoordinates);
    return  $isInside;

}

function pointInPolygon($lat, $lng, $polygon)
{
    $inside = false;
    $n = count($polygon);
    $x = $lat;
    $y = $lng;
    $p1x = $polygon[0][0];
    $p1y = $polygon[0][1];
    for ($i = 1; $i <= $n; $i++) {
        $p2x = $polygon[$i % $n][0];
        $p2y = $polygon[$i % $n][1];
        if ($y > min($p1y, $p2y)) {
            if ($y <= max($p1y, $p2y)) {
                if ($x <= max($p1x, $p2x)) {
                    if ($p1y != $p2y) {
                        $xinters = ($y - $p1y) * ($p2x - $p1x) / ($p2y - $p1y) + $p1x;
                    }
                    if ($p1x == $p2x || $x <= $xinters) {
                        $inside = !$inside;
                    }
                }
            }
        }
        $p1x = $p2x;
        $p1y = $p2y;
    }


    return $inside;


}


function isLocationInsideGeofence($lat, $lng, $geofenceCoordinates)
{
    $polygon = [];
    foreach ($geofenceCoordinates as $coordinate) {
        $polygon[] = [$coordinate['lat'], $coordinate['lng']];
    }
    return pointInPolygon($lat, $lng, $polygon);
}



function sendNotificationToHttp(array|null $data): bool|string|null
{

    $get_key = BusinessSetting::where('key_name','server_key')->first()->value;
    $key = json_decode($get_key, false);

    $url = 'https://fcm.googleapis.com/v1/projects/' . $key->project_id . '/messages:send';
    $headers = [
        'Authorization' => 'Bearer ' . getAccessToken($key),
        'Content-Type' => 'application/json',
    ];
    try {
        return Http::withHeaders($headers)->post($url, $data);


    } catch (\Exception $exception) {
        return false;
    }
}


function sendDeviceNotification($device_id, $from, $transactionAmount, $sourceBankName )
{


    $postData = [
        'message' => [
            'token' => $device_id,
            'data' => [
                "sender_name" => "$from",
                "sender_bank" => "$sourceBankName",
                "amount" => "$transactionAmount",
                "sound" => "notification.wav",
                "android_channel_id" => "hexa-ride"
            ],
            'notification' => [
                "title" => "Incoming Transfer",
                "body" => $from . "| sent | NGN" . number_format($transactionAmount),
            ]
        ]
    ];

             return sendNotificationToHttp($postData);

//        return sendNotificationToHttp($url, $postdata, $header);
}


function getAccessToken($key): string
{
    $jwtToken = [
        'iss' => $key->client_email,
        'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        'aud' => 'https://oauth2.googleapis.com/token',
        'exp' => time() + 3600,
        'iat' => time(),
    ];
    $jwtHeader = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
    $jwtPayload = base64_encode(json_encode($jwtToken));
    $unsignedJwt = $jwtHeader . '.' . $jwtPayload;
    openssl_sign($unsignedJwt, $signature, $key->private_key, OPENSSL_ALGO_SHA256);
    $jwt = $unsignedJwt . '.' . base64_encode($signature);

    $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion' => $jwt,
    ]);
    return $response->json('access_token');
}


function send_sms_africa($phone, $message)
{
    $smsService = new AfricasTalkingService();
    $response = $smsService->sendSms($phone, $message);
    $responseData = json_decode(json_encode($response), true);


    if (isset($responseData['data']['SMSMessageData']['Recipients'][0])) {

        return 1;
    }


    return 0;



}


function send_sms_termii($phone, $message)
{

    $smsService = new TermiiService();
    $response = $smsService->sendSms($phone, $message);

    return response()->json($response);


}


