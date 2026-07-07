<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class OtpApiController extends Controller
{
    /**
     * Send OTP to the specified email.
     *
     * POST /api/send-otp
     * Payload: { "email": "user@example.com", "name": "Optional Name" }
     */
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $email = $request->email;
        $name = $request->name ?? 'Pengguna';
        $otp = rand(100000, 999999);

        try {
            // Send email using Laravel Mail
            Mail::raw(
                "Halo {$name},\n\nKode OTP verifikasi Anda di SINDU Posyandu adalah: {$otp}\n\nKode ini berlaku selama 5 menit. Jangan bagikan kode ini kepada siapapun.",
                function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Kode OTP Verifikasi - SINDU');
                }
            );

            // Save OTP to cache for 5 minutes
            Cache::put('api_otp_' . $email, $otp, now()->addMinutes(5));

            // Log and backup copy to file for dev testing convenience
            file_put_contents(base_path('otp.txt'), "=== API OTP MASUK (EMAIL) ===\nEmail   : {$email}\nWaktu   : " . now()->toDateTimeString() . "\nKode OTP: {$otp}\n=============================\n");

            return response()->json([
                'success' => true,
                'message' => "Kode OTP telah berhasil dikirim ke email ({$email})!",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Verify the sent OTP code.
     *
     * POST /api/verify-otp
     * Payload: { "email": "user@example.com", "otp": "123456" }
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $email = $request->email;
        $otp = $request->otp;

        $cachedOtp = Cache::get('api_otp_' . $email);

        if (!$cachedOtp) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP tidak ditemukan atau sudah kadaluarsa.',
            ], 400);
        }

        if ((string) $cachedOtp !== (string) $otp) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP yang Anda masukkan salah.',
            ], 400);
        }

        // OTP is correct - clear it from cache
        Cache::forget('api_otp_' . $email);

        return response()->json([
            'success' => true,
            'message' => 'Verifikasi OTP berhasil!',
        ]);
    }
}
