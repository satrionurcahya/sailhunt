@extends('layouts.admin')

@section('title', 'Detail Unit: ' . $unit->school_name)

@section('content')

<section class="admin-page">
    <div class="container">

        <div class="admin-header">
            <h1>Detail Unit: {{ $unit->school_name }}</h1>
            <p>Informasi lengkap unit dan status verifikasi.</p>
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

        {{-- Informasi Unit --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <h2><i class="fas fa-info-circle mr-2"></i> Informasi Unit</h2>
                <span class="badge badge-primary">{{ $unit->level }}</span>
            </div>
            <div class="admin-card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama Sekolah:</strong> {{ $unit->school_name }}</p>
                        <p><strong>Alamat:</strong> {{ $unit->address }}</p>
                        <p><strong>Kota:</strong> {{ $unit->city }}</p>
                        <p><strong>Kode Pos:</strong> {{ $unit->postal_code }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Pembina:</strong> {{ $unit->coach_name }}</p>
                        <p><strong>Pelatih:</strong> {{ $unit->trainer_name }}</p>
                        <p><strong>Komandan:</strong> {{ $unit->commander_name }}</p>
                        <p>
                            <strong>Status:</strong>
                            @if($unit->status == 'verified')
                                <span class="badge badge-success">Terverifikasi</span>
                            @elseif($unit->status == 'rejected')
                                <span class="badge badge-danger">Ditolak</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Update Status --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <h2><i class="fas fa-edit mr-2"></i> Update Status Unit</h2>
            </div>
            <div class="admin-card-body">
                <form method="POST" action="{{ route('admin.units.verify', $unit->id) }}" class="form-inline">
                    @csrf
                    <div class="form-group mr-2">
                        <label for="status" class="mr-2">Pilih Status:</label>
                        <select name="status" id="status" class="form-control">
                            <option value="verified" {{ $unit->status == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                            <option value="rejected" {{ $unit->status == 'rejected' ? 'selected' : '' }}>Tolak</option>
                            <option value="pending" {{ $unit->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Update Status
                    </button>
                </form>
            </div>
        </div>

        {{-- Registrasi Lomba --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <h2><i class="fas fa-trophy mr-2"></i> Registrasi Lomba</h2>
                <span class="badge badge-primary">{{ $unit->registrations->count() }} Lomba</span>
            </div>
            <div class="admin-card-body p-0">
                @if($unit->registrations->count())
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Lomba</th>
                                    <th>Status Pendaftaran</th>
                                    <th>Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($unit->registrations as $reg)
                                    <tr>
                                        <td><strong>{{ $reg->competition->name }}</strong></td>
                                        <td>
                                            @if($reg->status == 'confirmed')
                                                <span class="badge badge-success">Dikonfirmasi</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($reg->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($reg->payment_status == 'verified')
                                                <span class="badge badge-success">Lunas</span>
                                            @elseif($reg->payment_status == 'paid')
                                                <span class="badge badge-info">Menunggu Verifikasi</span>
                                            @else
                                                <span class="badge badge-warning">Belum Bayar</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div style="padding: 30px 20px; text-align: center; color: #94a3b8;">
                        <i class="fas fa-inbox" style="font-size: 1.5rem; display: block; margin-bottom: 8px;"></i>
                        <p style="margin: 0;">Unit ini belum mendaftar lomba apa pun.</p>
                    </div>
                @endif
            </div>
            <div class="admin-card-footer" style="padding: 12px 20px; border-top: 1px solid #e2e8f0; background: #f8fafc;">
                <a href="{{ route('admin.units.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Unit
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm ml-2">
                    <i class="fas fa-home mr-1"></i> Dashboard
                </a>
            </div>
        </div>

    </div>
</section>

@endsection