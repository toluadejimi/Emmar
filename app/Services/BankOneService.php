<?php

namespace App\Services;

use App\Models\Account;
use App\Models\BankLogo;
use GuzzleHttp\Client;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
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
            $response = $this->client->post($this->thirdpartybaseUrl . "Account/BVN/GetBVNDetails", [
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

            $message = "BVN FETCHING ERROR ====>>>" . $e->getMessage();
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


            if ($status == true) {

                $accs = new Account();
                $accs->user_id = $user_id;
                $accs->customer_id = $body['Message']['CustomerID'] ?? null;
                $accs->account_number = $body['Message']['AccountNumber'];
                $accs->bankone_account_number = $body['Message']['BankoneAccountNumber'] ?? null;
                $accs->account_name = $body['Message']['FullName'];
                $accs->account_tier = "Tier 1";
                $accs->account_type = "Personal Account";
                $accs->save();


                Log::info('Account Created', [
                    'data' => $body,
                    'user_info' => $data
                ]);


                $data['status'] = 1;
                $data['account_no'] = $body['Message']['AccountNumber'];
                return $data;


            } else {

                Log::error('Account Creation Failed', [
                    'error_message' => $body['Message'] ?? 'No error message',
                    'data' => $body
                ]);

//                $message = "Account Creation Failed ===>>>>" . $body['Message'] ?? 'No error message';
//                send_notification($message);

            }


        } catch (RequestException $e) {
            $message = $e->getMessage();
            send_notification($message);
            $data['status'] = 0;
            $data['message'] = $e->getMessage();
            return $data;
        }
    }


    public function get_balance($account)
    {
        try {
            $response = $this->client->get($this->baseUrl . "Account/GetAccountByAccountNumber/2", [
                'query' => [
                    'authtoken' => $this->token,
                    'accountNumber' => $account,
                    'computewithdrawableBalance' => 'false'
                ],
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]);

            $data = json_decode($response->getBody(), true) ?? null;


            if ($data !== null) {
                return [
                    'account_number' => $account,
                    'availabe_balance' => $data['AvailableBalance'] ?? 'N/A',
                    'ledger_balance' => $data['LedgerBalance'] ?? 'N/A',
                    'withdrawable_balance' => $data['WithdrawableBalance'] ?? 'N/A',
                    'account_type' => $data['AccountType'] ?? 'N/A'
                ];
            }


            return 0;


        } catch (\Exception $e) {

            $message = "Account Balance Error ====>>>" . $e->getMessage();
            send_notification($message);
            return 0;
        }
    }


    public function getCommercialBanks()
    {


        try {
            $response = $this->client->get($this->thirdpartybaseUrl . "BillsPayment/GetCommercialBanks/{$this->token}", [
                'headers' => ['Accept' => 'application/json']
            ]);

            $data = json_decode($response->getBody(), true);

            if (is_array($data)) {

                foreach ($data as $bank) {

                     $bank_logo = BankLogo::where('name', $bank['Name'])->value('url') ?? null;



//                    BankLogo::updateOrCreate(
//                        [
//                            'name' => $bank['Name'],
//                            'code' => $bank['Code']
//                        ]
//                    );


                    $banks[] = [
                        'code' => $bank['Code'],
                        'name' => $bank['Name'],
                        'logo' => url('')."/storage/app/public/bankslogo/".$bank_logo
                    ];
                }

               return $banks;

            }

            return 0;


        } catch (\Exception $e) {
            $message = "Fetch Bank Balance Error ====>>>" . $e->getMessage();
            send_notification($message);
            return 0;
        }
    }



    public function getTransactions(request $request)
    {

        try {
            $response = $this->client->get($this->thirdpartybaseUrl . "BillsPayment/GetCommercialBanks/{$this->token}", [
                'headers' => ['Accept' => 'application/json']
            ]);

            $data = json_decode($response->getBody(), true);

            if (is_array($data)) {

                foreach ($data as $bank) {

                    $bank_logo = BankLogo::where('name', $bank['Name'])->value('url') ?? null;



//                    BankLogo::updateOrCreate(
//                        [
//                            'name' => $bank['Name'],
//                            'code' => $bank['Code']
//                        ]
//                    );


                    $banks[] = [
                        'code' => $bank['Code'],
                        'name' => $bank['Name'],
                        'logo' => url('')."/storage/app/public/bankslogo/".$bank_logo
                    ];
                }

                return $banks;

            }

            return 0;


        } catch (\Exception $e) {
            $message = "Fetch Bank Balance Error ====>>>" . $e->getMessage();
            send_notification($message);
            return 0;
        }
    }

}
