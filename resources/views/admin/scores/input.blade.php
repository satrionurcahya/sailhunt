@extends('layouts.admin')

@section('title', 'Input Skor: ' . $competition->name)

@section('content')

<section class="admin-page">
    <div class="container">

        <div class="admin-header">
            <h1>Input Skor: {{ $competition->name }}</h1>
            <p>Masukkan skor dan catatan untuk setiap peserta.</p>
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
                <h2><i class="fas fa-edit mr-2"></i> Skor Peserta</h2>
                <span class="badge badge-primary">{{ $registrations->count() }} Peserta</span>
            </div>
            <div class="admin-card-body p-0">
                <form method="POST" action="{{ route('admin.scores.store', $competition->id) }}">
                    @csrf
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Unit</th>
                                    <th>Peserta</th>
                                    <th>Skor</th>
                                    <th>Peringkat</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($registrations as $index => $reg)
                                <tr>
                                    <td><strong>{{ $reg->unit->school_name }}</strong></td>
                                    <td>
                                        <ul style="margin: 0; padding-left: 18px; list-style: disc; font-size: 0.9rem;">
                                            @foreach($reg->participants as $p)
                                                <li>{{ $p->name }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td style="min-width: 100px;">
                                        <input type="hidden" name="scores[{{ $loop->index }}][registration_id]" value="{{ $reg->id }}">
                                        <input type="number" 
                                               name="scores[{{ $loop->index }}][score]" 
                                               class="form-control form-control-sm" 
                                               step="0.01" 
                                               value="{{ $reg->score->score ?? '' }}"
                                               placeholder="0.00"
                                               style="width: 100px;">
                                    </td>
                                    <td>
                                        @if($reg->score && $reg->score->rank)
                                            <span class="badge badge-info" style="font-size: 0.9rem;">
                                                <i class="fas fa-hashtag mr-1"></i> {{ $reg->score->rank }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <input type="text" 
                                               name="scores[{{ $loop->index }}][notes]" 
                                               class="form-control form-control-sm" 
                                               value="{{ $reg->score->notes ?? '' }}"
                                               placeholder="Catatan..."
                                               style="min-width: 150px;">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div style="padding: 16px 20px; border-top: 1px solid #e2e8f0; background: #f8fafc; display: flex; gap: 10px; flex-wrap: wrap;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Simpan Skor
                        </button>
                        <a href="{{ route('admin.scores.select') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali Pilih Lomba
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-home mr-1"></i> Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</section>

@endsection