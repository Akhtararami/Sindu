<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationOtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_access_registration_page()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Pendaftaran Akun Baru');
    }

    public function test_kader_registration_requires_no_address()
    {
        $response = $this->post('/register', [
            'name' => 'Kader Test',
            'email' => 'kader_test@sindu.id',
            'phone_number' => '081234567890',
            'role' => 'kader',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('verify-otp'));
        $this->assertTrue(session()->has('pending_user'));
        $this->assertEquals('kader', session('pending_user.role'));
        $this->assertNull(session('pending_user.address'));
    }

    public function test_registration_fails_if_password_contains_no_number()
    {
        $response = $this->post('/register', [
            'name' => 'Kader Test',
            'email' => 'kader_test@sindu.id',
            'phone_number' => '081234567890',
            'role' => 'kader',
            'password' => 'password', // tidak mengandung angka
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_parent_registration_requires_address()
    {
        // 1. Without address should fail
        $response = $this->post('/register', [
            'name' => 'Parent Test',
            'email' => 'parent_test@sindu.id',
            'phone_number' => '081234567890',
            'role' => 'user',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('address');

        // 2. With address should succeed
        $response2 = $this->post('/register', [
            'name' => 'Parent Test',
            'email' => 'parent_test@sindu.id',
            'phone_number' => '081234567890',
            'role' => 'user',
            'address' => 'Jalan Kebon Jeruk No. 10',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response2->assertRedirect(route('verify-otp'));
        $this->assertTrue(session()->has('pending_user'));
        $this->assertEquals('user', session('pending_user.role'));
        $this->assertEquals('Jalan Kebon Jeruk No. 10', session('pending_user.address'));
    }

    public function test_otp_verification_flow()
    {
        // 1. Submit registration
        $this->post('/register', [
            'name' => 'Parent Test',
            'email' => 'parent_test@sindu.id',
            'phone_number' => '081234567890',
            'role' => 'user',
            'address' => 'Jalan Kebon Jeruk No. 10',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $otpCode = session('otp_code');
        $this->assertNotNull($otpCode);

        // 2. Access verify page
        $response = $this->get('/verify-otp');
        $response->assertStatus(200);
        $response->assertSee('Verifikasi Kode OTP');

        // 3. Submit wrong OTP
        $responseWrong = $this->post('/verify-otp', [
            'otp' => '000000',
        ]);
        $responseWrong->assertSessionHasErrors('otp');
        $this->assertFalse(\Auth::check());

        // 4. Submit correct OTP
        $responseCorrect = $this->post('/verify-otp', [
            'otp' => (string) $otpCode,
        ]);

        $responseCorrect->assertRedirect('/');
        $this->assertTrue(\Auth::check());

        $user = \Auth::user();
        $this->assertEquals('Parent Test', $user->name);
        $this->assertEquals('parent_test@sindu.id', $user->email);
        $this->assertEquals('081234567890', $user->phone_number);
        $this->assertEquals('user', $user->role);
        $this->assertEquals('Jalan Kebon Jeruk No. 10', $user->address);
    }

    public function test_can_resend_otp()
    {
        // 1. Submit registration
        $this->post('/register', [
            'name' => 'Parent Test',
            'email' => 'parent_test@sindu.id',
            'phone_number' => '081234567890',
            'role' => 'user',
            'address' => 'Jalan Kebon Jeruk No. 10',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $firstOtp = session('otp_code');

        // 2. Resend
        $response = $this->post('/resend-otp');
        $response->assertRedirect();
        
        $secondOtp = session('otp_code');
        $this->assertNotEquals($firstOtp, $secondOtp);
    }
}
