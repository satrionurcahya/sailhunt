@extends('layouts.app')

@section('title', 'Kartu Peserta - ' . $registration->registration_code)

@section('content')

<div class="participant-card-page">

    {{-- =========================================================
         ACTION BUTTON
         ========================================================= --}}
    <div class="participant-card-actions no-print">

        <a href="{{ route('participant-cards.index') }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left mr-1"></i>
            Kembali

        </a>

        <button type="button"
                onclick="window.print()"
                class="btn btn-primary">

            <i class="fas fa-print mr-1"></i>
            Cetak Kartu

        </button>

    </div>


    {{-- =========================================================
         KARTU PESERTA
         ========================================================= --}}
    <div class="participant-card">

        {{-- GUNAKAN IMG, BUKAN BACKGROUND-IMAGE
             agar gambar ikut tercetak --}}
        <img src="{{ asset('assets/images/kartu-peserta.png') }}"
             alt="Kartu Peserta Sail & Hunt"
             class="participant-card-background">


        {{-- =====================================================
             KODE LOMBA
             ===================================================== --}}
        <div class="participant-card-code">
            {{ $registration->registration_code }}
        </div>


        {{-- =====================================================
             NAMA SEKOLAH
             ===================================================== --}}
        <div class="participant-card-school">
            {{ $registration->unit->school_name }}
        </div>

    </div>


    {{-- =========================================================
         INFORMASI DI BAWAH KARTU
         ========================================================= --}}
    <div class="text-center mt-4 no-print">

        <h5 class="font-weight-bold">
            {{ $registration->registration_code }}
        </h5>

        <p class="text-muted mb-1">
            {{ $registration->competition->name }}
        </p>

        <p class="text-muted">
            {{ $registration->unit->school_name }}
        </p>

    </div>

</div>


@push('styles')

<style>

/* =========================================================
   PAGE
   ========================================================= */

.participant-card-page {
    min-height: 100vh;

    padding: 40px 20px 60px;

    display: flex;
    flex-direction: column;
    align-items: center;

    background: #f5f7fb;
}


/* =========================================================
   BUTTON
   ========================================================= */

.participant-card-actions {
    width: min(100%, 1024px);

    display: flex;
    justify-content: space-between;

    gap: 10px;

    margin-bottom: 25px;
}


/* =========================================================
   KARTU
   ========================================================= */

.participant-card {

    position: relative;

    /*
     * Ukuran layar:
     * menggunakan rasio desain asli 1024 x 1536
     */
    width: min(100%, 768px);

    aspect-ratio: 1024 / 1536;

    overflow: hidden;

    background: #ffffff;

    /*
     * Penting untuk browser yang mendukung
     * pencetakan warna/gambar.
     */
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
}


/* =========================================================
   BACKGROUND GAMBAR
   ========================================================= */

.participant-card-background {

    position: absolute;

    inset: 0;

    width: 100%;
    height: 100%;

    display: block;

    object-fit: cover;

    /*
     * Jangan sampai gambar mengganggu posisi teks.
     */
    z-index: 1;

    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
}


/* =========================================================
   KODE LOMBA
   ========================================================= */

.participant-card-code {

    position: absolute;

    left: 13%;

    top: 16%;

    width: 74%;

    height: 15.5%;

    display: flex;

    align-items: center;

    justify-content: center;

    padding: 15px;

    font-size: clamp(24px, 4.5vw, 52px);

    font-weight: 900;

    letter-spacing: 2px;

    text-align: center;

    color: #173c72;

    line-height: 1;

    z-index: 2;
}


/* =========================================================
   NAMA SEKOLAH
   ========================================================= */

.participant-card-school {

    position: absolute;

    left: 13%;

    top: 34.5%;

    width: 74%;

    height: 18%;

    display: flex;

    align-items: center;

    justify-content: center;

    padding: 20px;

    font-size: clamp(18px, 3.2vw, 38px);

    font-weight: 900;

    text-align: center;

    text-transform: uppercase;

    color: #173c72;

    line-height: 1.15;

    z-index: 2;

    overflow-wrap: anywhere;
}


/* =========================================================
   DESKTOP
   ========================================================= */

@media screen and (max-width: 768px) {

    .participant-card-page {
        padding: 20px 10px 40px;
    }

    .participant-card-actions {
        width: 100%;
    }

}


/* =========================================================
   PRINT
   ========================================================= */

@media print {

    /*
     * Hilangkan semua elemen halaman yang tidak perlu.
     */
    html,
    body {

        margin: 0 !important;
        padding: 0 !important;

        width: 100% !important;

        background: #ffffff !important;

    }


    /*
     * Jangan cetak navbar/layout Laravel.
     */
    nav,
    header,
    footer {

        display: none !important;

    }


    /*
     * Tombol dan informasi tambahan tidak dicetak.
     */
    .no-print {

        display: none !important;

    }


    /*
     * Halaman print.
     */
    .participant-card-page {

        min-height: auto !important;

        width: 210mm !important;

        height: 297mm !important;

        margin: 0 !important;

        padding: 0 !important;

        display: flex !important;

        align-items: center !important;

        justify-content: center !important;

        background: #ffffff !important;

    }


    /*
     * Kartu dibuat 190 x 285 mm.
     *
     * Rasio:
     * 190 / 285 = 2 / 3
     *
     * sehingga sesuai dengan desain 1024 x 1536.
     *
     * Masih muat di kertas A4:
     *
     * A4 = 210 x 297 mm
     */
    .participant-card {

        width: 190mm !important;

        height: 285mm !important;

        max-width: none !important;

        max-height: none !important;

        min-width: 0 !important;

        min-height: 0 !important;

        aspect-ratio: auto !important;

        margin: 0 !important;

        padding: 0 !important;

        overflow: hidden !important;

        page-break-after: avoid !important;

        break-after: avoid-page !important;

        background: #ffffff !important;

        -webkit-print-color-adjust: exact !important;

        print-color-adjust: exact !important;

    }


    /*
     * Pastikan gambar benar-benar dicetak.
     */
    .participant-card-background {

        display: block !important;

        position: absolute !important;

        left: 0 !important;

        top: 0 !important;

        width: 100% !important;

        height: 100% !important;

        object-fit: cover !important;

        z-index: 1 !important;

        visibility: visible !important;

        opacity: 1 !important;

        -webkit-print-color-adjust: exact !important;

        print-color-adjust: exact !important;

    }


    /*
     * Kode lomba ketika dicetak.
     */
    .participant-card-code {

        left: 13% !important;

        top: 16% !important;

        width: 74% !important;

        height: 15.5% !important;

        padding: 10px !important;

        font-size: 38px !important;

        letter-spacing: 1.5px !important;

        line-height: 1 !important;

        color: #173c72 !important;

        z-index: 2 !important;

        -webkit-print-color-adjust: exact !important;

        print-color-adjust: exact !important;

    }


    /*
     * Nama sekolah ketika dicetak.
     */
    .participant-card-school {

        left: 13% !important;

        top: 34.5% !important;

        width: 74% !important;

        height: 18% !important;

        padding: 15px !important;

        font-size: 28px !important;

        line-height: 1.15 !important;

        color: #173c72 !important;

        z-index: 2 !important;

        -webkit-print-color-adjust: exact !important;

        print-color-adjust: exact !important;

    }


    /*
     * Pastikan hanya 1 halaman.
     */
    @page {

        size: A4 portrait;

        margin: 0;

    }

}

</style>

@endpush

@endsection