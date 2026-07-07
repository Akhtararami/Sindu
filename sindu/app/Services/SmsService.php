<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Twilio\Rest\Client;

class SmsService
{
    /**
     * Send an OTP SMS to the given phone number.
     * Automatically formats Indonesian local numbers (08xx) to E.164 (+628xx).
     *
     * @param  string  $to    Destination phone number (local or E.164)
     * @param  string  $otp   The 6-digit OTP code
     * @param  string  $name  Recipient name for personalisation
     * @return bool
     */
    public function sendOtp(string $to, string $otp, string $name = ''): bool
    {
        $phone = $this->formatIndonesianNumber($to);

        $message = "SINDU Posyandu\n"
            . "Halo {$name},\n"
            . "Kode OTP registrasi Anda: {$otp}\n"
            . "Berlaku 5 menit. Jangan bagikan kode ini kepada siapapun.";

        // 1. Try Android SMS Gateway if configured
        if ($this->isAndroidSmsConfigured()) {
            return $this->sendViaAndroidSms($phone, $message, $otp);
        }

        // 2. Try Twilio if credentials are configured
        if ($this->isTwilioConfigured()) {
            return $this->sendViaTwilio($phone, $message, $otp);
        }

        // Fallback: log and write to otp.txt (development mode)
        Log::info("[SMS - Dev Mode] To: {$phone} | OTP: {$otp} | Name: {$name}");
        file_put_contents(base_path('otp.txt'), "=== OTP MASUK (SIMULASI SMS) ===\nNomor HP: {$phone}\nWaktu   : " . now()->toDateTimeString() . "\nKode OTP: {$otp}\n================================\n");
        return true;
    }

    /**
     * Convert Indonesian local format to E.164 international format.
     * 08xxxxxxxxxx  -> +628xxxxxxxxxx
     * 628xxxxxxxxxx -> +628xxxxxxxxxx
     * +628xxxxxxxxxx -> unchanged
     */
    public function formatIndonesianNumber(string $number): string
    {
        // Strip spaces, dashes, parentheses
        $number = preg_replace('/[\s\-\(\)]/', '', $number);

        if (str_starts_with($number, '+')) {
            return $number; // Already E.164
        }

        if (str_starts_with($number, '08')) {
            return '+62' . substr($number, 1); // 08xx -> +628xx
        }

        if (str_starts_with($number, '628')) {
            return '+' . $number; // 628xx -> +628xx
        }

        return '+' . $number; // Generic fallback
    }

    /**
     * Check whether Android SMS Gateway credentials are set in .env.
     */
    private function isAndroidSmsConfigured(): bool
    {
        return !empty(config('services.android_sms.token'))
            && !empty(config('services.android_sms.device_id'));
    }

    /**
     * Send SMS via SmsGateway24 Android API.
     */
    private function sendViaAndroidSms(string $to, string $message, string $otp): bool
    {
        try {
            $apiUrl = config('services.android_sms.api_url');
            $token = config('services.android_sms.token');
            $deviceId = config('services.android_sms.device_id');

            $response = Http::asForm()->post($apiUrl, [
                'token' => $token,
                'sendtoid' => $to,
                'body' => $message,
                'device_id' => $deviceId,
            ]);

            if ($response->successful()) {
                Log::info("[Android SMS Gateway] OTP {$otp} sent successfully to {$to}");
                return true;
            }

            Log::error("[Android SMS Gateway Error] Response failed: " . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error("[Android SMS Gateway Error] Failed to connect: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check whether Twilio credentials are set in .env.
     */
    private function isTwilioConfigured(): bool
    {
        return !empty(config('services.twilio.sid'))
            && !empty(config('services.twilio.token'))
            && !empty(config('services.twilio.from'));
    }

    /**
     * Send SMS via Twilio REST API.
     */
    private function sendViaTwilio(string $to, string $message, string $otp): bool
    {
        try {
            $client = new Client(
                config('services.twilio.sid'),
                config('services.twilio.token')
            );

            $client->messages->create($to, [
                'from' => config('services.twilio.from'),
                'body' => $message,
            ]);

            Log::info("[SMS Twilio] OTP {$otp} sent to {$to}");
            return true;
        } catch (\Exception $e) {
            Log::error("[SMS Twilio Error] Failed to send OTP to {$to}: " . $e->getMessage());
            return false;
        }
    }
}
