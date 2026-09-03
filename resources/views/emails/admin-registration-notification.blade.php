<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Notifikasi Pendaftaran Baru</title>
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
        .card-info {
            background: #f8fafc;
            border-radius: 12px;
            padding: 16px 20px;
            margin: 20px 0;
            border-left: 4px solid #1872B5;
        }
        .card-info strong {
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
            <h1>📢 Unit PMR Baru Mendaftar</h1>
            <p>Sail & Hunt Chapter I</p>
        </div>

        <div class="body">
            <h2>Halo, Admin!</h2>

            <p>
                Ada unit PMR baru yang telah mendaftar di
                <strong>Sail & Hunt Chapter I</strong>.
            </p>

            <div class="card-info">
                <p><strong>Nama Sekolah:</strong> {{ $unit->school_name }}</p>
                <p><strong>Tingkat PMR:</strong> {{ $unit->level }}</p>
                <p><strong>Kota:</strong> {{ $unit->city }}</p>
                <p><strong>Pembina:</strong> {{ $unit->coach_name }}</p>
                <p><strong>Email:</strong> {{ $unit->email }}</p>
                <p><strong>Username:</strong> {{ $unit->username }}</p>
                <p><strong>Status:</strong> <span class="badge badge-pending">Pending</span></p>
                <p><strong>Terdaftar pada:</strong> {{ $unit->created_at->translatedFormat('d M Y, H:i') }}</p>
            </div>

            <p>
                Segera lakukan verifikasi akun unit ini melalui panel admin.
            </p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $adminUrl }}" class="btn">
                    <i class="fas fa-user-check"></i> Verifikasi Sekarang
                </a>
            </div>
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