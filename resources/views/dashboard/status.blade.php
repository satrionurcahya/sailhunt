@extends('layouts.app')

@section('title', 'Status Pendaftaran')

@section('content')

{{-- Load page-specific stylesheet --}}
<link rel="stylesheet" href="{{ asset('assets/css/status.css') }}">

<section class="registration-status-page" style="padding: 20px 0 60px;">
    <div class="rs-container">

        {{-- =====================================================
             HERO
             ===================================================== --}}
        <header class="rs-hero">

            <span class="rs-eyebrow">
                <i class="fas fa-chart-line"></i>
                Status Pendaftaran
            </span>

            <h1>
                Pantau Status Verifikasi & Pembayaran
            </h1>

            <p>
                Berikut adalah status terkini pendaftaran unit Anda.
                Pastikan pembayaran dan dokumen yang diperlukan sudah sesuai.
            </p>

            {{-- =================================================
                 UI/UX: TOMBOL REFRESH
                 ================================================= --}}
            <div style="margin-top: 15px;">
                <button onclick="refreshStatus()" class="btn btn-sm btn-outline-light" id="refreshStatusBtn">
                    <i class="fas fa-sync-alt mr-1"></i> Refresh Data
                </button>
                <small style="color: rgba(255,255,255,0.7); margin-left: 10px;">
                    <i class="fas fa-clock"></i> Update otomatis setiap 60 detik
                </small>
            </div>

        </header>


        {{-- =====================================================
             FLASH MESSAGES
             ===================================================== --}}

        @if(session('success'))

            <div class="rs-alert rs-alert-success alert-dismissible fade show"
                 role="alert">

                <span class="rs-alert-icon">
                    <i class="fas fa-check"></i>
                </span>

                <div class="rs-alert-content">
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

            <div class="rs-alert rs-alert-danger alert-dismissible fade show"
                 role="alert">

                <span class="rs-alert-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </span>

                <div class="rs-alert-content">
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
             UPDATE INFO
             ===================================================== --}}

        <div class="rs-alert rs-alert-info"
             role="status">

            <span class="rs-alert-icon">
                <i class="fas fa-clock"></i>
            </span>

            <div class="rs-alert-content">

                <strong>Info penting.</strong>

                Status pembayaran dan verifikasi diperbarui setiap hari pukul
                <strong>22.00 - 23.00 WIB</strong>.

            </div>

        </div>


        {{-- =====================================================
             PROGRESS
             ===================================================== --}}

        <section class="rs-card"
                 aria-labelledby="progressTitle">

            <div class="rs-card-header">

                <h2 class="rs-card-title"
                    id="progressTitle">

                    <span class="rs-title-icon">
                        <i class="fas fa-route"></i>
                    </span>

                    Proses Pendaftaran

                </h2>

            </div>


            <div class="rs-progress">

                <div class="rs-progress-track">

                    <div class="rs-progress-line"
                         aria-hidden="true">
                    </div>


                    {{-- Langkah 1 --}}
                    <div class="rs-step
                        {{
                            $unit->status == 'verified'
                                ? 'rs-step-success'
                                : (
                                    $unit->status == 'rejected'
                                        ? 'rs-step-danger'
                                        : 'rs-step-warning'
                                )
                        }}">

                        <div class="rs-step-icon">

                            <i class="fas
                                {{
                                    $unit->status == 'verified'
                                        ? 'fa-check'
                                        : (
                                            $unit->status == 'rejected'
                                                ? 'fa-times'
                                                : 'fa-hourglass-half'
                                        )
                                }}">
                            </i>

                        </div>

                        <div class="rs-step-copy">

                            <div class="rs-step-label">
                                Langkah 1
                            </div>

                            <p class="rs-step-title">
                                Verifikasi Akun
                            </p>

                        </div>

                    </div>


                    {{-- Langkah 2 --}}
                    <div class="rs-step
                        {{
                            $totalTagihan > 0
                                ? 'rs-step-warning'
                                : 'rs-step-success'
                        }}">

                        <div class="rs-step-icon">

                            <i class="fas
                                {{
                                    $totalTagihan > 0
                                        ? 'fa-wallet'
                                        : 'fa-check'
                                }}">
                            </i>

                        </div>

                        <div class="rs-step-copy">

                            <div class="rs-step-label">
                                Langkah 2
                            </div>

                            <p class="rs-step-title">
                                Pembayaran
                            </p>

                        </div>

                    </div>


                    {{-- Langkah 3 --}}
                    <div class="rs-step
                        {{
                            $registrations->where('status', 'confirmed')->count() == $registrations->count()
                            && $registrations->count() > 0
                                ? 'rs-step-success'
                                : 'rs-step-muted'
                        }}">

                        <div class="rs-step-icon">
                            <i class="fas fa-flag-checkered"></i>
                        </div>

                        <div class="rs-step-copy">

                            <div class="rs-step-label">
                                Langkah 3
                            </div>

                            <p class="rs-step-title">
                                Selesai
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- =====================================================
             ACCOUNT STATUS
             ===================================================== --}}

        <section class="rs-card"
                 aria-labelledby="accountStatusTitle">

            <div class="rs-card-header">

                <h2 class="rs-card-title"
                    id="accountStatusTitle">

                    <span class="rs-title-icon">
                        <i class="fas fa-user-check"></i>
                    </span>

                    Status Akun

                </h2>


                @if($unit->status == 'verified')

                    <span class="rs-status rs-status-success">

                        <i class="fas fa-check-circle"></i>

                        Terverifikasi

                    </span>

                @elseif($unit->status == 'rejected')

                    <span class="rs-status rs-status-danger">

                        <i class="fas fa-times-circle"></i>

                        Ditolak

                    </span>

                @else

                    <span class="rs-status rs-status-warning">

                        <i class="fas fa-clock"></i>

                        Menunggu Verifikasi

                    </span>

                @endif

            </div>


            <div class="rs-card-body">

                <div class="rs-account-grid">

                    {{-- =================================================
                         STATUS PENDAFTARAN
                         ================================================= --}}

                    <div class="rs-info-item">

                        <span class="rs-info-label">
                            Status Pendaftaran
                        </span>

                        <div class="rs-info-value">

                            @if($unit->status == 'verified')

                                <span class="rs-status rs-status-success">

                                    <i class="fas fa-check"></i>

                                    Terverifikasi

                                </span>

                            @elseif($unit->status == 'rejected')

                                <span class="rs-status rs-status-danger">

                                    <i class="fas fa-times"></i>

                                    Ditolak

                                </span>

                            @else

                                <span class="rs-status rs-status-warning">

                                    <i class="fas fa-clock"></i>

                                    Menunggu Verifikasi

                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- =================================================
                         DAFTAR ULANG
                         ================================================= --}}

                    <div class="rs-info-item">

                        <span class="rs-info-label">
                            Daftar Ulang
                        </span>

                        <div class="rs-info-value">

                            @if($daftarUlang)

                                {{-- STATUS DOKUMEN --}}
                                @if($daftarUlang->status == 'verified')

                                    <span class="rs-status rs-status-success">

                                        <i class="fas fa-check"></i>

                                        Terverifikasi

                                    </span>

                                @elseif($daftarUlang->status == 'rejected')

                                    <span class="rs-status rs-status-danger">

                                        <i class="fas fa-times"></i>

                                        Ditolak

                                    </span>

                                @else

                                    <span class="rs-status rs-status-warning">

                                        <i class="fas fa-clock"></i>

                                        {{ ucfirst($daftarUlang->status) }}

                                    </span>

                                @endif


                                {{-- =================================================
                                     LIHAT FILE DARI GOOGLE DRIVE
                                     ================================================= --}}

                                @if($daftarUlang->file_path)

                                    <a href="{{ route('documents.view', $daftarUlang->id) }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="rs-file-link">

                                        <i class="fas fa-external-link-alt"></i>

                                        Lihat file

                                    </a>

                                @else

                                    <span class="text-muted">

                                        <i class="fas fa-file-slash mr-1"></i>

                                        File tidak tersedia

                                    </span>

                                @endif

                            @else

                                <span class="text-muted">

                                    <i class="fas fa-minus-circle mr-1"></i>

                                    Belum diunggah

                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- =====================================================
             PAYMENT
             ===================================================== --}}

        @if($totalTagihan > 0)

            <section class="rs-card"
                     aria-labelledby="paymentTitle">

                <div class="rs-card-header rs-payment-header">

                    <h2 class="rs-card-title"
                        id="paymentTitle">

                        <span class="rs-title-icon">
                            <i class="fas fa-receipt"></i>
                        </span>

                        Total Tagihan & Pembayaran

                    </h2>

                    <span class="rs-count">

                        <i class="fas fa-list-ul"></i>

                        {{ $pendingPayments->count() }} Lomba

                    </span>

                </div>


                <div class="rs-card-body">

                    <div class="rs-payment-grid">

                        {{-- =================================================
                             RINGKASAN TAGIHAN
                             ================================================= --}}

                        <div>

                            <div class="rs-total-box">

                                <span class="rs-total-label">
                                    Total yang perlu dibayar
                                </span>

                                <p class="rs-total-amount">

                                    Rp {{ number_format($totalTagihan, 2, ',', '.') }}

                                </p>

                                <p class="rs-terbilang">

                                    <i class="fas fa-quote-left mr-1"></i>

                                    {{ $terbilang }}

                                </p>

                            </div>


                            <div class="rs-subtitle">

                                <span>
                                    Rincian Biaya
                                </span>

                                <small>
                                    {{ $pendingPayments->count() }} item
                                </small>

                            </div>


                            <div class="rs-fee-list">

                                @foreach($pendingPayments as $reg)

                                    <div class="rs-fee-row">

                                        <div class="rs-fee-name">

                                            <i class="fas fa-trophy"></i>

                                            <span>
                                                {{ $reg->competition->name }}
                                            </span>

                                            {!! $reg->competition->category_badge !!}

                                        </div>

                                        <span class="rs-fee-price">

                                            Rp {{ number_format($reg->competition->fee, 2, ',', '.') }}

                                        </span>

                                    </div>

                                @endforeach


                                <div class="rs-fee-row rs-fee-total">

                                    <div class="rs-fee-name font-weight-bold">

                                        <i class="fas fa-calculator"></i>

                                        <span>
                                            Total
                                        </span>

                                    </div>

                                    <span class="rs-fee-price">

                                        Rp {{ number_format($totalTagihan, 2, ',', '.') }}

                                    </span>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             METODE PEMBAYARAN + CTA
                             ================================================= --}}

                        <div>

                            <h3 class="rs-method-title">

                                <i class="fas fa-credit-card mr-1"></i>

                                Metode Pembayaran

                            </h3>


                            <div class="rs-method-grid">

                                {{-- TRANSFER --}}
                                <div class="rs-method">

                                    <div class="rs-method-name">

                                        <i class="fas fa-university"></i>

                                        Transfer

                                    </div>


                                    <div class="rs-bank-box">

                                        <span class="rs-bank-label">
                                            Nomor rekening
                                        </span>

                                        <strong class="rs-account-number text-monospace"
                                                id="rekeningText"
                                                aria-label="Nomor rekening pembayaran">

                                            107445606623

                                        </strong>


                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary btn-block rs-copy-btn"
                                                onclick="copyRekening(event)"
                                                aria-label="Salin nomor rekening">

                                            <i class="fas fa-copy mr-1"></i>

                                            Salin

                                        </button>

                                    </div>


                                    <p class="rs-owner">

                                        a.n.
                                        <strong>
                                            Satrio Nurcahya
                                        </strong>

                                    </p>


                                    <span class="rs-status rs-status-muted rs-bank-badge">

                                        <i class="fas fa-building"></i>

                                        Bank Jago

                                    </span>

                                </div>


                                {{-- QRIS --}}
                                <div class="rs-method">

                                    <div class="rs-method-name">

                                        <i class="fas fa-qrcode"></i>

                                        QRIS

                                    </div>


                                    <div class="rs-qris-wrap">

                                        <button type="button"
                                                class="rs-qris-button"
                                                data-toggle="modal"
                                                data-target="#qrisModal"
                                                aria-label="Buka QRIS pembayaran">

                                            <img src="{{ asset('assets/images/qris_pembayaran.png') }}"
                                                 alt="QRIS pembayaran">

                                        </button>

                                    </div>


                                    <p class="rs-qris-hint">

                                        <i class="fas fa-search-plus mr-1"></i>

                                        Klik untuk perbesar

                                    </p>

                                </div>

                            </div>


                            {{-- CTA UPLOAD --}}
                            <div class="rs-upload-box">

                                <h3 class="rs-upload-title">

                                    <i class="fas fa-paper-plane"></i>

                                    Konfirmasi Pembayaran

                                </h3>


                                <p class="rs-upload-text">

                                    Setelah melakukan pembayaran, upload bukti transfer
                                    agar admin dapat melakukan verifikasi dalam 1x24 jam.

                                </p>


                                <button type="button"
                                        class="btn btn-primary btn-block rs-upload-btn"
                                        data-toggle="modal"
                                        data-target="#batchPaymentModal">

                                    <i class="fas fa-cloud-upload-alt mr-2"></i>

                                    Upload Bukti Pembayaran

                                </button>


                                <div class="rs-help">

                                    <p class="rs-help-title">
                                        Butuh bantuan? Hubungi admin.
                                    </p>


                                    <div class="rs-help-grid">

                                        <a href="https://wa.me/6287779726472"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="btn btn-outline-success btn-sm">

                                            <i class="fab fa-whatsapp mr-1"></i>

                                            Bahira Putri

                                        </a>


                                        <a href="https://wa.me/6285865441811"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="btn btn-outline-success btn-sm">

                                            <i class="fab fa-whatsapp mr-1"></i>

                                            Kayla Aghnia

                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </section>

        @else

            {{-- SEMUA TAGIHAN LUNAS --}}
            <div class="rs-success-state"
                 role="status">

                <span class="rs-success-icon">

                    <i class="fas fa-check"></i>

                </span>

                <div>

                    <strong>
                        Semua tagihan sudah lunas!
                    </strong>

                    <span>
                        Terima kasih, tidak ada pembayaran yang perlu diselesaikan.
                    </span>

                </div>

            </div>

        @endif


        {{-- =====================================================
             COMPETITION STATUS
             ===================================================== --}}

        <section class="rs-card"
                 aria-labelledby="competitionStatusTitle">

            <div class="rs-card-header">

                <h2 class="rs-card-title"
                    id="competitionStatusTitle">

                    <span class="rs-title-icon">
                        <i class="fas fa-trophy"></i>
                    </span>

                    Status Lomba & Pembayaran

                </h2>


                @if($registrations->count() > 0)

                    <span class="rs-status rs-status-muted">

                        <i class="fas fa-list"></i>

                        {{ $registrations->count() }} Lomba

                    </span>

                @endif

            </div>


            <div class="rs-card-body p-0">

                @if($registrations->isEmpty())

                    <div class="rs-empty">

                        <div class="rs-empty-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>

                        <h4>
                            Belum mendaftar lomba apa pun
                        </h4>

                        <p>
                            Silakan pilih lomba melalui dashboard untuk mulai mendaftar.
                        </p>

                        <a href="{{ route('dashboard') }}"
                           class="btn btn-primary btn-sm">

                            <i class="fas fa-plus mr-1"></i>

                            Pilih Lomba

                        </a>

                    </div>

                @else

                    <div class="rs-table-wrap">

                        <table class="rs-table">

                            <thead>

                                <tr>

                                    <th width="55">
                                        #
                                    </th>

                                    <th>
                                        Lomba
                                    </th>

                                    <th>
                                        Treasure / Bounty
                                    </th>

                                    <th>
                                        Status Pendaftaran
                                    </th>

                                    <th>
                                        Pembayaran
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($registrations as $index => $reg)

                                    <tr>

                                        <td class="rs-index">
                                            {{ $index + 1 }}
                                        </td>


                                        <td>

                                            <span class="rs-competition-name">
                                                {{ $reg->competition->name }}
                                            </span>

                                        </td>


                                        <td>
                                            {!! $reg->competition->category_badge !!}
                                        </td>


                                        <td>

                                            @if($reg->status == 'confirmed')

                                                <span class="rs-status rs-status-success">

                                                    <i class="fas fa-check"></i>

                                                    Dikonfirmasi

                                                </span>

                                            @else

                                                <span class="rs-status rs-status-muted">

                                                    {{ ucfirst($reg->status) }}

                                                </span>

                                            @endif

                                        </td>


                                        <td>

                                            @if($reg->payment_status == 'verified')

                                                <span class="rs-status rs-status-success">

                                                    <i class="fas fa-check-double"></i>

                                                    Lunas

                                                </span>

                                            @elseif($reg->payment_status == 'paid')

                                                <span class="rs-status rs-status-info">

                                                    <i class="fas fa-clock"></i>

                                                    Menunggu Verifikasi

                                                </span>

                                            @else

                                                <span class="rs-status rs-status-warning">

                                                    <i class="fas fa-exclamation-circle"></i>

                                                    Belum Bayar

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


        {{-- =====================================================
             BACK TO DASHBOARD
             ===================================================== --}}

        <div class="rs-bottom-nav">

            <a href="{{ route('dashboard') }}"
               class="btn btn-outline-secondary rs-back-btn">

                <i class="fas fa-arrow-left mr-2"></i>

                Kembali ke Dashboard

            </a>

        </div>

    </div>
