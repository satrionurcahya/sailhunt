@extends('layouts.admin')

@section('title', 'Peserta: ' . $competition->name)

@section('content')

<section class="admin-page">
    <div class="container">

        <div class="admin-header">
            <h1>Peserta: {{ $competition->name }}</h1>
            <p>Daftar unit yang mendaftar pada lomba ini.</p>
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

        <div class="admin-card">
            <div class="admin-card-header">
                <h2><i class="fas fa-users mr-2"></i> Daftar Peserta</h2>
                <div style="display: flex; gap: 10px; align-items: center;">
                    {!! $competition->category_badge !!}
                    <span class="badge badge-primary">{{ $registrations->count() }} Pendaftar</span>
                </div>
            </div>
            <div class="admin-card-body p-0">

                @if($registrations->isEmpty())
                    <div style="padding: 40px 20px; text-align: center; color: #94a3b8;">
                        <i class="fas fa-inbox" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                        <p style="margin: 0;">Belum ada pendaftar untuk lomba ini.</p>
                    </div>
                @else
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Unit</th>
                                    <th>Peserta</th>
                                    <th>Treasure / Bounty</th>
                                    <th>Status Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($registrations as $index => $reg)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $reg->unit->school_name }}</strong></td>
                                    <td>
                                        <ul style="margin: 0; padding-left: 18px; list-style: disc;">
                                            @foreach($reg->participants as $p)
                                                <li>{{ $p->name }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>{!! $competition->category_badge !!}</td>
                                    <td>
                                        @if($reg->payment_status == 'verified')
                                            <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Lunas</span>
                                        @elseif($reg->payment_status == 'paid')
                                            <span class="badge badge-info"><i class="fas fa-clock mr-1"></i> Menunggu Verifikasi</span>
                                        @else
                                            <span class="badge badge-warning"><i class="fas fa-exclamation-circle mr-1"></i> Belum Bayar</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
            <div class="admin-card-footer" style="padding: 12px 20px; border-top: 1px solid #e2e8f0; background: #f8fafc;">
                <a href="{{ route('admin.competitions.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Lomba
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm ml-2">
                    <i class="fas fa-home mr-1"></i> Dashboard
                </a>
            </div>
        </div>

    </div>
</section>

@endsection