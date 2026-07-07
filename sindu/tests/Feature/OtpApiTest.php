<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class OtpApiTest extends TestCase
{
    public function test_can_request_otp_via_api()
    {
        Mail::fake();

        $response = $this->postJson('/api/send-otp', [
            'email' => 'api_test@sindu.id',
            'name' => 'API Test User',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertNotNull(Cache::get('api_otp_api_test@sindu.id'));
    }

    public function test_validation_fails_on_invalid_email()
    {
        $response = $this->postJson('/api/send-otp', [
            'email' => 'not-an-email',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Validasi gagal.',
        ]);
    }

    public function test_can_verify_otp_via_api()
    {
        // 1. Setup a cached OTP code
        Cache::put('api_otp_verify@sindu.id', '123456', now()->addMinutes(5));

        // 2. Submit wrong OTP
        $responseWrong = $this->postJson('/api/verify-otp', [
            'email' => 'verify@sindu.id',
            'otp' => '000000',
        ]);

        $responseWrong->assertStatus(400);
        $responseWrong->assertJson([
            'success' => false,
            'message' => 'Kode OTP yang Anda masukkan salah.',
        ]);

        // 3. Submit correct OTP
        $responseCorrect = $this->postJson('/api/verify-otp', [
            'email' => 'verify@sindu.id',
            'otp' => '123456',
        ]);

        $responseCorrect->assertStatus(200);
        $responseCorrect->assertJson([
            'success' => true,
            'message' => 'Verifikasi OTP berhasil!',
        ]);

        // 4. Verify it was cleared from cache
        $this->assertNull(Cache::get('api_otp_verify@sindu.id'));
    }
}
