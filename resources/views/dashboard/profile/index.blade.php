@extends('layouts.app')

@section('title', 'Profil Unit')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">

<section class="unit-profile-page up-page" style="padding: 20px 0 60px;">
    <div class="up-container">

        {{-- =====================================================
             HERO
             ===================================================== --}}
        <header class="up-hero">
            <span class="up-eyebrow">
                <i class="fas fa-school"></i>
                Profil Unit
            </span>

            <h1>Kelola Data & Dokumen</h1>

            <p>
                Perbarui informasi sekolah, dokumen daftar ulang, data lomba,
                dan kartu peserta dari satu halaman.
            </p>
        </header>


        {{-- =====================================================
             FLASH MESSAGES
             ===================================================== --}}

        @if(session('success'))
            <div class="up-alert up-alert-success alert-dismissible fade show" role="alert">

                <span class="up-alert-icon">
                    <i class="fas fa-check"></i>
                </span>

                <div class="up-alert-content">
                    {{ session('success') }}
                </div>

                <button type="button"
                        class="close"
                        data-dismiss="alert"
                        aria-label="Tutup notifikasi">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>
        @endif


        @if(session('error'))
            <div class="up-alert up-alert-danger alert-dismissible fade show" role="alert">

                <span class="up-alert-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </span>

                <div class="up-alert-content">
                    {{ session('error') }}
                </div>

                <button type="button"
                        class="close"
                        data-dismiss="alert"
                        aria-label="Tutup notifikasi">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>
        @endif


        {{-- =====================================================
             1. PROFIL UNIT
             ===================================================== --}}
        <section class="up-card" aria-labelledby="schoolProfileTitle">

            <div class="up-card-header">

                <h2 class="up-card-title" id="schoolProfileTitle">

                    <span class="up-title-icon">
                        <i class="fas fa-school"></i>
                    </span>

                    Data Sekolah

                </h2>

                <span class="up-status up-status-info">
                    <i class="fas fa-pen"></i>
                    Dapat diedit
                </span>

            </div>


            <div class="up-card-body">

                <form method="POST" action="{{ route('profile.update') }}">

                    @csrf

                    <div class="up-form-grid">

                        {{-- Nama Sekolah --}}
                        <div class="up-form-group">

                            <label class="up-form-label" for="school_name">
                                Nama Sekolah
                                <span class="up-required">*</span>
                            </label>

                            <input type="text"
                                   id="school_name"
                                   name="school_name"
                                   class="form-control up-form-control"
                                   value="{{ old('school_name', $unit->school_name) }}"
                                   autocomplete="organization"
                                   required>

                        </div>


                        {{-- Alamat --}}
                        <div class="up-form-group up-span-2">

                            <label class="up-form-label" for="address">
                                Alamat
                                <span class="up-required">*</span>
                            </label>

                            <textarea id="address"
                                      name="address"
                                      class="form-control up-form-control"
                                      rows="2"
                                      autocomplete="street-address"
                                      required>{{ old('address', $unit->address) }}</textarea>

                        </div>


                        {{-- Kabupaten/Kota --}}
                        <div class="up-form-group">

                            <label class="up-form-label" for="city">
                                Kabupaten/Kota
                                <span class="up-required">*</span>
                            </label>

                            <input type="text"
                                   id="city"
                                   name="city"
                                   class="form-control up-form-control"
                                   value="{{ old('city', $unit->city) }}"
                                   required>

                        </div>


                        {{-- Kode Pos --}}
                        <div class="up-form-group">

                            <label class="up-form-label" for="postal_code">
                                Kode Pos
                                <span class="up-required">*</span>
                            </label>

                            <input type="text"
                                   id="postal_code"
                                   name="postal_code"
                                   class="form-control up-form-control"
                                   value="{{ old('postal_code', $unit->postal_code) }}"
                                   inputmode="numeric"
                                   autocomplete="postal-code"
                                   required>

                        </div>


                        {{-- Pembina --}}
                        <div class="up-form-group">

                            <label class="up-form-label" for="coach_name">
                                Pembina
                                <span class="up-required">*</span>
                            </label>

                            <input type="text"
                                   id="coach_name"
                                   name="coach_name"
                                   class="form-control up-form-control"
                                   value="{{ old('coach_name', $unit->coach_name) }}"
                                   required>

                        </div>


                        {{-- Pelatih --}}
                        <div class="up-form-group">

                            <label class="up-form-label" for="trainer_name">
                                Pelatih/Fasilitator
                                <span class="up-required">*</span>
                            </label>

                            <input type="text"
                                   id="trainer_name"
                                   name="trainer_name"
                                   class="form-control up-form-control"
                                   value="{{ old('trainer_name', $unit->trainer_name) }}"
                                   required>

                        </div>


                        {{-- Komandan --}}
                        <div class="up-form-group">

                            <label class="up-form-label" for="commander_name">
                                Komandan
                                <span class="up-required">*</span>
                            </label>

                            <input type="text"
                                   id="commander_name"
                                   name="commander_name"
                                   class="form-control up-form-control"
                                   value="{{ old('commander_name', $unit->commander_name) }}"
                                   required>

                        </div>

                    </div>


                    <div class="up-form-footer">

                        <span class="up-form-footer-note">
                            <i class="fas fa-info-circle mr-1"></i>
                            Pastikan data yang disimpan sudah benar.
                        </span>

                        <button type="submit"
                                class="btn btn-primary up-primary-btn">

                            <i class="fas fa-save mr-2"></i>
                            Simpan Perubahan

                        </button>

                    </div>

                </form>

            </div>

        </section>


        {{-- =====================================================
             2. DAFTAR ULANG
             ===================================================== --}}
        <section class="up-card" aria-labelledby="reRegistrationTitle">

            <div class="up-card-header">

                <h2 class="up-card-title" id="reRegistrationTitle">

                    <span class="up-title-icon">
                        <i class="fas fa-file-alt"></i>
                    </span>

                    Daftar Ulang

                </h2>


                @if($daftarUlang)

                    @if($daftarUlang->status == 'verified')

                        <span class="up-status up-status-success">
                            <i class="fas fa-check-circle"></i>
                            Terverifikasi
                        </span>

                    @elseif($daftarUlang->status == 'rejected')

                        <span class="up-status up-status-danger">
                            <i class="fas fa-times-circle"></i>
                            Ditolak
                        </span>

                    @else

                        <span class="up-status up-status-warning">
                            <i class="fas fa-clock"></i>
                            Menunggu Verifikasi
                        </span>

                    @endif

                @else

                    <span class="up-status up-status-muted">
                        <i class="fas fa-upload"></i>
                        Belum diunggah
                    </span>

                @endif

            </div>


            <div class="up-card-body">

                @if($daftarUlang)

                    {{-- =================================================
                         DOKUMEN YANG SUDAH DIUPLOAD
                         ================================================= --}}
                    <div class="up-document-state">

                        <div class="up-document-info">

                            <span class="up-document-icon">
                                <i class="fas fa-file-pdf"></i>
                            </span>

                            <div>

                                <p class="up-document-name">
                                    Dokumen Daftar Ulang
                                </p>

                                <p class="up-document-meta">
                                    Dokumen sudah tersimpan di Google Drive.
                                </p>

                            </div>

                        </div>


                        {{-- =================================================
                             LIHAT DOKUMEN GOOGLE DRIVE
                             ================================================= --}}
                        @if($daftarUlang->file_path)

                            <a href="{{ route('documents.view', $daftarUlang->id) }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="up-document-link">

                                <i class="fas fa-external-link-alt"></i>
                                Lihat Dokumen

                            </a>

                        @else

                            <span class="text-muted">
                                <i class="fas fa-file-slash mr-1"></i>
                                File tidak tersedia
                            </span>

                        @endif

                    </div>


                    <div class="up-upload-info">

                        <i class="fas fa-info-circle mr-1 text-primary"></i>

                        Ingin mengunggah ulang?
                        Unggah file baru di bawah ini.
                        Dokumen baru akan mengikuti proses verifikasi yang berlaku.

                    </div>

                @else

                    <div class="up-upload-info">

                        <i class="fas fa-info-circle mr-1 text-primary"></i>

                        Unggah Surat Keterangan, Kartu Pelajar, atau Kartu Anggota PMR.
                        Format <strong>JPG, PNG, atau PDF</strong>
                        dengan ukuran maksimal <strong>2 MB</strong>.

                    </div>

                @endif


                {{-- =================================================
                     FORM UPLOAD DAFTAR ULANG (DENGAN SPINNER)
                     ================================================= --}}
                <form method="POST"
                      action="{{ route('profile.daftar-ulang.upload') }}"
                      enctype="multipart/form-data"
                      class="up-upload-form upload-form">

                    @csrf

                    <div class="form-group mb-3">

                        <label class="up-form-label" for="daftarUlangFile">

                            Pilih Dokumen

                            <span class="up-required">*</span>

                        </label>

                        <div class="custom-file">

                            <input type="file"
                                   name="file"
                                   id="daftarUlangFile"
                                   class="custom-file-input up-file-input"
                                   accept="image/jpeg,image/png,application/pdf"
                                   required>

                            <label class="custom-file-label up-file-label"
                                   for="daftarUlangFile">

                                Pilih file...

                            </label>

                        </div>

                    </div>


                    <button type="submit"
                            class="btn btn-primary up-upload-btn">

                        <i class="fas fa-cloud-upload-alt mr-2"></i>
                        Unggah Dokumen

                    </button>

                </form>

            </div>

        </section>


        {{-- =====================================================
             3. LOMBA SAYA
             ===================================================== --}}
        <section class="up-card" aria-labelledby="myCompetitionTitle">

            <div class="up-card-header">

                <h2 class="up-card-title" id="myCompetitionTitle">

                    <span class="up-title-icon">
                        <i class="fas fa-trophy"></i>
                    </span>

                    Lomba Saya

                </h2>


                @if(!$registrations->isEmpty())

                    <span class="up-status up-status-muted">

                        <i class="fas fa-list"></i>

                        {{ $registrations->count() }} Lomba

                    </span>

                @endif

            </div>


            <div class="up-card-body p-0">

                @if($registrations->isEmpty())

                    <div class="up-empty">

                        <div class="up-empty-icon">
                            <i class="fas fa-trophy"></i>
                        </div>

                        <h4>
                            Belum mendaftar lomba
                        </h4>

                        <p>
                            Belum ada lomba yang terdaftar pada unit Anda.
                            Silakan pilih lomba melalui dashboard untuk mulai mendaftar.
                        </p>

                        <a href="{{ route('dashboard') }}"
                           class="btn btn-primary btn-sm">

                            <i class="fas fa-plus mr-1"></i>
                            Pilih Lomba

                        </a>

                    </div>

                @else

                    <div class="up-table-wrap">

                        <table class="up-table">

                            <thead>

                                <tr>
                                    <th>Lomba</th>
                                    <th>Status</th>
                                    <th>Pembayaran</th>
                                    <th>Dokumen Lomba</th>
                                </tr>

                            </thead>


                            <tbody>

                                @foreach($registrations as $reg)

                                    <tr>

                                        {{-- =================================================
                                             LOMBA
                                             ================================================= --}}
                                        <td>

                                            <span class="up-competition-name">
                                                {{ $reg->competition->name ?? 'Lomba tidak ditemukan' }}
                                            </span>

                                        </td>


                                        {{-- =================================================
                                             STATUS
                                             ================================================= --}}
                                        <td>

                                            <span class="up-status up-status-info">
                                                {{ ucfirst($reg->status) }}
                                            </span>

                                        </td>


                                        {{-- =================================================
                                             PEMBAYARAN
                                             ================================================= --}}
                                        <td>

                                            <div class="up-payment-action">

                                                @if($reg->payment_status == 'pending')

                                                    <span class="up-status up-status-warning">

                                                        <i class="fas fa-exclamation-circle"></i>
                                                        Belum Bayar

                                                    </span>

                                                    <a href="{{ route('status.index') }}"
                                                       class="btn btn-sm btn-outline-primary up-action-btn">

                                                        <i class="fas fa-wallet mr-1"></i>
                                                        Bayar

                                                    </a>

                                                @elseif($reg->payment_status == 'paid')

                                                    <span class="up-status up-status-info">

                                                        <i class="fas fa-clock"></i>
                                                        Menunggu Verifikasi

                                                    </span>

                                                @else

                                                    <span class="up-status up-status-success">

                                                        <i class="fas fa-check-double"></i>
                                                        Lunas

                                                    </span>

                                                @endif

                                            </div>

                                        </td>


                                        {{-- =================================================
                                             DOKUMEN LOMBA (DENGAN SPINNER)
                                             ================================================= --}}
                                        <td>

                                            @if($reg->payment_status == 'verified')

                                                @if($reg->competition->requires_upload ?? false)

                                                    @php

                                                        $lombaUpload = $reg->uploads
                                                            ->where('type', 'lomba')
                                                            ->first();

                                                    @endphp


                                                    @if($lombaUpload)

                                                        {{-- =====================================
                                                             FILE LOMBA
                                                             ===================================== --}}
                                                        @if($lombaUpload->file_path)

                                                            <a href="{{ route('documents.view', $lombaUpload->id) }}"
                                                               target="_blank"
                                                               rel="noopener noreferrer"
                                                               class="btn btn-sm btn-outline-success up-action-btn">

                                                                <i class="fas fa-file-audio mr-1"></i>
                                                                Dengarkan

                                                            </a>


                                                        {{-- =====================================
                                                             LINK LOMBA
                                                             ===================================== --}}
                                                        @elseif($lombaUpload->submission_link)

                                                            <a href="{{ $lombaUpload->submission_link }}"
                                                               target="_blank"
                                                               rel="noopener noreferrer"
                                                               class="btn btn-sm btn-outline-success up-action-btn">

                                                                <i class="fas fa-link mr-1"></i>
                                                                Lihat Link

                                                            </a>

                                                        @endif


                                                    @else

                                                        {{-- =====================================
                                                             FORM UPLOAD KARYA (DENGAN SPINNER)
                                                             ===================================== --}}
                                                        <form method="POST"
                                                              action="{{ route('profile.lomba.upload', $reg->id) }}"
                                                              enctype="multipart/form-data"
                                                              class="up-inline-upload upload-form">

                                                            @csrf


                                                            @if($reg->competition->upload_type == 'file')

                                                                <div class="form-group">

                                                                    <label class="up-form-label">
                                                                        Upload File Suara (MP3/WAV)
                                                                    </label>

                                                                    <input type="file"
                                                                           name="file"
                                                                           class="form-control up-form-control"
                                                                           accept="audio/*"
                                                                           required>

                                                                </div>


                                                            @elseif($reg->competition->upload_type == 'link')

                                                                <div class="form-group">

                                                                    <label class="up-form-label">
                                                                        Link Video
                                                                    </label>

                                                                    <input type="url"
                                                                           name="link"
                                                                           class="form-control up-form-control"
                                                                           placeholder="https://..."
                                                                           required>

                                                                </div>

                                                            @endif


                                                            <button type="submit"
                                                                    class="btn btn-sm btn-outline-success up-action-btn">

                                                                <i class="fas fa-cloud-upload-alt mr-1"></i>
                                                                Upload

                                                            </button>

                                                        </form>

                                                    @endif

                                                @else

                                                    <span class="text-muted">
                                                        Tidak ada dokumen
                                                    </span>

                                                @endif

                                            @else

                                                <span class="up-muted-action">

                                                    <i class="fas fa-lock mr-1"></i>
                                                    Selesaikan pembayaran

                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @endif

            </div>


            {{-- =====================================================
                 CTA KE HALAMAN STATUS PENDAFTARAN
                 ===================================================== --}}
            <div class="up-competition-footer">

                <a href="{{ route('status.index') }}"
                   class="btn btn-primary up-status-btn">

                    <i class="fas fa-chart-line mr-2"></i>

                    Lihat Status Pendaftaran & Pembayaran

                </a>

            </div>

        </section>


        {{-- =====================================================
             4. ID TIM & KARTU PESERTA
             ===================================================== --}}
        <section class="up-card" aria-labelledby="teamCardTitle">

            <div class="up-card-header">

                <h2 class="up-card-title" id="teamCardTitle">

                    <span class="up-title-icon">
                        <i class="fas fa-id-card"></i>
                    </span>

                    ID Tim & Kartu Peserta

                </h2>


                @if(!$registrations->isEmpty())

                    <span class="up-status up-status-muted">

                        <i class="fas fa-users"></i>
                        Data Peserta

                    </span>

                @endif

            </div>


            <div class="up-card-body p-0">

                @if($registrations->isEmpty())

                    <div class="up-empty">

                        <div class="up-empty-icon">
                            <i class="fas fa-id-card"></i>
                        </div>

                        <h4>
                            Belum ada data tim
                        </h4>

                        <p>
                            Anda belum mendaftar lomba apa pun,
                            sehingga ID tim dan kartu peserta belum tersedia.
                        </p>

                        <a href="{{ route('dashboard') }}"
                           class="btn btn-primary btn-sm">

                            <i class="fas fa-plus mr-1"></i>
                            Pilih Lomba

                        </a>

                    </div>

                @else

                    <div class="up-table-wrap">

                        <table class="up-table team-table">

                            <thead>

                                <tr>
                                    <th>Lomba</th>
                                    <th>ID Tim</th>
                                    <th>Peserta</th>
                                    <th>Kartu</th>
                                </tr>

                            </thead>


                            <tbody>

                                @foreach($registrations as $reg)

                                    @php

                                        $participants = $reg->participants;

                                        /*
                                         * ID Tim dan kartu hanya tersedia
                                         * setelah pembayaran dilakukan.
                                         *
                                         * paid     = pembayaran sudah dikirim,
                                         * verified = pembayaran sudah diverifikasi.
                                         */

                                        $hasPaid = in_array(
                                            $reg->payment_status,
                                            ['paid', 'verified']
                                        );

                                    @endphp


                                    <tr>

                                        {{-- =================================================
                                             LOMBA
                                             ================================================= --}}
                                        <td>

                                            <span class="up-competition-name">
                                                {{ $reg->competition->name ?? 'Lomba tidak ditemukan' }}
                                            </span>

                                        </td>


                                        {{-- =================================================
                                             ID TIM
                                             ================================================= --}}
                                        <td>

                                            @if($hasPaid)

                                                <span class="up-team-code">

                                                    <i class="fas fa-hashtag mr-1"></i>

                                                    {{ $reg->registration_code ?? 'Belum tersedia' }}

                                                </span>

                                            @else

                                                <span class="up-muted-action">

                                                    <i class="fas fa-lock mr-1"></i>

                                                    Terkunci

                                                </span>

                                            @endif

                                        </td>


                                        {{-- =================================================
                                             PESERTA
                                             ================================================= --}}
                                        <td>

                                            <ul class="up-participants">

                                                @if(
                                                    $reg->competition &&
                                                    $reg->competition->name ==
                                                    'Gerakan Pungut Sampah (GPS)'
                                                )

                                                    <li class="up-participant">

                                                        {{ $unit->school_name }}

                                                        <small class="text-muted">
                                                            (Seluruh Unit)
                                                        </small>

                                                    </li>

                                                @else

                                                    @forelse($participants as $p)

                                                        <li class="up-participant">
                                                            {{ $p->name }}
                                                        </li>

                                                    @empty

                                                        <li class="text-muted">
                                                            Belum diisi
                                                        </li>

                                                    @endforelse

                                                @endif

                                            </ul>

                                        </td>


                                        {{-- =================================================
                                             KARTU PESERTA
                                             ================================================= --}}
                                        <td>

                                            @if($hasPaid)

                                                <a href="{{ route('card.download', $reg->id) }}"
                                                   class="btn btn-sm btn-outline-primary up-download-btn"
                                                   target="_blank"
                                                   rel="noopener noreferrer">

                                                    <i class="fas fa-download"></i>
                                                    Unduh Kartu

                                                </a>

                                            @else

                                                <span class="up-muted-action">

                                                    <i class="fas fa-lock mr-1"></i>

                                                    Selesaikan pembayaran

                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @endif

            </div>

        </section>

    </div>
</section>

<script src="{{ asset('assets/js/profile.js') }}"></script>

{{-- =========================================================
     PERBAIKAN UI/UX: SPINNER UNTUK UPLOAD
     ========================================================= --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Semua form dengan class upload-form akan disable tombol saat submit
    document.querySelectorAll('.upload-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                const originalHtml = btn.innerHTML;
                btn.dataset.originalHtml = originalHtml;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengunggah...';
                // Fallback: enable kembali setelah 30 detik
                setTimeout(function() {
                    if (btn.disabled) {
                        btn.disabled = false;
                        btn.innerHTML = btn.dataset.originalHtml || 'Submit';
                    }
                }, 30000);
            }
        });
    });
});
</script>

@endsection