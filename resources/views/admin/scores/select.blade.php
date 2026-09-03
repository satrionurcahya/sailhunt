@extends('layouts.admin')

@section('title', 'Pilih Lomba – Input Skor')

@section('content')

<section class="admin-page">
    <div class="container">

        <div class="admin-header">
            <h1>Pilih Lomba untuk Input Skor</h1>
            <p>Pilih mata lomba yang akan diisi skor dan peringkatnya.</p>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h2><i class="fas fa-star mr-2"></i> Daftar Lomba</h2>
                <span class="badge badge-primary">{{ $competitions->count() }} Lomba</span>
            </div>
            <div class="admin-card-body">
                <div class="row">
                    @foreach($competitions as $comp)
                    <div class="col-md-4 col-sm-6 mb-3">
                        <a href="{{ route('admin.scores.input', $comp->id) }}" class="btn btn-outline-primary btn-block text-left" style="padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;">
                            <span>
                                <i class="fas fa-trophy mr-2"></i> {{ $comp->name }}
                            </span>
                            <span>
                                {!! $comp->category_badge !!}
                                <span class="badge badge-light ml-1">{{ $comp->registrations->count() }} Peserta</span>
                            </span>
                        </a>
                    </div>
                    @endforeach
                </div>
                <div style="margin-top: 20px;">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection