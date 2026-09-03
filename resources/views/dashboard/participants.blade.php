@extends('layouts.landing')

@section('content')
    @include('partials.navbar')

    <section style="padding: 150px 0 80px;">
        <div class="container" style="max-width: 700px;">

            <div class="section-header">
                <span>DATA PESERTA</span>
                <h2>{{ $competition->name }}</h2>
                <p>Isikan nama peserta untuk lomba ini. Maksimal {{ $max }} tim/peserta.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('participants.store', $competition->id) }}">
                @csrf
                <div id="participants-container">
                    @for ($i = 0; $i < $max; $i++)
                        <div class="form-group">
                            <label class="form-label">Nama Peserta {{ $i+1 }}</label>
                            <input type="text" name="names[]" class="form-control"
                                   value="{{ $existingParticipants[$i]->name ?? '' }}"
                                   placeholder="Nama lengkap">
                        </div>
                    @endfor
                </div>
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-save"></i> Simpan Peserta
                </button>
                <a href="{{ route('competitions.index') }}" class="btn btn-secondary btn-block mt-2">
                    Kembali ke Daftar Lomba
                </a>
            </form>
        </div>
    </section>

    @include('partials.footer')
@endsection