<?php

namespace App\Listeners;

use App\Events\OtpGenerated;
use App\Interfaces\Sms\SmsServiceInterface;
use Illuminate\Support\Facades\Log;

class OtpGeneratedListener
{
    protected SmsServiceInterface $smsService;

    /**
     * Create the event listener.
     */
    public function __construct(SmsServiceInterface $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Handle the event.
     */
    public function handle(OtpGenerated $event): void
    {
        $this->smsService->sendSms($event->phoneNumber, $event->otp);

        Log::info('OTP Generated', [
            'phone' => $event->phoneNumber,
            'otp' => $event->otp,
        ]);
    }
}
