<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode OTP SINDU</title>
</head>
<body style="font-family: sans-serif; background-color: #f8fafc; padding: 20px; color: #334155;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; padding: 30px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);">
        <h2 style="color: #059669; margin-top: 0;">SINDU (Sistem Posyandu Digital Terpadu)</h2>
        <p>Halo <strong>{{ $name }}</strong>,</p>
        <p>Terima kasih telah melakukan pendaftaran di platform SINDU. Untuk menyelesaikan pendaftaran akun Anda, silakan gunakan kode verifikasi OTP berikut:</p>
        <div style="font-size: 28px; font-weight: bold; letter-spacing: 4px; text-align: center; background: #ecfdf5; border: 1px solid #10b981; color: #065f46; padding: 15px; border-radius: 8px; margin: 25px 0;">
            {{ $otp }}
        </div>
        <p style="font-size: 13px; color: #64748b;">Kode ini bersifat rahasia dan berlaku selama 5 menit. Jangan bagikan kode ini kepada siapa pun.</p>
        <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 25px 0;">
        <p style="font-size: 12px; color: #94a3b8; text-align: center;">Ini adalah email otomatis, mohon tidak membalas email ini.</p>
    </div>
</body>
</html>
