@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran')

@section('content')

<section class="admin-page">
    <div class="container">

        <div class="admin-header">
            <h1>Verifikasi Pembayaran</h1>
            <p>Kelola bukti pembayaran yang diunggah oleh unit.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="admin-card">

            <div class="admin-card-header">
                <h2><i class="fas fa-money-bill-wave mr-2"></i> Daftar Bukti Pembayaran</h2>
                <span class="badge badge-primary">{{ $uploads->total() }} Total</span>
            </div>

            {{-- ============================================================
                 FILTER & SEARCH
                 ============================================================ --}}
            <div class="admin-card-body" style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0;">
                <form method="GET" action="{{ route('admin.payments.index') }}" class="form-inline" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                    <input type="text" name="search" class="form-control" placeholder="Cari unit atau lomba..." value="{{ request('search') }}" style="flex: 1; min-width: 200px;">

                    <select name="status" class="form-control">
                        <option value="all">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>

                    <select name="payment_type" class="form-control">
                        <option value="all">Semua Tipe</option>
                        <option value="dp" {{ request('payment_type') == 'dp' ? 'selected' : '' }}>DP</option>
                        <option value="lunas" {{ request('payment_type') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>

                    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
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
                                <th>Unit</th>
                                <th>Lomba</th>
                                <th>Tipe</th>
                                <th>File</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($uploads as $up)
                            <tr>
                                <td><strong>{{ $up->unit->school_name }}</strong></td>
                                <td>{{ $up->registration->competition->name ?? '-' }}</td>
                                <td>
                                    @if($up->registration->payment_type == 'dp')
                                        <span class="badge badge-info">DP 60%</span>
                                    @elseif($up->registration->payment_type == 'lunas')
                                        <span class="badge badge-success">Lunas</span>
                                    @else
                                        <span class="badge badge-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($up->file_path)
                                        <a href="{{ route('admin.documents.view', $up->id) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye mr-1"></i> Lihat
                                        </a>
                                    @else
                                        <span class="text-muted"><i class="fas fa-file-slash mr-1"></i> File tidak tersedia</span>
                                    @endif
                                </td>
                                <td>
                                    @if($up->status == 'verified')
                                        <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Terverifikasi</span>
                                    @elseif($up->status == 'rejected')
                                        <span class="badge badge-danger"><i class="fas fa-times-circle mr-1"></i> Ditolak</span>
                                    @else
                                        <span class="badge badge-warning"><i class="fas fa-clock mr-1"></i> Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($up->status == 'pending')
                                        <form method="POST" action="{{ route('admin.payments.verify', $up->id) }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="verified">
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Yakin ingin memverifikasi?')">
                                                <i class="fas fa-check mr-1"></i> Verifikasi
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.payments.verify', $up->id) }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menolak?')">
                                                <i class="fas fa-times mr-1"></i> Tolak
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px; color: #94a3b8;">
                                    <i class="fas fa-inbox" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                                    Belum ada bukti pembayaran yang diunggah.
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
                    {{ $uploads->links() }}
                </div>
            </div>

        </div>

    </div>
</section>

@endsection