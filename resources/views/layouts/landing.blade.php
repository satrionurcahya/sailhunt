<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sail & Hunt Chapter I</title>
    <meta name="description" content="Website resmi Sail & Hunt Chapter I - Kompetisi PMR Tingkat Jawa Barat pertama bertema petualangan bajak laut. Sailing for Glory, Hunting the Treasure!">

    {{-- Favicon (opsional, bisa ditambahkan nanti) --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- Preconnect untuk performa --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Nunito:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    {{-- CSS Utama --}}
    <link rel="stylesheet" href="{{ asset('assets/css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">

    {{-- Stack untuk CSS tambahan per halaman --}}
    @stack('styles')
</head>
<body>

    {{-- Konten utama setiap halaman --}}
    @yield('content')

    {{-- Tombol Back to Top --}}
    <button class="back-to-top" id="backTop" aria-label="Kembali ke atas">
        <i class="fas fa-arrow-up"></i>
    </button>

    {{-- JavaScript Utama --}}
    <script src="{{ asset('assets/js/navigation.js') }}"></script>
    <script src="{{ asset('assets/js/countdown.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>

    {{-- Stack untuk JS tambahan per halaman --}}
    @stack('scripts')
</body>
</html>