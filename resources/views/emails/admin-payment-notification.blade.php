<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Notifikasi Pembayaran Baru</title>
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
            background: linear-gradient(135deg, #E88A1A, #C75F00);
            padding: 30px 30px 20px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 800;
        }
        .header p {
            color: rgba(255,255,255,0.85);
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
            border-left: 4px solid #E88A1A;
        }
        .card-info strong {
            color: #0D4A85;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }
        .badge-pending {
            background: #FEF3C7;
            color: #92400E;
        }
        .btn {
            display: inline-block;
            background: #E88A1A;
            color: #ffffff;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            margin-top: 10px;
        }
        .btn:hover {
            background: #C75F00;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📢 Notifikasi Pembayaran Baru</h1>
            <p>Sail & Hunt Chapter I</p>
        </div>

        <div class="body">
            <h2>Halo, Admin!</h2>

            <p>
                Ada bukti pembayaran baru yang diunggah oleh unit berikut:
            </p>

            <div class="card-info">
                <p><strong>Unit:</strong> {{ $unit->school_name }}</p>
                <p><strong>Lomba:</strong> {{ $upload->registration->competition->name ?? 'Tidak diketahui' }}</p>
                <p><strong>Jenis Pembayaran:</strong> {{ ucfirst($upload->registration->payment_type ?? 'Belum diisi') }}</p>
                <p><strong>Nominal:</strong> Rp {{ number_format($upload->registration->amount_paid ?? 0, 2, ',', '.') }}</p>
                <p><strong>Status:</strong> <span class="badge badge-pending">Pending</span></p>
                <p><strong>Diunggah pada:</strong> {{ $upload->created_at->translatedFormat('d M Y, H:i') }}</p>
            </div>

            <p>
                Segera lakukan verifikasi melalui panel admin.
            </p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $adminUrl }}" class="btn">
                    <i class="fas fa-check"></i> Verifikasi Sekarang
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