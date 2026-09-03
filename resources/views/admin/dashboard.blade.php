@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')

<section class="admin-page">
    <div class="container">

        {{-- Header --}}
        <div class="admin-header">
            <h1><i class="fas fa-gauge-high" style="color: #FFCC80;"></i> Dashboard Admin</h1>
            <p>Kelola seluruh data Sail & Hunt Chapter I dengan mudah dan cepat.</p>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Statistik --}}
        <div class="admin-stats">
            <div class="admin-stat-card">
                <h3>{{ $totalUnits }}</h3>
                <p><i class="fas fa-school mr-1"></i> Unit Terdaftar</p>
            </div>
            <div class="admin-stat-card">
                <h3>{{ $totalRegistrations }}</h3>
                <p><i class="fas fa-file-signature mr-1"></i> Pendaftaran Lomba</p>
            </div>
            <div class="admin-stat-card">
                <h3>{{ $pendingPayments }}</h3>
                <p><i class="fas fa-clock mr-1"></i> Pembayaran Pending</p>
            </div>
            <div class="admin-stat-card">
                <h3>{{ $totalCompetitions }}</h3>
                <p><i class="fas fa-trophy mr-1"></i> Mata Lomba</p>
            </div>
        </div>

        {{-- Menu Manajemen --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <h2><i class="fas fa-cog mr-2"></i> Menu Manajemen</h2>
                <span class="badge badge-primary">5 Menu</span>
            </div>
            <div class="admin-card-body">
                <div class="row">

                    {{-- Menu 1: Manajemen Unit --}}
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a href="{{ route('admin.units.index') }}" class="admin-menu-card">
                            <div class="admin-menu-icon" style="background: #EBF5FF; color: #0D4A85;">
                                <i class="fas fa-school"></i>
                            </div>
                            <div class="admin-menu-content">
                                <h4>Manajemen Unit</h4>
                                <p>Kelola verifikasi unit PMR</p>
                            </div>
                            <div class="admin-menu-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </a>
                    </div>

                    {{-- Menu 2: Verifikasi Pembayaran --}}
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a href="{{ route('admin.payments.index') }}" class="admin-menu-card">
                            <div class="admin-menu-icon" style="background: #FFF4E0; color: #E88A1A;">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="admin-menu-content">
                                <h4>Verifikasi Pembayaran</h4>
                                <p>Kelola bukti transfer peserta</p>
                            </div>
                            <div class="admin-menu-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </a>
                    </div>

                    {{-- Menu 3: Verifikasi Daftar Ulang --}}
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a href="{{ route('admin.daftar-ulang.index') }}" class="admin-menu-card">
                            <div class="admin-menu-icon" style="background: #E6F7ED; color: #059669;">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="admin-menu-content">
                                <h4>Verifikasi Daftar Ulang</h4>
                                <p>Kelola dokumen daftar ulang</p>
                            </div>
                            <div class="admin-menu-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </a>
                    </div>

                    {{-- Menu 4: Manajemen Lomba --}}
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a href="{{ route('admin.competitions.index') }}" class="admin-menu-card">
                            <div class="admin-menu-icon" style="background: #FCE4EC; color: #C62828;">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="admin-menu-content">
                                <h4>Manajemen Lomba</h4>
                                <p>Lihat peserta per lomba</p>
                            </div>
                            <div class="admin-menu-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </a>
                    </div>

                    {{-- Menu 5: Input Skor --}}
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a href="{{ route('admin.scores.select') }}" class="admin-menu-card">
                            <div class="admin-menu-icon" style="background: #F3E5F5; color: #6A1B9A;">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="admin-menu-content">
                                <h4>Input Skor</h4>
                                <p>Masukkan skor & peringkat</p>
                            </div>
                            <div class="admin-menu-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </a>
                    </div>

                    {{-- Menu 6: Ranking --}}
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a href="{{ route('admin.scores.ranking') }}" class="admin-menu-card">
                            <div class="admin-menu-icon" style="background: #FEF3C7; color: #D97706;">
                                <i class="fas fa-crown"></i>
                            </div>
                            <div class="admin-menu-content">
                                <h4>Ranking Juara</h4>
                                <p>Lihat akumulasi poin & Juara Favorit</p>
                            </div>
                            <div class="admin-menu-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </div>
</section>

@endsection