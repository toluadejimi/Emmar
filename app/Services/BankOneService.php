<?php

namespace App\Services;

use App\Models\Account;
use App\Models\BankLogo;
use GuzzleHttp\Client;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
                    'other_name' => $data['bvnDetails']['OtherNames'],
                    'dob' => $data['bvnDetails']['DOB'],
                ];

            }else{

                $message = "BVN FETCHING ERROR ====>>>" . json_encode($data);
                send_notification($message);
                return 0;

            }


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


                $message = "Account Creation Failed ===>>>>\n\n" . json_encode($body) . "\n\n Request =>>>>>" . json_encode($data);
                send_notification($message);

            }


        } catch (RequestException $e) {
            $message = $e->getMessage();
            send_notification($message);
            $data['status'] = 0;
            $data['message'] = $e->getMessage();
            return $data;
        }
    }


    public function initiate_bank_transfer($data)
    {
        try {
            $data_sent = $data;
            $data_sent['Token'] = $this->token;
            $response = $this->client->post($this->thirdpartybaseUrl . "Transfer/InterBankTransfer", [
                'json' => $data_sent,
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]);

            $body = json_decode($response->getBody(), true);
            $status =  $body['Status'] ?? null;
            $code =  $body['ResponseCode'] ?? null;


            if ($status == "Successful" && $code == "00") {

                return [
                    'Status' => true,
                    'SessionID' => $body['SessionID'] ?? "10000000000000",
                ];

            } else {

                $message = "Bank Transfer Failed ===>>>>" . json_encode($body) ?? 'No error message' ." \n\n Request=====> ".json_encode($data_sent);
                send_notification($message);

                return [
                    'Status' => false,
                    'Message' => $body['ResponseMessage'],
                ];


            }


        } catch (RequestException $e) {
            $message = $e->getMessage();
            send_notification($message);
            $data['status'] = 0;
            $data['message'] = $e->getMessage();
            return $data;
        }
    }
    public function initiate_local_transfer($data)
    {
        try {
            $data_sent = $data;
            $data_sent['Token'] = $this->token;
            $response = $this->client->post($this->thirdpartybaseUrl . "CoreTransactions/LocalFundsTransfer", [
                'json' => $data_sent,
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]);

            $body = json_decode($response->getBody(), true);
            $status =  true; //$body['IsSuccessful'] ?? null;



            if ($status == true) {

                return [
                    'Status' => true,
                    'SessionID' => $body['SessionID'] ?? "10000000000000",
                ];

            } else {

                $message = "Bank Transfer Failed ===>>>>" . json_encode($body) ?? 'No error message';
                send_notification($message);

                return [
                    'Status' => false,
                    'Message' => $body['ResponseMessage'],
                ];


            }


        } catch (RequestException $e) {
            $message = $e->getMessage();
            send_notification($message);
            $data['status'] = 0;
            $data['message'] = $e->getMessage();
            return $data;
        }
    }
    public function initiate_customer_debit($data)
    {
        try {
            $data_sent = $data;
            $data_sent['Token'] = $this->token;
            $data_sent['GLCode'] = env("GLCODE");
            $response = $this->client->post($this->thirdpartybaseUrl . "apiservice/CoreTransactions/Debit", [
                'json' => $data_sent,
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]);

            $body = json_decode($response->getBody(), true);
            $status =  $body['IsSuccessful'] ?? null;



            if ($status == true) {

                return [
                    'Status' => true,
                    'ResponseCode' => $body['ResponseCode'],
                    'Reference' => $body['Reference'],
                ];

            } else {

                $message = "Bank Transfer Failed ===>>>>" . json_encode($body) ?? 'No error message';
                send_notification($message);

                return [
                    'Status' => false,
                    'Message' => $body['ResponseMessage'],
                ];


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

                $cleaned_availabe = str_replace(',', '', $data['AvailableBalance']);
                $avilable_balance = (int)$cleaned_availabe;

                $legder_availabe = str_replace(',', '', $data['LedgerBalance']);
                $ledger_balance = (int)$legder_availabe;


                return [
                    'account_number' => $account,
                    'availabe_balance' => $avilable_balance ?? 'N/A',
                    'ledger_balance' => $ledger_balance ?? 'N/A',
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

                     $bank_logo = BankLogo::where('name', $bank['Name'])->value('logo') ?? null;



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

    public function name_enquiry($code, $account_no){

        try {
            $response = $this->client->post($this->thirdpartybaseUrl . "Transfer/NameEnquiry", [
                'json' => [
                    'AccountNumber' => $account_no,
                    'Token' => $this->token,
                    'BankCode' => $code
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ]
            ]);

            $data = json_decode($response->getBody(), true);



            if ($data['Name'] != null) {

                return [
                    'codes' => 1,
                    'name' => $data['Name'],
                    'session_id' => $data['SessionID'],
                    'BVN' => $data['BVN'],
                    'KYC' => $data['KYC']
                ];

            }

            return [
                'codes' => 0,
                'message' => $data['ResponseMessage']
            ];

        } catch  (\Exception $e) {
            $message = "Name Inquiry  Error ====>>>" . $e->getMessage();
            send_notification($message);
            return 0;
        }



    }

    public function getTransactions($accountNumber, $from, $to)
    {

        try {


            if($from == null && $to == null){
                $response = $this->client->get($this->baseUrl . "Account/GetTransactions/2/?authtoken={$this->token}&accountNumber=$accountNumber&numberOfItems=500", [
                    'headers' => ['Accept' => 'application/json']
                ]);

                $data = json_decode($response->getBody(), true);
                $status = $data['IsSuccessful'] ?? null;


            }elseif($from != null && $to == null){
                $from_date = $from;
                $response = $this->client->get($this->baseUrl . "Account/GetTransactions/2/?authtoken={$this->token}&accountNumber=$accountNumber&fromDate=$from_date&numberOfItems=500", [
                    'headers' => ['Accept' => 'application/json']
                ]);

                $data = json_decode($response->getBody(), true);
                $status = $data['IsSuccessful'] ?? null;


            }elseif($from != null && $to != null){

                $from_date = $from;
                $to_date = $to;

                $response = $this->client->get($this->baseUrl . "Account/GetTransactions/2/?authtoken={$this->token}&accountNumber=$accountNumber&fromDate=$from_date&toDate=$to_date&numberOfItems=500", [
                    'headers' => ['Accept' => 'application/json']
                ]);

                $data = json_decode($response->getBody(), true);
                $status = $data['IsSuccessful'] ?? null;
            }


            if($status == true){
                return[
                    'status' => 2,
                    'message' => $data['Message']
                ];

            }else{

                $data2['status'] = 0;
                $data2['message'] = $data['Message'] ?? "NA";
                return $data2;
            }






        } catch (\Exception $e) {
            $message = "Fetch Bank Balance Error ====>>>" . $e->getMessage();
            send_notification($message);
            return 0;
        }
    }


    public function get_airtime_biller(){

        try {

            $Token = $this->token;
            $response = $this->client->get($this->thirdpartybaseUrl . "BillsPayment/GetBillers/{$Token}", [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]);

            $data = json_decode($response->getBody(), true) ?? [];

            $formatted = [];

            foreach ($data as $biller) {
                if ($biller['CategoryId'] == "3") {
                    $formatted[] = [
                        "CategoryId" => $biller['CategoryId'],
                        "BillerID" => $biller['BillerID'],
                        "BillerCategoryID" => $biller['BillerCategoryID'],
                        "Narration" => $biller['Narration'],
                        "CurrencyCode" => $biller['CurrencyCode'],
                        "CurrencySymbol" => $biller['CurrencySymbol'],
                        "CustomerField1" => $biller['CustomerField1'],
                        "CustomerField2" => $biller['CustomerField2'],
                        "SupportEmail" => $biller['SupportEmail'],
                        "Surcharge" => $biller['Surcharge'],
                        "Url" => $biller['Url'],
                        "LogoUrl" => $biller['LogoUrl'],
                        "IsActive" => $biller['IsActive'],
                        "ShortName" => $biller['ShortName'],
                        "CustomSectionUrl" => $biller['CustomSectionUrl'],
                        "ID" => $biller['ID'],
                        "Name" => $biller['Name'],
                        "Status" => $biller['Status'],
                        "StatusDetails" => $biller['StatusDetails'],
                        "RequestStatus" => $biller['RequestStatus'],
                        "ResponseDescription" => $biller['ResponseDescription'],
                        "ResponseStatus" => $biller['ResponseStatus'],
                    ];
                }
            }

            return $formatted;

        } catch (\Exception $e) {

            $message = "Account Balance Error ====>>>" . $e->getMessage();
            send_notification($message);
            return 0;
        }


    }
    public function get_data_biller(){

        try {

            $Token = $this->token;
            $response = $this->client->get($this->thirdpartybaseUrl . "BillsPayment/GetBillers/{$Token}", [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]);

            $data = json_decode($response->getBody(), true) ?? [];

            $formatted = [];

            foreach ($data as $biller) {
                if ($biller['CategoryId'] == "4") {
                    $formatted[] = [
                        "CategoryId" => $biller['CategoryId'],
                        "BillerID" => $biller['BillerID'],
                        "BillerCategoryID" => $biller['BillerCategoryID'],
                        "Narration" => $biller['Narration'],
                        "CurrencyCode" => $biller['CurrencyCode'],
                        "CurrencySymbol" => $biller['CurrencySymbol'],
                        "CustomerField1" => $biller['CustomerField1'],
                        "CustomerField2" => $biller['CustomerField2'],
                        "SupportEmail" => $biller['SupportEmail'],
                        "Surcharge" => $biller['Surcharge'],
                        "Url" => $biller['Url'],
                        "LogoUrl" => $biller['LogoUrl'],
                        "IsActive" => $biller['IsActive'],
                        "ShortName" => $biller['ShortName'],
                        "CustomSectionUrl" => $biller['CustomSectionUrl'],
                        "ID" => $biller['ID'],
                        "Name" => $biller['Name'],
                        "Status" => $biller['Status'],
                        "StatusDetails" => $biller['StatusDetails'],
                        "RequestStatus" => $biller['RequestStatus'],
                        "ResponseDescription" => $biller['ResponseDescription'],
                        "ResponseStatus" => $biller['ResponseStatus'],
                    ];
                }
            }

            return $formatted;

        } catch (\Exception $e) {

            $message = "Account Balance Error ====>>>" . $e->getMessage();
            send_notification($message);
            return 0;
        }


    }
    public function get_cable_biller(){

        try {

            $Token = $this->token;
            $response = $this->client->get($this->thirdpartybaseUrl . "BillsPayment/GetBillers/{$Token}", [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]);

            $data = json_decode($response->getBody(), true) ?? [];

            $formatted = [];

            foreach ($data as $biller) {
                if ($biller['CategoryId'] == "2") {
                    $formatted[] = [
                        "CategoryId" => $biller['CategoryId'],
                        "BillerID" => $biller['BillerID'],
                        "BillerCategoryID" => $biller['BillerCategoryID'],
                        "Narration" => $biller['Narration'],
                        "CurrencyCode" => $biller['CurrencyCode'],
                        "CurrencySymbol" => $biller['CurrencySymbol'],
                        "CustomerField1" => $biller['CustomerField1'],
                        "CustomerField2" => $biller['CustomerField2'],
                        "SupportEmail" => $biller['SupportEmail'],
                        "Surcharge" => $biller['Surcharge'],
                        "Url" => $biller['Url'],
                        "LogoUrl" => $biller['LogoUrl'],
                        "IsActive" => $biller['IsActive'],
                        "ShortName" => $biller['ShortName'],
                        "CustomSectionUrl" => $biller['CustomSectionUrl'],
                        "ID" => $biller['ID'],
                        "Name" => $biller['Name'],
                        "Status" => $biller['Status'],
                        "StatusDetails" => $biller['StatusDetails'],
                        "RequestStatus" => $biller['RequestStatus'],
                        "ResponseDescription" => $biller['ResponseDescription'],
                        "ResponseStatus" => $biller['ResponseStatus'],
                    ];
                }
            }

            return $formatted;

        } catch (\Exception $e) {

            $message = "Account Balance Error ====>>>" . $e->getMessage();
            send_notification($message);
            return 0;
        }


    }
    public function get_exams_biller(){

        try {

            $Token = $this->token;
            $response = $this->client->get($this->thirdpartybaseUrl . "BillsPayment/GetBillers/{$Token}", [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]);

            $data = json_decode($response->getBody(), true) ?? [];

            $formatted = [];

            foreach ($data as $biller) {
                if ($biller['ID'] == "3588") {
                    $formatted[] = [
                        "CategoryId" => $biller['CategoryId'],
                        "BillerID" => $biller['BillerID'],
                        "BillerCategoryID" => $biller['BillerCategoryID'],
                        "Narration" => $biller['Narration'],
                        "CurrencyCode" => $biller['CurrencyCode'],
                        "CurrencySymbol" => $biller['CurrencySymbol'],
                        "CustomerField1" => $biller['CustomerField1'],
                        "CustomerField2" => $biller['CustomerField2'],
                        "SupportEmail" => $biller['SupportEmail'],
                        "Surcharge" => $biller['Surcharge'],
                        "Url" => $biller['Url'],
                        "LogoUrl" => $biller['LogoUrl'],
                        "IsActive" => $biller['IsActive'],
                        "ShortName" => $biller['ShortName'],
                        "CustomSectionUrl" => $biller['CustomSectionUrl'],
                        "ID" => $biller['ID'],
                        "Name" => $biller['Name'],
                        "Status" => $biller['Status'],
                        "StatusDetails" => $biller['StatusDetails'],
                        "RequestStatus" => $biller['RequestStatus'],
                        "ResponseDescription" => $biller['ResponseDescription'],
                        "ResponseStatus" => $biller['ResponseStatus'],
                    ];
                }
            }

            return $formatted;

        } catch (\Exception $e) {

            $message = "Account Balance Error ====>>>" . $e->getMessage();
            send_notification($message);
            return 0;
        }


    }
    public function get_waste_biller(){

        try {

            $Token = $this->token;
            $response = $this->client->get($this->thirdpartybaseUrl . "BillsPayment/GetBillers/{$Token}", [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]);

            $data = json_decode($response->getBody(), true) ?? [];

            $formatted = [];

            foreach ($data as $biller) {
                if ($biller['ID'] == "3511") {
//               if($biller['BillerID'] == 0){
                    $formatted[] = [
                        "CategoryId" => $biller['CategoryId'],
                        "BillerID" => $biller['BillerID'],
                        "BillerCategoryID" => $biller['BillerCategoryID'],
                        "Narration" => $biller['Narration'],
                        "CurrencyCode" => $biller['CurrencyCode'],
                        "CurrencySymbol" => $biller['CurrencySymbol'],
                        "CustomerField1" => $biller['CustomerField1'],
                        "CustomerField2" => $biller['CustomerField2'],
                        "SupportEmail" => $biller['SupportEmail'],
                        "Surcharge" => $biller['Surcharge'],
                        "Url" => $biller['Url'],
                        "LogoUrl" => $biller['LogoUrl'],
                        "IsActive" => $biller['IsActive'],
                        "ShortName" => $biller['ShortName'],
                        "CustomSectionUrl" => $biller['CustomSectionUrl'],
                        "ID" => $biller['ID'],
                        "Name" => $biller['Name'],
                        "Status" => $biller['Status'],
                        "StatusDetails" => $biller['StatusDetails'],
                        "RequestStatus" => $biller['RequestStatus'],
                        "ResponseDescription" => $biller['ResponseDescription'],
                        "ResponseStatus" => $biller['ResponseStatus'],
                    ];
                }
            }

            return $formatted;

        } catch (\Exception $e) {

            $message = "Account Balance Error ====>>>" . $e->getMessage();
            send_notification($message);
            return 0;
        }


    }
    public function get_electric_biller(){

        try {

            $Token = $this->token;
            $response = $this->client->get($this->thirdpartybaseUrl . "BillsPayment/GetBillers/{$Token}", [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]);

            $data = json_decode($response->getBody(), true) ?? [];

            $formatted = [];

            foreach ($data as $biller) {
                if ($biller['CategoryId'] == "1") {
//               if($biller['BillerID'] == 0){
                    $formatted[] = [
                        "CategoryId" => $biller['CategoryId'],
                        "BillerID" => $biller['BillerID'],
                        "BillerCategoryID" => $biller['BillerCategoryID'],
                        "Narration" => $biller['Narration'],
                        "CurrencyCode" => $biller['CurrencyCode'],
                        "CurrencySymbol" => $biller['CurrencySymbol'],
                        "CustomerField1" => $biller['CustomerField1'],
                        "CustomerField2" => $biller['CustomerField2'],
                        "SupportEmail" => $biller['SupportEmail'],
                        "Surcharge" => $biller['Surcharge'],
                        "Url" => $biller['Url'],
                        "LogoUrl" => $biller['LogoUrl'],
                        "IsActive" => $biller['IsActive'],
                        "ShortName" => $biller['ShortName'],
                        "CustomSectionUrl" => $biller['CustomSectionUrl'],
                        "ID" => $biller['ID'],
                        "Name" => $biller['Name'],
                        "Status" => $biller['Status'],
                        "StatusDetails" => $biller['StatusDetails'],
                        "RequestStatus" => $biller['RequestStatus'],
                        "ResponseDescription" => $biller['ResponseDescription'],
                        "ResponseStatus" => $biller['ResponseStatus'],
                    ];
                }
            }

            return $formatted;

        } catch (\Exception $e) {

            $message = "Account Balance Error ====>>>" . $e->getMessage();
            send_notification($message);
            return 0;
        }


    }
    public function get_betting_biller(){

        try {

            $Token = $this->token;
            $response = $this->client->get($this->thirdpartybaseUrl . "BillsPayment/GetBillers/{$Token}", [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]);

            $data = json_decode($response->getBody(), true) ?? [];

            $formatted = [];

            foreach ($data as $biller) {
               if ($biller['CategoryId'] == "41") {
              // if($biller['BillerID'] == 0){
                    $formatted[] = [
                        "CategoryId" => $biller['CategoryId'],
                        "BillerID" => $biller['BillerID'],
                        "BillerCategoryID" => $biller['BillerCategoryID'],
                        "Narration" => $biller['Narration'],
                        "CurrencyCode" => $biller['CurrencyCode'],
                        "CurrencySymbol" => $biller['CurrencySymbol'],
                        "CustomerField1" => $biller['CustomerField1'],
                        "CustomerField2" => $biller['CustomerField2'],
                        "SupportEmail" => $biller['SupportEmail'],
                        "Surcharge" => $biller['Surcharge'],
                        "Url" => $biller['Url'],
                        "LogoUrl" => $biller['LogoUrl'],
                        "IsActive" => $biller['IsActive'],
                        "ShortName" => $biller['ShortName'],
                        "CustomSectionUrl" => $biller['CustomSectionUrl'],
                        "ID" => $biller['ID'],
                        "Name" => $biller['Name'],
                        "Status" => $biller['Status'],
                        "StatusDetails" => $biller['StatusDetails'],
                        "RequestStatus" => $biller['RequestStatus'],
                        "ResponseDescription" => $biller['ResponseDescription'],
                        "ResponseStatus" => $biller['ResponseStatus'],
                    ];
                }
            }

            return $formatted;

        } catch (\Exception $e) {

            $message = "Account Balance Error ====>>>" . $e->getMessage();
            send_notification($message);
            return 0;
        }


    }


    public function get_associations_sociery_biller(){

        try {

            $Token = $this->token;
            $response = $this->client->get($this->thirdpartybaseUrl . "BillsPayment/GetBillers/{$Token}", [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]);

            $data = json_decode($response->getBody(), true) ?? [];

            $formatted = [];

            foreach ($data as $biller) {
               if ($biller['CategoryId'] == "28") {
                    $formatted[] = [
                        "CategoryId" => $biller['CategoryId'],
                        "BillerID" => $biller['BillerID'],
                        "BillerCategoryID" => $biller['BillerCategoryID'],
                        "Narration" => $biller['Narration'],
                        "CurrencyCode" => $biller['CurrencyCode'],
                        "CurrencySymbol" => $biller['CurrencySymbol'],
                        "CustomerField1" => $biller['CustomerField1'],
                        "CustomerField2" => $biller['CustomerField2'],
                        "SupportEmail" => $biller['SupportEmail'],
                        "Surcharge" => $biller['Surcharge'],
                        "Url" => $biller['Url'],
                        "LogoUrl" => $biller['LogoUrl'],
                        "IsActive" => $biller['IsActive'],
                        "ShortName" => $biller['ShortName'],
                        "CustomSectionUrl" => $biller['CustomSectionUrl'],
                        "ID" => $biller['ID'],
                        "Name" => $biller['Name'],
                        "Status" => $biller['Status'],
                        "StatusDetails" => $biller['StatusDetails'],
                        "RequestStatus" => $biller['RequestStatus'],
                        "ResponseDescription" => $biller['ResponseDescription'],
                        "ResponseStatus" => $biller['ResponseStatus'],
                    ];
                }
            }

            return $formatted;

        } catch (\Exception $e) {

            $message = "Account Balance Error ====>>>" . $e->getMessage();
            send_notification($message);
            return 0;
        }


    }
    public function get_tax_biller(){

        try {

            $Token = $this->token;
            $response = $this->client->get($this->thirdpartybaseUrl . "BillsPayment/GetBillers/{$Token}", [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]);

            $data = json_decode($response->getBody(), true) ?? [];

            $formatted = [];

            foreach ($data as $biller) {
               if ($biller['CategoryId'] == "26") {
                    $formatted[] = [
                        "CategoryId" => $biller['CategoryId'],
                        "BillerID" => $biller['BillerID'],
                        "BillerCategoryID" => $biller['BillerCategoryID'],
                        "Narration" => $biller['Narration'],
                        "CurrencyCode" => $biller['CurrencyCode'],
                        "CurrencySymbol" => $biller['CurrencySymbol'],
                        "CustomerField1" => $biller['CustomerField1'],
                        "CustomerField2" => $biller['CustomerField2'],
                        "SupportEmail" => $biller['SupportEmail'],
                        "Surcharge" => $biller['Surcharge'],
                        "Url" => $biller['Url'],
                        "LogoUrl" => $biller['LogoUrl'],
                        "IsActive" => $biller['IsActive'],
                        "ShortName" => $biller['ShortName'],
                        "CustomSectionUrl" => $biller['CustomSectionUrl'],
                        "ID" => $biller['ID'],
                        "Name" => $biller['Name'],
                        "Status" => $biller['Status'],
                        "StatusDetails" => $biller['StatusDetails'],
                        "RequestStatus" => $biller['RequestStatus'],
                        "ResponseDescription" => $biller['ResponseDescription'],
                        "ResponseStatus" => $biller['ResponseStatus'],
                    ];
                }
            }

            return $formatted;

        } catch (\Exception $e) {

            $message = "Account Balance Error ====>>>" . $e->getMessage();
            send_notification($message);
            return 0;
        }


    }



    public function pay_bills($data)
    {


        try {
            $data_sent = $data;
            $data_sent['Token'] = $this->token;
            $response = $this->client->post($this->thirdpartybaseUrl . "BillsPayment/Payment", [
                'json' => $data_sent,
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]);

            $body = json_decode($response->getBody(), true);
            $status =  true; //$body['IsSuccessful'] ?? null;


            dd($body);

            if ($status == true) {

                return [
                    'Status' => true,
                    'SessionID' => $body['SessionID'] ?? "10000000000000",
                ];

            } else {

                $message = "Bank Transfer Failed ===>>>>" . json_encode($body) ?? 'No error message';
                send_notification($message);

                return [
                    'Status' => false,
                    'Message' => $body['ResponseMessage'],
                ];


            }


        } catch (RequestException $e) {
            $message = $e->getMessage();
            send_notification($message);
            $data['status'] = 0;
            $data['message'] = $e->getMessage();
            return $data;
        }


    }




}
