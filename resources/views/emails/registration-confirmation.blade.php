<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Konfirmasi Pendaftaran</title>
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
        .unit-info {
            background: #f8fafc;
            border-radius: 12px;
            padding: 16px 20px;
            margin: 20px 0;
            border-left: 4px solid #1872B5;
        }
        .unit-info strong {
            color: #0D4A85;
        }
        .btn {
            display: inline-block;
            background: #0D4A85;
            color: #ffffff;
            padding: 12px 30px;
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
            <p>Kompetisi PMR Tingkat Jawa Barat</p>
        </div>

        <div class="body">
            <h2>Halo, <span class="highlight">{{ $unit->school_name }}</span>!</h2>

            <p>
                Selamat! Unit PMR Anda berhasil terdaftar di
                <strong>Sail & Hunt Chapter I</strong>.
            </p>

            <p>
                Berikut adalah data pendaftaran unit Anda:
            </p>

            <div class="unit-info">
                <p><strong>Nama Sekolah:</strong> {{ $unit->school_name }}</p>
                <p><strong>Tingkat PMR:</strong> {{ $unit->level }}</p>
                <p><strong>Username:</strong> {{ $unit->username }}</p>
                <p><strong>Email:</strong> {{ $unit->email }}</p>
                <p><strong>Pembina:</strong> {{ $unit->coach_name }}</p>
            </div>

            <p>
                <strong>📌 Langkah Selanjutnya:</strong>
            </p>
            <ol style="color: #475569; line-height: 1.8; padding-left: 20px;">
                <li>Login ke dashboard peserta menggunakan <strong>username</strong> dan <strong>password</strong> yang telah Anda buat.</li>
                <li>Lengkapi profil unit dan dokumen daftar ulang.</li>
                <li>Pilih mata lomba yang ingin diikuti dan isi data peserta.</li>
                <li>Lakukan pembayaran sesuai dengan tagihan.</li>
            </ol>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $loginUrl }}" class="btn">
                    <i class="fas fa-sign-in-alt"></i> Login ke Dashboard
                </a>
            </div>

            <p style="font-size: 14px; color: #94a3b8;">
                ⚠️ Jika Anda tidak melakukan pendaftaran ini, abaikan email ini.
            </p>
        </div>

        <div class="footer">
            <p>
                &copy; {{ date('Y') }} Sail & Hunt Chapter I •
                <a href="mailto:SailAndHunt.13@gmail.com">SailAndHunt.13@gmail.com</a>
            </p>
            <p style="margin-top: 5px; font-size: 12px;">
                SMA Negeri 27 Bandung • Jalan Utsman bin Affan No. 1, Gedebage
            </p>
        </div>
    </div>
</body>
</html>