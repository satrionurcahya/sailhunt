<header class="navbar" id="navbar">

    <div class="nav-container">

        {{-- =====================================================
             LOGO
             ===================================================== --}}
        <a
            href="{{ url('/') }}"
            class="logo"
            aria-label="Sail & Hunt Chapter I"
        >
            <img
                src="{{ asset('assets/logo/logo-sailandhunt.png') }}"
                alt="Sail & Hunt Chapter I"
                class="logo-image"
            >
        </a>


        {{-- =====================================================
             MENU NAVIGASI
             ===================================================== --}}
        <nav
            class="nav-menu"
            id="navMenu"
            aria-label="Navigasi utama"
        >

            {{-- =================================================
                 MENU PUBLIK (BELUM LOGIN)
                 ================================================= --}}
            @if(!session('unit_id'))

                <a href="{{ url('/') }}">
                    Beranda
                </a>

                <a href="{{ url('/') }}#about">
                    Tentang
                </a>

                <a href="{{ url('/') }}#timeline">
                    Timeline
                </a>

                <a href="{{ url('/') }}#competition">
                    Lomba
                </a>

                <a href="{{ url('/') }}#faq">
                    FAQ
                </a>

                {{-- ---------------------------------------------
                     DROPDOWN DOWNLOAD PUBLIK
                     --------------------------------------------- --}}
                <div class="nav-dropdown">

                    <button
                        type="button"
                        class="nav-dropdown-toggle"
                        aria-expanded="false"
                        aria-haspopup="true"
                    >
                        Download

                        <i class="fas fa-chevron-down"></i>
                    </button>

                    <div class="nav-dropdown-menu">

                        <span class="dropdown-category">
                            📄 Dokumen Publik
                        </span>

                        <a href="{{ route('download.show', 'juklak-juknis') }}">
                            <i class="fas fa-book"></i>
                            Juklak & Juknis
                        </a>

                        <div class="dropdown-divider"></div>

                        <span class="dropdown-category">
                            📑 Dokumen Peserta
                        </span>

                        <a href="{{ route('download.show', 'surat-rekomendasi') }}">
                            <i class="fas fa-file-pdf"></i>
                            Surat Rekomendasi
                        </a>

                        <a href="{{ route('download.show', 'kartu-pp') }}">
                            <i class="fas fa-id-card"></i>
                            Kartu PP
                        </a>

                        <a href="{{ route('download.show', 'kartu-pk') }}">
                            <i class="fas fa-id-card"></i>
                            Kartu PK
                        </a>

                        <a href="{{ route('download.show', 'surat-undangan') }}">
                            <i class="fas fa-envelope"></i>
                            Surat Undangan
                        </a>

                    </div>

                </div>

            @endif


            {{-- =================================================
                 MENU USER YANG SUDAH LOGIN
                 ================================================= --}}
            @if(session('unit_id'))

                {{-- ---------------------------------------------
                     DASHBOARD
                     --------------------------------------------- --}}
                <a
                    href="{{ route('dashboard') }}"
                    class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
                >
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>

                {{-- ---------------------------------------------
                     LOMBA
                     --------------------------------------------- --}}
                <a
                    href="{{ route('competitions.index') }}"
                    class="{{ request()->routeIs('competitions.*') ? 'active' : '' }}"
                >
                    <i class="fas fa-trophy"></i>
                    Lomba
                </a>

                {{-- ---------------------------------------------
                     PROFIL
                     --------------------------------------------- --}}
                <a
                    href="{{ route('profile.index') }}"
                    class="{{ request()->routeIs('profile.*') ? 'active' : '' }}"
                >
                    <i class="fas fa-user"></i>
                    Profil
                </a>

                {{-- ---------------------------------------------
                     STATUS
                     --------------------------------------------- --}}
                <a
                    href="{{ route('status.index') }}"
                    class="{{ request()->routeIs('status.*') ? 'active' : '' }}"
                >
                    <i class="fas fa-clipboard-check"></i>
                    Status
                </a>

                {{-- ---------------------------------------------
                     DROPDOWN DOWNLOAD USER
                     --------------------------------------------- --}}
                <div class="nav-dropdown">

                    <button
                        type="button"
                        class="nav-dropdown-toggle"
                        aria-expanded="false"
                        aria-haspopup="true"
                    >
                        Download

                        <i class="fas fa-chevron-down"></i>
                    </button>

                    <div class="nav-dropdown-menu">

                        {{-- Dokumen Publik --}}
                        <span class="dropdown-category">
                            📄 Dokumen Publik
                        </span>

                        <a href="{{ route('download.show', 'juklak-juknis') }}">
                            <i class="fas fa-book"></i>
                            Juklak & Juknis
                        </a>

                        <div class="dropdown-divider"></div>

                        {{-- Dokumen Peserta --}}
                        <span class="dropdown-category">
                            📑 Dokumen Peserta
                        </span>

                        <a href="{{ route('download.show', 'surat-rekomendasi') }}">
                            <i class="fas fa-file-pdf"></i>
                            Surat Rekomendasi
                        </a>

                        <a href="{{ route('download.show', 'kartu-pp') }}">
                            <i class="fas fa-id-card"></i>
                            Kartu PP
                        </a>

                        <a href="{{ route('download.show', 'kartu-pk') }}">
                            <i class="fas fa-id-card"></i>
                            Kartu PK
                        </a>

                        <a href="{{ route('download.show', 'surat-undangan') }}">
                            <i class="fas fa-envelope"></i>
                            Surat Undangan
                        </a>

                    </div>

                </div>

                {{-- =================================================
                     ADMIN PANEL
                     HANYA UNTUK ADMIN
                     ================================================= --}}
                @php
                    $user = \App\Models\Unit::find(session('unit_id'));
                @endphp

                @if($user && $user->is_admin)

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="{{ request()->routeIs('admin.*') ? 'active' : '' }}"
                    >
                        <i class="fas fa-user-shield"></i>
                        Admin Panel
                    </a>

                @endif

            @endif

        </nav>


        {{-- =====================================================
             NAV ACTION
             ===================================================== --}}
        <div class="nav-action">

            @if(session('unit_id'))

                {{-- =================================================
                     USER LOGIN
                     ================================================= --}}

                <div class="nav-user-info">

                    <i class="fas fa-school"></i>

                    <span>
                        {{ session('unit_name') }}
                    </span>

                </div>

                {{-- Logout --}}
                <a
                    href="{{ route('logout') }}"
                    class="btn btn-primary btn-sm nav-logout"
                    onclick="
                        event.preventDefault();
                        document.getElementById('logout-form').submit();
                    "
                >
                    <i class="fas fa-sign-out-alt"></i>

                    <span>Keluar</span>
                </a>

                <form
                    id="logout-form"
                    action="{{ route('logout') }}"
                    method="POST"
                    style="display: none;"
                >
                    @csrf
                </form>

            @else

                {{-- =================================================
                     BELUM LOGIN
                     ================================================= --}}

                <a
                    href="{{ route('login') }}"
                    class="btn btn-secondary nav-auth-btn"
                >
                    <i class="fa-solid fa-right-to-bracket"></i>

                    <span>Login</span>
                </a>

                <a
                    href="{{ route('register') }}"
                    class="btn btn-primary nav-auth-btn"
                >
                    <i class="fa-solid fa-user-plus"></i>

                    <span>Daftar Unit</span>
                </a>

            @endif

        </div>


        {{-- =====================================================
             HAMBURGER
             
             PENTING:
             Tidak ada onclick di sini.

             Semua event ditangani oleh:
             public/assets/js/navigation.js
             ===================================================== --}}
        <button
            type="button"
            class="nav-toggle"
            id="navToggle"
            aria-label="Buka menu navigasi"
            aria-expanded="false"
            aria-controls="navMenu"
        >
            <i class="fas fa-bars"></i>
        </button>

    </div>

</header>