<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'Admin Panel') – Sail & Hunt Chapter I
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
         
         PENTING:
         Navbar menggunakan CSS dari landing.css.
         
         JANGAN DIHAPUS.
         ===================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/landing.css') }}"
    >


    {{-- =====================================================
         CSS RESPONSIVE
         ===================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/responsive.css') }}"
    >


    {{-- =====================================================
         CSS ADMIN
         ===================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/admin.css') }}"
    >


    {{-- =====================================================
         CSS KHUSUS HALAMAN
         ===================================================== --}}

    @stack('styles')

</head>


<body>


    {{-- =====================================================
         NAVBAR
         ===================================================== --}}

    @include('partials.navbar')


    {{-- =====================================================
         MAIN ADMIN
         ===================================================== --}}

    <main
        class="admin-main"
        style="
            padding-top: 90px;
            min-height: 70vh;
            background: #f0f2f8;
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

    <script
        src="{{ asset('assets/js/app.js') }}"
    ></script>


    {{-- =====================================================
         JAVASCRIPT NAVBAR
         
         Semua fungsi navbar:
         - Hamburger
         - Mobile menu
         - Dropdown
         - Click outside
         - Escape
         
         berada di navigation.js.
         ===================================================== --}}

    <script
        src="{{ asset('assets/js/navigation.js') }}"
    ></script>


    {{-- =====================================================
         JAVASCRIPT KHUSUS HALAMAN
         ===================================================== --}}

    @stack('scripts')


</body>

</html>