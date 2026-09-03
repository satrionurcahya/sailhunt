@extends('layouts.landing')

@section('content')

@include('partials.navbar')

<link rel="stylesheet" href="{{ asset('assets/css/competitions.css') }}">

<section class="competition-page cl-page">
    <div class="cl-container">

        <header class="cl-hero">
            <span class="cl-eyebrow"><i class="fas fa-trophy"></i> Daftar Lomba</span>
            <h1>Pilih Mata Lomba & Isi Peserta</h1>
            <p>Pilih lomba yang ingin diikuti, lalu lengkapi nama peserta untuk setiap tim. GPS otomatis diikutsertakan sesuai ketentuan lomba.</p>
        </header>

        @if(session('success'))
            <div class="cl-alert cl-alert-success alert-dismissible fade show" role="alert">
                <span class="cl-alert-icon"><i class="fas fa-check"></i></span>
                <div>{{ session('success') }}</div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Tutup notifikasi">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="cl-alert cl-alert-danger alert-dismissible fade show" role="alert">
                <span class="cl-alert-icon"><i class="fas fa-exclamation-triangle"></i></span>
                <div>{{ session('error') }}</div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Tutup notifikasi">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="cl-list-summary">
            <div class="cl-summary-text">
                <i class="fas fa-info-circle"></i>
                <span>Centang lomba untuk membuka form peserta dan melanjutkan pendaftaran.</span>
            </div>
            <span class="cl-summary-badge">
                <i class="fas fa-list"></i> {{ $competitions->count() }} Mata Lomba
            </span>
        </div>

        <form method="POST" action="{{ route('competitions.storeBatch') }}">
            @csrf

            <div class="competition-cards">
                @foreach($competitions as $lomba)
                    @php
                        $isRegistered = in_array($lomba->id, $registeredIds);
                        $existingRegs = $existingRegistrations[$lomba->id] ?? [];

                        $maxTeams = $lomba->max_teams;
                        $teamSize = $lomba->team_size;

                        $deadlinePassed = $lomba->registration_deadline && now()->greaterThan($lomba->registration_deadline);
                    @endphp

                    <div class="comp-card-single {{ $isRegistered ? 'is-active' : '' }}" id="card-{{ $lomba->id }}">
                        <div class="card-header-bar">
                            <label class="checkbox-label" title="Pilih {{ $lomba->name }}">
                                <input type="checkbox"
                                       name="competitions[{{ $lomba->id }}][active]"
                                       value="1"
                                       class="lomba-checkbox"
                                       data-target="body-{{ $lomba->id }}"
                                       {{ $isRegistered ? 'checked' : '' }}
                                       {{ ($isRegistered && $maxTeams == 1 && count($existingRegs) > 0) ? 'disabled' : '' }}
                                       {{ $deadlinePassed ? 'disabled' : '' }}
                                       aria-label="Pilih lomba {{ $lomba->name }}">
                            </label>

                            <div class="card-title">
                                <h3>{{ $lomba->name }}</h3>
                                <span class="card-category"><i class="fas fa-tag"></i> {{ $lomba->category }}</span>
                            </div>

                            <div class="card-meta">
                                @if($lomba->registration_deadline)
                                    <span class="deadline-badge {{ $deadlinePassed ? 'is-closed' : '' }}">
                                        <i class="fas {{ $deadlinePassed ? 'fa-lock' : 'fa-clock' }}"></i>
                                        {{ $deadlinePassed ? 'Pendaftaran ditutup' : 'Sampai ' . $lomba->registration_deadline->translatedFormat('d M Y, H:i') }}
                                    </span>
                                @endif
                                {!! $lomba->category_badge !!}
                                <div class="card-fee">Rp {{ number_format($lomba->fee, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <div class="card-body-participants {{ $deadlinePassed ? 'is-locked' : '' }}"
                             id="body-{{ $lomba->id }}"
                             style="{{ $isRegistered ? '' : 'display:none;' }}">
                            @if($deadlinePassed)
                                <div class="deadline-locked-box">
                                    <div class="deadline-locked-icon"><i class="fas fa-lock"></i></div>
                                    <div>
                                        <strong>Pendaftaran sudah ditutup.</strong>
                                        <p>Anda tidak dapat mengubah data peserta setelah batas waktu pendaftaran berakhir.</p>
                                    </div>
                                </div>
                            @endif

                            @if($isRegistered && $maxTeams == 1 && count($existingRegs) > 0)
                                <div class="registered-box">
                                    <div class="registered-box-title"><i class="fas fa-check-circle"></i> Lomba sudah terdaftar</div>
                                    <div class="registered-names">
                                        @foreach($existingRegs as $reg)
                                            @foreach($reg->participants as $p)
                                                <span class="badge badge-success">{{ $p->name }}</span>
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="teams-wrapper"
                                     data-competition="{{ $lomba->id }}"
                                     data-max-teams="{{ $maxTeams }}"
                                     data-team-size="{{ $teamSize }}"
                                     data-deadline-passed="{{ $deadlinePassed ? '1' : '0' }}">
                                    <div class="participant-heading">
                                        <h4><i class="fas fa-users mr-1"></i> Data Peserta</h4>
                                        <small>{{ $teamSize }} peserta per tim @if($maxTeams > 1) • Maks. {{ $maxTeams }} tim @endif</small>
                                    </div>

                                    <div class="teams-container" id="teams-{{ $lomba->id }}">
                                        @php $teamCount = max(count($existingRegs), 1); @endphp
                                        @for($t = 0; $t < $teamCount; $t++)
                                            <div class="team-row">
                                                <span class="team-label">Tim {{ $t+1 }}</span>
                                                <div class="team-inputs">
                                                    @for($i = 0; $i < $teamSize; $i++)
                                                        <div class="team-input-wrap">
                                                            <input type="text"
                                                                   name="competitions[{{ $lomba->id }}][teams][{{ $t }}][{{ $i }}]"
                                                                   class="form-control form-control-sm participant-name-input"
                                                                   data-competition="{{ $lomba->id }}"
                                                                   data-team="{{ $t }}"
                                                                   data-participant="{{ $i }}"
                                                                   placeholder="Nama Peserta {{ $i+1 }}"
                                                                   value="{{ $existingRegs[$t]->participants[$i]->name ?? '' }}"
                                                                   autocomplete="off"
                                                                   {{ $deadlinePassed ? 'disabled' : '' }}>
                                                        </div>
                                                    @endfor
                                                </div>
                                                @if($maxTeams > 1)
                                                    <button type="button" class="btn btn-sm btn-outline-danger remove-team-btn" {{ $deadlinePassed ? 'disabled' : '' }}>
                                                        <i class="fas fa-trash-alt mr-1"></i> Hapus Tim
                                                    </button>
                                                @endif
                                            </div>
                                        @endfor
                                    </div>

                                    @if($maxTeams > 1)
                                        <button type="button" class="btn btn-sm btn-outline-primary add-team-btn"
                                                data-competition="{{ $lomba->id }}"
                                                {{ $deadlinePassed ? 'disabled' : '' }}>
                                            <i class="fas fa-plus-circle mr-1"></i> Tambah Tim
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="cl-actions">
                <button type="submit" class="btn btn-primary btn-lg cl-primary-action">
                    <i class="fas fa-save mr-2"></i> Simpan Semua Pendaftaran
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                <a href="{{ route('status.index') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-receipt mr-2"></i> Cek Status Pembayaran
                </a>
            </div>

            <div class="cl-help">
                <i class="fas fa-shield-alt mr-1"></i> Pastikan nama peserta sudah benar sebelum menyimpan pendaftaran.
            </div>
        </form>

    </div>
</section>

@include('partials.footer')

<script src="{{ asset('assets/js/competitions.js') }}"></script>

@endsection