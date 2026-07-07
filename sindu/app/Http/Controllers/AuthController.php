<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/')
                ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20',
            'role' => 'required|in:kader,user',
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/[0-9]/', // Harus mengandung angka/nomor
            ],
        ];

        $messages = [
            'password.regex' => 'Password harus mengandung setidaknya satu nomor.',
            'password.min' => 'Password harus memiliki panjang minimal 6 karakter.',
        ];

        // Alamat is required if role is Orang Tua / Wali (user)
        if ($request->role === 'user') {
            $rules['address'] = 'required|string';
        }

        $validated = $request->validate($rules, $messages);

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        // Save registration details to session
        $pendingUser = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'role' => $validated['role'],
            'address' => $request->role === 'user' ? $validated['address'] : null,
            'password' => Hash::make($validated['password']),
        ];

        session([
            'pending_user' => $pendingUser,
            'otp_code' => $otp,
            'otp_channel' => 'email',
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        // Kirim OTP ke email
        $this->dispatchOtp($pendingUser, $otp);

        return redirect()->route('verify-otp')
            ->with('success', 'Kode OTP telah dikirim ke email yang tercantum (' . $validated['email'] . ')!');
    }

    public function showVerifyOtp()
    {
        if (!session()->has('pending_user')) {
            return redirect('/register');
        }

        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        if (!session()->has('pending_user')) {
            return redirect('/register')->withErrors(['error' => 'Sesi registrasi telah kedaluwarsa.']);
        }

        $sessionOtp = session('otp_code');
        $expiresAt = session('otp_expires_at');

        if (now()->greaterThan($expiresAt)) {
            return back()->withErrors(['otp' => 'Kode OTP telah kedaluwarsa. Silakan kirim ulang kode OTP.']);
        }

        if ($request->otp != $sessionOtp) {
            return back()->withErrors(['otp' => 'Kode OTP yang Anda masukkan salah.']);
        }

        $pendingUser = session('pending_user');
        
        $user = User::create([
            'name' => $pendingUser['name'],
            'email' => $pendingUser['email'],
            'phone_number' => $pendingUser['phone_number'],
            'role' => $pendingUser['role'],
            'address' => $pendingUser['address'],
            'password' => $pendingUser['password'],
        ]);

        // Clean session
        session()->forget(['pending_user', 'otp_code', 'otp_channel', 'otp_expires_at']);

        Auth::login($user);

        return redirect('/')
            ->with('success', 'Registrasi berhasil! Selamat datang di SINDU.');
    }

    public function resendOtp()
    {
        if (!session()->has('pending_user')) {
            return redirect('/register');
        }

        $otp = rand(100000, 999999);
        $pendingUser = session('pending_user');

        session([
            'otp_code' => $otp,
            'otp_channel' => 'email',
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        $this->dispatchOtp($pendingUser, $otp);

        return back()->with('success', 'Kode OTP baru telah dikirim ke email yang tercantum (' . $pendingUser['email'] . ')!');
    }

    /**
     * Send OTP code to the user's email.
     */
    protected function dispatchOtp($user, $otp)
    {
        // Always save OTP to file for local dev backup
        file_put_contents(base_path('otp.txt'), "=== OTP MASUK (EMAIL) ===\nEmail   : {$user['email']}\nWaktu   : " . now()->toDateTimeString() . "\nKode OTP: {$otp}\n=========================\n");

        try {
            Mail::raw(
                "Halo {$user['name']},\n\nKode OTP verifikasi registrasi Anda di SINDU Posyandu adalah: {$otp}\n\nKode ini berlaku selama 5 menit. Jangan bagikan kode ini kepada siapapun.",
                function ($message) use ($user) {
                    $message->to($user['email'])
                        ->subject('Kode OTP Verifikasi Registrasi - SINDU');
                }
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('[Email OTP Error] ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
