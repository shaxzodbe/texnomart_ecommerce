<?php

namespace App\Services\Sms;

use App\Events\OtpGenerated;
use App\Events\OtpRegenerated;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class OtpService
{
    protected int $otpExpiration = 3600;

    protected int $regenThreshold = 30;

    protected int $throttleLimit = 5;

    protected int $blockDuration = 3600;

    /**
     * @throws \Exception
     */
    public function generateOtp(string $phoneNumber): int
    {
        $otp = rand(100000, 999999);
        $cacheKey = 'otp_'.$phoneNumber;
        $cacheTimestampKey = 'otp_timestamp_'.$phoneNumber;
        $throttleKey = 'otp_throttle_'.$phoneNumber;

        $lastGeneratedAt = Cache::get($cacheTimestampKey);
        $requestCount = Cache::get($throttleKey);

        if ($requestCount >= $this->throttleLimit) {
            throw new Exception('Too many OTP requests. Please try again later.');
        }

        if ($lastGeneratedAt && now()->diffInSeconds($lastGeneratedAt) <= $this->regenThreshold) {
            throw new Exception('You can only request a new OTP every 30 seconds.');
        }

        if ($lastGeneratedAt && now()->diffInSeconds($lastGeneratedAt) <= $this->regenThreshold) {
            Event::dispatch(new OtpRegenerated($phoneNumber, $otp));
        } else {
            Event::dispatch(new OtpGenerated($phoneNumber, $otp));
        }

        Cache::put($cacheKey, $otp, now()->addSeconds($this->otpExpiration));
        Cache::put($cacheTimestampKey, now(), now()->addSeconds($this->otpExpiration));

        Cache::increment($throttleKey);
        Cache::put($throttleKey, $requestCount + 1, now()->addSeconds($this->blockDuration));

        return $otp;
    }

    public function verifyOtp(string $phoneNumber, int $otp): bool
    {
        $cacheKey = 'otp_'.$phoneNumber;
        $cachedOtp = Cache::get($cacheKey);

        if ($cachedOtp == $otp) {
            Cache::forget($cacheKey);

            return true;
        }

        return false;
    }

    private function logSMS(string $phoneNumber, int $otp): void
    {
        Log::info('Sending OTP', [
            'phone' => $phoneNumber,
            'otp' => $otp,
        ]);
    }
}
