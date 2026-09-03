@extends('layouts.admin')

@section('title', 'Manajemen Unit')

@section('content')

<section class="admin-page">
    <div class="container">

        <div class="admin-header">
            <h1>Manajemen Unit</h1>
            <p>Kelola status verifikasi unit PMR yang terdaftar.</p>
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
                <h2><i class="fas fa-school mr-2"></i> Daftar Unit</h2>
                <span class="badge badge-primary">{{ $units->total() }} Total</span>
            </div>

            {{-- ============================================================
                 FILTER & SEARCH
                 ============================================================ --}}
            <div class="admin-card-body" style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0;">
                <form method="GET" action="{{ route('admin.units.index') }}" class="form-inline" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                    <input type="text" name="search" class="form-control" placeholder="Cari unit..." value="{{ request('search') }}" style="flex: 1; min-width: 200px;">

                    <select name="status" class="form-control">
                        <option value="all">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>

                    <select name="level" class="form-control">
                        <option value="all">Semua Level</option>
                        <option value="Madya" {{ request('level') == 'Madya' ? 'selected' : '' }}>Madya</option>
                        <option value="Wira" {{ request('level') == 'Wira' ? 'selected' : '' }}>Wira</option>
                    </select>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>

                    <a href="{{ route('admin.units.index') }}" class="btn btn-secondary">
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
                                <th>Nama Sekolah</th>
                                <th>Kota</th>
                                <th>Level</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($units as $unit)
                            <tr>
                                <td><strong>{{ $unit->school_name }}</strong></td>
                                <td>{{ $unit->city }}</td>
                                <td><span class="badge badge-secondary">{{ $unit->level }}</span></td>
                                <td>
                                    @if($unit->status == 'verified')
                                        <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Terverifikasi</span>
                                    @elseif($unit->status == 'rejected')
                                        <span class="badge badge-danger"><i class="fas fa-times-circle mr-1"></i> Ditolak</span>
                                    @else
                                        <span class="badge badge-warning"><i class="fas fa-clock mr-1"></i> Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.units.show', $unit->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px; color: #94a3b8;">
                                    <i class="fas fa-inbox" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                                    Tidak ada unit yang ditemukan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="admin-card-footer" style="padding: 12px 20px; border-top: 1px solid #e2e8f0; background: #f8fafc; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
                    </a>
                </div>
                <div>
                    {{ $units->links() }}
                </div>
            </div>
        </div>

    </div>
</section>

@endsection