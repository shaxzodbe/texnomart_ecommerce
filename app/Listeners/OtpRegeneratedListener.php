<?php

namespace App\Listeners;

use App\Events\OtpRegenerated;
use App\Interfaces\Sms\SmsServiceInterface;
use Illuminate\Support\Facades\Log;

class OtpRegeneratedListener
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
    public function handle(OtpRegenerated $event): void
    {
        $this->smsService->sendSms($event->phoneNumber, $event->otp);

        Log::info('OTP Regenerated', [
            'phone' => $event->phoneNumber,
            'otp' => $event->otp,
        ]);
    }
}