</section>


{{-- =========================================================
     MODAL: UPLOAD BUKTI PEMBAYARAN
     ========================================================= --}}

<div class="modal fade"
     id="batchPaymentModal"
     tabindex="-1"
     role="dialog"
     aria-labelledby="batchPaymentLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered"
         role="document">

        <div class="modal-content">

            <form method="POST"
                  action="{{ route('payment.storeBatch') }}"
                  enctype="multipart/form-data"
                  id="paymentForm"
                  class="upload-form">

                @csrf


                <div class="modal-header">

                    <h5 class="modal-title"
                        id="batchPaymentLabel">

                        <i class="fas fa-cloud-upload-alt mr-2"></i>

                        Upload Bukti Pembayaran

                    </h5>


                    <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Tutup modal">

                        <span aria-hidden="true">
                            &times;
                        </span>

                    </button>

                </div>


                {{-- MODAL BODY --}}
                <div class="modal-body"
                     style="max-height: 60vh; overflow-y: auto; padding: 20px;">

                    <div class="rs-modal-total">

                        <div class="rs-modal-total-row mb-1">

                            <span class="small text-muted">
                                Total Tagihan
                            </span>

                            <strong class="text-primary">

                                Rp {{ number_format($totalTagihan, 2, ',', '.') }}

                            </strong>

                        </div>


                        <p class="small text-muted mb-0">

                            Pastikan nominal transfer sesuai dengan tagihan.

                        </p>

                    </div>


                    <div class="form-group">

                        <label for="payment_type">

                            Jenis Pembayaran

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <select name="payment_type"
                                id="payment_type"
                                class="form-control"
                                required>

                            <option value="">
                                -- Pilih Jenis Pembayaran --
                            </option>

                            <option value="dp">

                                DP 60%

                                (Rp {{ number_format($totalTagihan * 0.6, 2, ',', '.') }})

                            </option>

                            <option value="lunas">

                                Lunas

                                (Rp {{ number_format($totalTagihan, 2, ',', '.') }})

                            </option>

                        </select>


                        <small class="form-text text-muted">

                            Pilih sesuai kesepakatan dengan admin.

                        </small>

                    </div>


                    <div class="form-group mb-0">

                        <label for="paymentFile">

                            Upload Bukti Transfer

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <div class="custom-file">

                            <input type="file"
                                   name="file"
                                   class="custom-file-input"
                                   id="paymentFile"
                                   accept="image/*,.pdf"
                                   required
                                   onchange="previewFile(this)">

                            <label class="custom-file-label"
                                   for="paymentFile">

                                Pilih file...

                            </label>

                        </div>


                        <small class="form-text text-muted">

                            JPG, PNG, atau PDF • Maksimal 2 MB

                        </small>


                        <div id="filePreview"
                             class="mt-3 d-none text-center">

                            <div class="border rounded p-2 bg-light">

                                <img id="previewImage"
                                     src=""
                                     alt="Preview bukti pembayaran"
                                     class="img-fluid rounded"
                                     style="max-height: 200px;">

                                <p id="previewFileName"
                                   class="small text-muted mt-2 mb-0">
                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-light border"
                            data-dismiss="modal">

                        Batal

                    </button>


                    <button type="submit"
                            class="btn btn-primary"
                            id="btnSubmitPayment">

                        <i class="fas fa-paper-plane mr-2"></i>

                        Kirim Bukti

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =========================================================
     MODAL: QRIS
     ========================================================= --}}

