<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') – Sail & Hunt Chapter I</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Nunito:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('assets/css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    {{-- CSS untuk halaman auth --}}
    <link rel="stylesheet" href="{{ asset('assets/css/register.css') }}">

    @stack('styles')
</head>
<body style="background: linear-gradient(135deg, #F5F8FD, #EEF5FC); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 0;">
    @yield('content')

    <script src="{{ asset('assets/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>