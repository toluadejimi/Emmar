<?php

namespace App\Services;

use App\Models\Account;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class BankOneService
{
    protected $client;
    protected $baseUrl;
    protected $token;
    protected $thirdpartybaseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->thirdpartybaseUrl = env('BANKONETHIRDPARTYURL');
        $this->baseUrl = env('BANKONEMAINURL');
        $this->token = env('BANKONEAUTHCODE');
    }

    public function getBvnDetails($bvn)
    {
        try {
            $response = $this->client->post($this->thirdpartybaseUrl."Account/BVN/GetBVNDetails", [
                'json' => [
                    'BVN' => $bvn,
                    'Token' => $this->token
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['RequestStatus'] && $data['isBvnValid']) {

                return [
                    'status' => 'success',
                    'bvn' => $data['bvnDetails']['BVN'],
                    'phone' => $data['bvnDetails']['phoneNumber'],
                    'first_name' => $data['bvnDetails']['FirstName'],
                    'last_name' => $data['bvnDetails']['LastName'],
                    'other_names' => $data['bvnDetails']['OtherNames'],
                    'dob' => $data['bvnDetails']['DOB'],
                ];

            }

            return 0;

        } catch (\Exception $e) {

            $message = "BVN FETCHING ERROR ====>>>". $e->getMessage();
            send_notification($message);
            return 0;
        }
    }

    public function createAccount($data, $user_id)
    {
        try {
            $response = $this->client->post($this->baseUrl . "Account/CreateAccountQuick/2", [
                'query' => ['authToken' => $this->token],
                'json' => $data,
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]);

            $body = json_decode($response->getBody(), true);
            $status = $body['IsSuccessful'] ?? null;

            if($status == true){

                $accs = new Account();
                $accs->user_id  = $user_id;
                $accs->customer_id  = $body['Message']['CustomerID'] ?? null;
                $accs->account_number  = $body['Message']['AccountNumber'];
                $accs->bankone_account_number  = $body['Message']['BankoneAccountNumber'] ?? null;
                $accs->account_name  = $body['Message']['FullName'];
                $accs->account_tier  = "Tier 1";
                $accs->account_type  = "Personal Account";
                $accs->save();

                return 1;

            }else{

                Log::error('Account Creation Failed', [
                    'error_message' => $body['Message'] ?? 'No error message',
                    'data' => $body
                ]);

                $message = "Account Creation Failed ===>>>>" . $body['Message'] ?? 'No error message';
                send_notification($message);

            }



        } catch (RequestException $e) {
            $message = $e->getMessage();
            send_notification($message);
            return 0;
        }
    }


}