<div class="modal fade"
     id="qrisModal"
     tabindex="-1"
     role="dialog"
     aria-labelledby="qrisModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered"
         role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title"
                    id="qrisModalLabel">

                    <i class="fas fa-qrcode mr-2"></i>

                    QRIS Pembayaran

                </h5>


                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Tutup modal">

                    <span aria-hidden="true">
                        &times;
                    </span>

                </button>

            </div>


            <div class="modal-body">

                <img src="{{ asset('assets/images/qris_pembayaran.png') }}"
                     alt="QRIS Pembayaran"
                     class="qris-large-image">

                <p class="small text-muted mt-3 mb-0">

                    Scan kode QR menggunakan aplikasi e-wallet
                    atau mobile banking Anda.

                </p>

            </div>


            <div class="modal-footer justify-content-center">

                <button type="button"
                        class="btn btn-light border"
                        data-dismiss="modal">

                    Tutup

                </button>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     JAVASCRIPT DEPENDENCIES
     ========================================================= --}}

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- Page-specific JavaScript --}}
<script src="{{ asset('assets/js/status.js') }}"></script>

{{-- =========================================================
     UI/UX: REFRESH STATUS
     ========================================================= --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Refresh otomatis setiap 60 detik
    setInterval(function() {
        refreshStatus();
    }, 60000);
});

function refreshStatus() {
    const btn = document.getElementById('refreshStatusBtn');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Memuat...';
    }
    // Reload halaman
    window.location.reload();
}
</script>

@endsection