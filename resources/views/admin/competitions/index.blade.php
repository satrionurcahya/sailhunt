@extends('layouts.admin')

@section('title', 'Manajemen Lomba')

@section('content')

<section class="admin-page">
    <div class="container">

        <div class="admin-header">
            <h1>Manajemen Lomba</h1>
            <p>Daftar semua mata lomba dan jumlah pendaftar.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="admin-card">

            <div class="admin-card-header">
                <h2><i class="fas fa-list mr-2"></i> Daftar Lomba</h2>
                <span class="badge badge-primary">{{ $competitions->count() }} Lomba</span>
            </div>

            {{-- ============================================================
                 FILTER & SEARCH
                 ============================================================ --}}
            <div class="admin-card-body" style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0;">
                <form method="GET" action="{{ route('admin.competitions.index') }}" class="form-inline" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama lomba..." value="{{ request('search') }}" style="flex: 1; min-width: 200px;">

                    <select name="category" class="form-control">
                        <option value="all">Semua Kategori</option>
                        <option value="Pertolongan Pertama" {{ request('category') == 'Pertolongan Pertama' ? 'selected' : '' }}>Pertolongan Pertama</option>
                        <option value="Remaja Sehat Peduli Sesama" {{ request('category') == 'Remaja Sehat Peduli Sesama' ? 'selected' : '' }}>Remaja Sehat Peduli Sesama</option>
                        <option value="Ayo Siaga Bencana" {{ request('category') == 'Ayo Siaga Bencana' ? 'selected' : '' }}>Ayo Siaga Bencana</option>
                        <option value="Kesehatan Remaja" {{ request('category') == 'Kesehatan Remaja' ? 'selected' : '' }}>Kesehatan Remaja</option>
                        <option value="Kepalangmerahan & Kreativitas" {{ request('category') == 'Kepalangmerahan & Kreativitas' ? 'selected' : '' }}>Kepalangmerahan & Kreativitas</option>
                    </select>

                    <select name="competition_category" class="form-control">
                        <option value="all">Semua Treasure/Bounty</option>
                        <option value="treasure" {{ request('competition_category') == 'treasure' ? 'selected' : '' }}>Treasure</option>
                        <option value="bounty" {{ request('competition_category') == 'bounty' ? 'selected' : '' }}>Bounty</option>
                    </select>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>

                    <a href="{{ route('admin.competitions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </form>
            </div>

            {{-- ============================================================
                 TABLE
                 ============================================================ --}}
            <div class="admin-card-body p-0">
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Nama Lomba</th>
                                <th>Kategori</th>
                                <th>Treasure / Bounty</th>
                                <th>Jumlah Pendaftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($competitions as $comp)
                            <tr>
                                <td><strong>{{ $comp->name }}</strong></td>
                                <td>{{ $comp->category }}</td>
                                <td>{!! $comp->category_badge !!}</td>
                                <td>
                                    <span class="badge badge-info">{{ $comp->registrations_count }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.competitions.show', $comp->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-users mr-1"></i> Lihat Peserta
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px; color: #94a3b8;">
                                    <i class="fas fa-inbox" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                                    Tidak ada lomba yang ditemukan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="admin-card-footer" style="padding: 12px 20px; border-top: 1px solid #e2e8f0; background: #f8fafc;">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
                </a>
            </div>

        </div>

    </div>
</section>

@endsection