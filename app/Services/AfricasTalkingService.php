<?php

namespace App\Services;

use AfricasTalking\SDK\AfricasTalking;

class AfricasTalkingService
{
    protected $sms;

    public function __construct()
    {
        $username = env('AFRICASTALKING_USERNAME');
        $apiKey = env('AFRICASTALKING_API_KEY');

        $africastalking = new AfricasTalking($username, $apiKey);
        $this->sms = $africastalking->sms();
    }

    public function sendSms($to, $message)
    {
        try {
            $result = $this->sms->send([
                'to'      => $to,
                'message' => $message,
            ]);

            return $result;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
