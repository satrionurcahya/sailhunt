<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #0D4A85, #1872B5);
            padding: 30px 30px 20px;
            text-align: center;
        }
        .header h1 {
            color: #FFCC80;
            margin: 0;
            font-size: 24px;
            font-weight: 800;
        }
        .header p {
            color: rgba(255,255,255,0.8);
            margin: 5px 0 0;
            font-size: 14px;
        }
        .body {
            padding: 30px;
        }
        .body h2 {
            color: #0D4A85;
            margin-top: 0;
            font-size: 20px;
        }
        .body p {
            color: #475569;
            line-height: 1.7;
            font-size: 15px;
        }
        .warning {
            background: #fef3c7;
            padding: 12px 16px;
            border-radius: 8px;
            color: #92400e;
            font-size: 14px;
            margin: 20px 0;
            border-left: 4px solid #d97706;
        }
        .btn {
            display: inline-block;
            background: #0D4A85;
            color: #ffffff;
            padding: 14px 34px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            margin-top: 10px;
        }
        .btn:hover {
            background: #0B3D6E;
        }
        .footer {
            background: #f8fafc;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            color: #94a3b8;
            font-size: 13px;
        }
        .footer a {
            color: #1872B5;
            text-decoration: none;
        }
        .highlight {
            color: #E88A1A;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⛵ Sail & Hunt Chapter I</h1>
            <p>Reset Password</p>
        </div>

        <div class="body">
            <h2>Halo, <span class="highlight">{{ $unit->school_name }}</span>!</h2>

            <p>
                Kami menerima permintaan untuk mereset password akun Anda di
                <strong>Sail & Hunt Chapter I</strong>.
            </p>

            <div class="warning">
                <strong>🔒 Link ini berlaku selama 60 menit.</strong>
                Jika Anda tidak meminta reset password, abaikan email ini.
                Password Anda tidak akan berubah.
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $resetUrl }}" class="btn">
                    <i class="fas fa-key"></i> Reset Password
                </a>
            </div>

            <p style="font-size: 14px; color: #94a3b8;">
                Jika tombol di atas tidak berfungsi, salin dan tempel link berikut ke browser Anda:
                <br>
                <a href="{{ $resetUrl }}" style="word-break: break-all; color: #1872B5;">
                    {{ $resetUrl }}
                </a>
            </p>

            <p style="font-size: 14px; color: #94a3b8; margin-top: 20px;">
                ⚠️ Jika Anda tidak melakukan permintaan ini, abaikan email ini.
            </p>
        </div>

        <div class="footer">
            <p>
                &copy; {{ date('Y') }} Sail & Hunt Chapter I •
                <a href="mailto:SailAndHunt.13@gmail.com">SailAndHunt.13@gmail.com</a>
            </p>
        </div>
    </div>
</body>
</html>