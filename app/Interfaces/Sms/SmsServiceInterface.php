<?php

namespace App\Interfaces\Sms;

interface SmsServiceInterface
{
    public function sendSms(string $phoneNumber, string $message): bool;
}
