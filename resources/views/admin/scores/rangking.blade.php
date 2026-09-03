@extends('layouts.admin')

@section('title', 'Ranking Juara Umum & Favorit')

@section('content')

<section class="admin-page">
    <div class="container">

        <div class="admin-header">
            <h1><i class="fas fa-crown mr-2" style="color: #D4AF37;"></i> Ranking Juara</h1>
            <p>Akumulasi poin untuk Juara Umum dan kelayakan Juara Favorit berdasarkan ketentuan Juklak.</p>
        </div>

        {{-- =========================================================
             JUARA UMUM
             ========================================================= --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <h2><i class="fas fa-trophy mr-2"></i> Juara Umum</h2>
                <span class="badge badge-primary">{{ $juaraUmum->count() }} Unit</span>
            </div>
            <div class="admin-card-body p-0">
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Peringkat</th>
                                <th>Unit</th>
                                <th>Total Poin</th>
                                <th>Jumlah Lomba</th>
                                <th>Treasure Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($juaraUmum as $index => $item)
                            <tr>
                                <td>
                                    @if($index == 0)
                                        <span class="badge" style="background: #D4AF37; color: #1e293b; font-size: 0.9rem; padding: 6px 12px;">
                                            <i class="fas fa-crown"></i> Juara I
                                        </span>
                                    @elseif($index == 1)
                                        <span class="badge" style="background: #C0C0C0; color: #1e293b; font-size: 0.9rem; padding: 6px 12px;">
                                            <i class="fas fa-medal"></i> Juara II
                                        </span>
                                    @elseif($index == 2)
                                        <span class="badge" style="background: #CD7F32; color: #fff; font-size: 0.9rem; padding: 6px 12px;">
                                            <i class="fas fa-medal"></i> Juara III
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td><strong>{{ $item->unit->school_name }}</strong></td>
                                <td>
                                    <span class="badge" style="background: #0D4A85; color: #FFCC80; font-size: 1rem; padding: 6px 14px;">
                                        {{ $item->total_points }} Poin
                                    </span>
                                </td>
                                <td>{{ $item->competitions_count }} Lomba</td>
                                <td>{{ $item->treasure_count }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px; color: #94a3b8;">
                                    <i class="fas fa-inbox" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                                    Belum ada skor yang diinput.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- =========================================================
             JUARA FAVORIT
             ========================================================= --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <h2><i class="fas fa-star mr-2" style="color: #E88A1A;"></i> Juara Favorit</h2>
                <span class="badge badge-warning">{{ $juaraFavorit->count() }} Eligible</span>
            </div>
            <div class="admin-card-body p-0">
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Unit</th>
                                <th>Video Kreatif</th>
                                <th>Lomba Lain</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($juaraFavorit as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $item->unit->school_name }}</strong></td>
                                <td>
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i> Diikuti
                                    </span>
                                </td>
                                <td>{{ $item->competitions_count - 1 }} Lomba</td>
                                <td>
                                    <span class="badge" style="background: #E88A1A; color: #fff;">
                                        <i class="fas fa-star"></i> Eligible
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px; color: #94a3b8;">
                                    <i class="fas fa-inbox" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                                    Belum ada unit yang memenuhi syarat Juara Favorit.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="admin-card-footer" style="padding: 12px 20px; border-top: 1px solid #e2e8f0; background: #f8fafc;">
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('admin.scores.select') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali Input Skor
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-home mr-1"></i> Dashboard
                    </a>
                </div>
                <div style="margin-top: 10px; font-size: 0.8rem; color: #94a3b8;">
                    <strong>Keterangan:</strong>
                    Poin: Juara I=10, Juara II=7, Juara III=5, Harapan I=3, Harapan II=1.
                    Juara Favorit: mengikuti Video Kreatif + minimal 2 lomba lainnya.
                </div>
            </div>
        </div>

    </div>
</section>

@endsection