<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'Dashboard') – Sail & Hunt Chapter I
    </title>


    {{-- =====================================================
         GOOGLE FONTS
         ===================================================== --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Nunito:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet"
    >


    {{-- =====================================================
         FONT AWESOME
         ===================================================== --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


    {{-- =====================================================
         CSS GLOBAL
         ===================================================== --}}
    <link
        rel="stylesheet"
        href="{{ asset('assets/css/variables.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/reset.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/layout.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/components.css') }}"
    >


    {{-- =====================================================
         CSS LANDING
         Agar tampilan navbar dan komponen tetap konsisten
         ===================================================== --}}
    <link
        rel="stylesheet"
        href="{{ asset('assets/css/landing.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/responsive.css') }}"
    >


    {{-- =====================================================
         CSS NAVBAR
         Jika file navbar.css tersedia, gunakan file ini.
         ===================================================== --}}
    @if(file_exists(public_path('assets/css/navbar.css')))
        <link
            rel="stylesheet"
            href="{{ asset('assets/css/navbar.css') }}"
        >
    @endif


    {{-- =====================================================
         CSS TAMBAHAN PER HALAMAN
         ===================================================== --}}
    @stack('styles')

</head>


<body>

    {{-- =====================================================
         NAVBAR
         ===================================================== --}}
    @include('partials.navbar')


    {{-- =====================================================
         KONTEN UTAMA
         ===================================================== --}}
    <main
        style="
            padding-top: 90px;
            min-height: 70vh;
        "
    >

        @yield('content')

    </main>


    {{-- =====================================================
         FOOTER
         ===================================================== --}}
    @include('partials.footer')


    {{-- =====================================================
         JAVASCRIPT GLOBAL
         ===================================================== --}}

    {{-- JavaScript umum aplikasi --}}
    <script
        src="{{ asset('assets/js/app.js') }}"
    ></script>


    {{-- =====================================================
         JAVASCRIPT NAVBAR
         
         SEMUA fungsi:
         - hamburger
         - mobile menu
         - dropdown
         - close menu
         - resize
         - escape
         
         ditangani oleh file ini.
         ===================================================== --}}
    <script
        src="{{ asset('assets/js/navigation.js') }}"
    ></script>


    {{-- =====================================================
         JAVASCRIPT TAMBAHAN PER HALAMAN
         ===================================================== --}}
    @stack('scripts')

</body>

</html>