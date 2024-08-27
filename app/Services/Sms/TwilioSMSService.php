<?php

namespace App\Services\Sms;

use App\Interfaces\Sms\SmsServiceInterface;
use Exception;
use Twilio\Rest\Client;

class TwilioSMSService implements SMSServiceInterface
{
    protected Client $client;

    protected string $from;

    /**
     * @throws \Twilio\Exceptions\ConfigurationException
     */
    public function __construct()
    {
        $this->client = new Client(
            config('services.twilio.account_sid'),
            config('services.twilio.auth_token')
        );
        $this->from = config('services.twilio.from');
    }

    /**
     * Send an SMS and return a response.
     */
    public function sendSms(string $phoneNumber, string $message): bool
    {
        try {
            $this->client->messages->create($phoneNumber, [
                'from' => $this->from,
                'body' => $message,
            ]);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
