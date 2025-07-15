<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="background-color:#f9fafb; font-family:Arial, sans-serif; padding:20px;">
    <div style="max-width:600px; margin:auto; background:white; border-radius:0.5rem; box-shadow:0 0 10px rgba(0,0,0,0.05); padding:2rem;">
        <!-- Logo -->
        <div style="text-align:center; margin-bottom:1.5rem;">
            <img src="https://app.btkvfkudayana.com/assets/media/logos/android-chrome-512x512.png" alt="BTKV Logo" style="max-width:120px; height:auto;">
        </div>

        <!-- Heading -->
        <h2 style="color:#1f2937; font-size:1.5rem; font-weight:600; margin-bottom:1rem;">
            Reset Password Akun Anda
        </h2>

        <!-- Body -->
        <p style="color:#4b5563; font-size:1rem; margin-bottom:1rem;">
            Halo {{ $user->name ?? 'Pengguna' }},
        </p>

        <p style="color:#4b5563; font-size:1rem; margin-bottom:1rem;">
            Kami menerima permintaan untuk mereset password akun Anda.
        </p>

        <p style="color:#4b5563; font-size:1rem; margin-bottom:1rem;">
            Silakan klik tombol di bawah untuk mengatur ulang password Anda:
        </p>

        <!-- Button -->
        <div style="text-align:center; margin:2rem 0;">
            <a href="{{ $resetUrl }}" style="background-color:#3b82f6; color:white; padding:0.75rem 1.5rem; border-radius:0.375rem; text-decoration:none; font-weight:600; font-size:1rem;">
                Reset Password
            </a>
        </div>

        <p style="color:#4b5563; font-size:1rem; margin-bottom:1rem;">
            Tautan ini akan kedaluwarsa dalam 60 menit.
        </p>

        <p style="color:#4b5563; font-size:1rem;">
            Jika Anda tidak meminta reset password, abaikan email ini.
        </p>

        <!-- Footer -->
        <div style="text-align:center; font-size:0.875rem; color:#9ca3af; margin-top:2rem;">
            &copy; {{ date('Y') }} BTKVFKUDAYANA. All rights reserved.
        </div>
    </div>
</body>
</html>
