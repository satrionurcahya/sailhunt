<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kartu Peserta - {{ $registration->competition->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .card { border: 2px solid #333; padding: 20px; width: 500px; margin: auto; text-align: center; }
        .header { margin-bottom: 20px; }
        .details { text-align: left; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h2>Sail & Hunt Chapter I</h2>
            <h3>Kartu Peserta</h3>
        </div>
        <div class="details">
            <p><strong>Lomba:</strong> {{ $registration->competition->name }}</p>
            <p><strong>ID Tim:</strong> <code>{{ $registration->registration_code }}</code></p>
            <p><strong>Peserta:</strong></p>
            <ul>
                @if($registration->competition->name == 'Gerakan Pungut Sampah (GPS)')
                    <li>{{ $registration->unit->school_name }} <small>(Seluruh Unit)</small></li>
                @else
                    @foreach($registration->participants as $p)
                        <li>{{ $p->name }}</li>
                    @endforeach
                @endif
            </ul>
            <p><strong>Status Pembayaran:</strong> {{ $registration->payment_status }}</p>
        </div>
    </div>
</body>
</html>