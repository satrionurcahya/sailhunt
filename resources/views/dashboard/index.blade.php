@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">

<section class="dashboard-page" style="padding: 20px 0 40px;">

    <div class="container dashboard-container">


        {{-- =========================================================
             FLASH MESSAGE (SUCCESS)
             ========================================================= --}}

        @if(session('success'))

            <div class="dashboard-alert dashboard-alert-success"
                 role="alert">

                <div class="dashboard-alert-icon">

                    <i class="fas fa-check"></i>

                </div>

                <div>

                    <strong>
                        Berhasil
                    </strong>

                    <p>
                        {{ session('success') }}
                    </p>

                </div>

                <button type="button"
                        class="dashboard-alert-close"
                        aria-label="Tutup">

                    <i class="fas fa-times"></i>

                </button>

            </div>

        @endif


        {{-- =========================================================
             NOTIFIKASI STATUS VERIFIKASI EMAIL
             ========================================================= --}}

        @if(isset($unit))

            @if(!$unit->hasVerifiedEmail())

                <div class="dashboard-alert"
                     style="
                        background: #fef3c7;
                        border: 1px solid #f59e0b;
                        color: #92400e;
                        border-radius: 14px;
                        padding: 14px 48px 14px 16px;
                        margin-bottom: 20px;
                        display: flex;
                        align-items: center;
                        gap: 12px;
                     "
                     role="alert">

                    <div style="
                        width: 34px;
                        height: 34px;
                        flex: 0 0 34px;
                        display: grid;
                        place-items: center;
                        border-radius: 10px;
                        background: #fef3c7;
                        color: #d97706;
                    ">

                        <i class="fas fa-exclamation-triangle"></i>

                    </div>

                    <div style="flex: 1;">

                        <strong style="
                            display: block;
                            font-size: 0.85rem;
                        ">

                            Email Belum Diverifikasi!

                        </strong>

                        <p style="
                            margin: 0;
                            font-size: 0.78rem;
                            color: #78350f;
                        ">

                            Silakan cek email
                            <strong>
                                {{ $unit->email }}
                            </strong>
                            untuk link verifikasi.

                            Link berlaku 60 menit.

                        </p>

                    </div>

                    <div style="flex: 0 0 auto;">

                        <form method="POST"
                              action="{{ route('verification.resend') }}"
                              style="display:inline;">

                            @csrf

                            <input type="hidden"
                                   name="email"
                                   value="{{ $unit->email }}">

                            <button type="submit"
                                    style="
                                        background: #f59e0b;
                                        color: #fff;
                                        border: none;
                                        border-radius: 8px;
                                        padding: 6px 14px;
                                        font-weight: 700;
                                        font-size: 0.75rem;
                                        cursor: pointer;
                                        transition: 0.2s;
                                    "
                                    onmouseover="this.style.background='#d97706'"
                                    onmouseout="this.style.background='#f59e0b'">

                                <i class="fas fa-paper-plane"></i>

                                Kirim Ulang Link

                            </button>

                        </form>

                    </div>


                    <button type="button"
                            class="dashboard-alert-close"
                            aria-label="Tutup"
                            style="
                                position: absolute;
                                top: 50%;
                                right: 12px;
                                transform: translateY(-50%);
                                border: 0;
                                background: transparent;
                                color: #92400e;
                                cursor: pointer;
                                padding: 6px;
                            ">

                        <i class="fas fa-times"></i>

                    </button>

                </div>

            @else

                <div class="dashboard-alert"
                     style="
                        background: #ecfdf5;
                        border: 1px solid #10b981;
                        color: #065f46;
                        border-radius: 14px;
                        padding: 14px 48px 14px 16px;
                        margin-bottom: 20px;
                        display: flex;
                        align-items: center;
                        gap: 12px;
                     "
                     role="alert">

                    <div style="
                        width: 34px;
                        height: 34px;
                        flex: 0 0 34px;
                        display: grid;
                        place-items: center;
                        border-radius: 10px;
                        background: #d1fae5;
                        color: #059669;
                    ">

                        <i class="fas fa-check-circle"></i>

                    </div>

                    <div style="flex: 1;">

                        <strong style="
                            display: block;
                            font-size: 0.85rem;
                        ">

                            Email Terverifikasi!

                        </strong>

                        <p style="
                            margin: 0;
                            font-size: 0.78rem;
                            color: #065f46;
                        ">

                            Akun Anda aktif.
                            Selamat bergabung di Sail & Hunt Chapter I!

                        </p>

                    </div>

                    <button type="button"
                            class="dashboard-alert-close"
                            aria-label="Tutup"
                            style="
                                position: absolute;
                                top: 50%;
                                right: 12px;
                                transform: translateY(-50%);
                                border: 0;
                                background: transparent;
                                color: #065f46;
                                cursor: pointer;
                                padding: 6px;
                            ">

                        <i class="fas fa-times"></i>

                    </button>

                </div>

            @endif

        @endif


        {{-- =========================================================
             HERO / WELCOME
             ========================================================= --}}

        <div class="dashboard-hero">

            <div class="dashboard-hero-content">

                <span class="dashboard-eyebrow">

                    <i class="fas fa-compass"></i>

                    Dashboard Peserta

                </span>

                <h1>

                    Selamat datang,

                    <span>
                        {{ session('unit_name', 'Peserta') }}
                    </span>

                </h1>

                <p>

                    Kelola profil unit, pendaftaran lomba, dan pantau proses
                    verifikasi Anda di satu tempat.

                </p>

            </div>


            <div class="dashboard-hero-decoration"
                 aria-hidden="true">

                <div class="hero-orbit hero-orbit-1"></div>

                <div class="hero-orbit hero-orbit-2"></div>

                <i class="fas fa-ship hero-medal"></i>

            </div>

        </div>


        {{-- =========================================================
             MAIN NAVIGATION
             ========================================================= --}}

        <div class="dashboard-section-heading">

            <div>

                <span class="section-kicker">
                    MENU UTAMA
                </span>

                <h2>
                    Kelola Keikutsertaan
                </h2>

            </div>

            <p>
                Pilih menu yang ingin Anda kelola.
            </p>

        </div>


        <div class="dashboard-menu-grid">


            {{-- =====================================================
                 01. PROFIL UNIT
                 ===================================================== --}}

            <article class="dashboard-menu-card card-profile">

                <div class="menu-card-top">

                    <div class="menu-icon">

                        <i class="fas fa-school"></i>

                    </div>

                    <span class="menu-number">
                        01
                    </span>

                </div>


                <div class="menu-card-content">

                    <span class="menu-label">
                        DATA UNIT
                    </span>

                    <h3>
                        Profil Unit
                    </h3>

                    <p>
                        Kelola informasi sekolah dan data unit PMR agar informasi
                        pendaftaran tetap lengkap dan akurat.
                    </p>

                </div>


                <a href="{{ route('profile.index') }}"
                   class="menu-card-action">

                    <span>
                        Kelola Profil
                    </span>

                    <i class="fas fa-arrow-right"></i>

                </a>

            </article>


            {{-- =====================================================
                 02. PENDAFTARAN LOMBA
                 ===================================================== --}}

            <article class="dashboard-menu-card card-competition">

                <div class="menu-card-top">

                    <div class="menu-icon">

                        <i class="fas fa-trophy"></i>

                    </div>

                    <span class="menu-number">
                        02
                    </span>

                </div>


                <div class="menu-card-content">

                    <span class="menu-label">
                        KEGIATAN
                    </span>

                    <h3>
                        Pendaftaran Lomba
                    </h3>

                    <p>
                        Pilih mata lomba yang tersedia dan lengkapi data peserta
                        untuk unit Anda.
                    </p>

                </div>


                <a href="{{ route('competitions.index') }}"
                   class="menu-card-action">

                    <span>
                        Daftar Sekarang
                    </span>

                    <i class="fas fa-arrow-right"></i>

                </a>

            </article>


            {{-- =====================================================
                 03. STATUS PENDAFTARAN
                 ===================================================== --}}

            <article class="dashboard-menu-card card-status">

                <div class="menu-card-top">

                    <div class="menu-icon">

                        <i class="fas fa-clipboard-check"></i>

                    </div>

                    <span class="menu-number">
                        03
                    </span>

                </div>


                <div class="menu-card-content">

                    <span class="menu-label">
                        PROGRES
                    </span>

                    <h3>
                        Status Pendaftaran
                    </h3>

                    <p>
                        Pantau status pendaftaran, verifikasi unit, dan proses
                        pembayaran secara berkala.
                    </p>

                </div>


                <a href="{{ route('status.index') }}"
                   class="menu-card-action">

                    <span>
                        Lihat Status
                    </span>

                    <i class="fas fa-arrow-right"></i>

                </a>

            </article>


            {{-- =====================================================
                 04. KARTU PESERTA
                 ===================================================== --}}

            <article class="dashboard-menu-card card-status">

                <div class="menu-card-top">

                    <div class="menu-icon">

                        <i class="fas fa-id-card"></i>

                    </div>

                    <span class="menu-number">
                        04
                    </span>

                </div>


                <div class="menu-card-content">

                    <span class="menu-label">
                        IDENTITAS PESERTA
                    </span>

                    <h3>
                        Kartu Peserta
                    </h3>

                    <p>
                        Lihat kartu peserta untuk setiap mata lomba yang telah
                        Anda daftarkan.
                    </p>

                </div>


                <a href="{{ route('participant-cards.index') }}"
                   class="menu-card-action">

                    <span>
                        Lihat Kartu
                    </span>

                    <i class="fas fa-arrow-right"></i>

                </a>

            </article>


        </div>


        {{-- =========================================================
             QUICK GUIDE
             ========================================================= --}}

        <div class="dashboard-guide">

            <div class="guide-icon">

                <i class="fas fa-lightbulb"></i>

            </div>

            <div class="guide-content">

                <strong>
                    Alur pendaftaran
                </strong>

                <p>
                    Lengkapi profil unit terlebih dahulu, pilih lomba dan peserta,
                    kemudian pantau status pendaftaran dan pembayaran.
                </p>

            </div>

        </div>


    </div>

</section>


<script src="{{ asset('assets/js/dashboard.js') }}"></script>

@endsection