@extends('layouts.app')

@section('title', 'Kartu Peserta')

@section('content')

<div class="container py-5">


    {{-- =========================================================
         HEADER
         ========================================================= --}}

    <div class="text-center mb-5">

        <span class="badge badge-primary mb-2">

            <i class="fas fa-id-card mr-1"></i>

            Kartu Peserta

        </span>


        <h1 class="font-weight-bold mb-2">

            Kartu Peserta Sail & Hunt

        </h1>


        <p class="text-muted mb-4">

            Kartu peserta dapat diunduh satu per satu sebagai PNG
            atau seluruhnya dalam satu dokumen PDF.

        </p>


        {{-- =====================================================
             DOWNLOAD SEMUA KARTU
             ===================================================== --}}

        @if($registrations->isNotEmpty())

            <a href="{{ route('participant-cards.pdf') }}"
               class="btn btn-primary">

                <i class="fas fa-file-pdf mr-1"></i>

                Download Semua Kartu PDF

            </a>

        @endif

    </div>



    {{-- =========================================================
         TIDAK ADA PENDAFTARAN
         ========================================================= --}}

    @if($registrations->isEmpty())

        <div class="row justify-content-center">

            <div class="col-md-8">

                <div class="card border-0 shadow-sm">

                    <div class="card-body text-center py-5">

                        <div class="mb-3"
                             style="
                                font-size: 3rem;
                                color: #94a3b8;
                             ">

                            <i class="fas fa-id-card"></i>

                        </div>


                        <h4 class="font-weight-bold">

                            Belum Ada Kartu Peserta

                        </h4>


                        <p class="text-muted mb-4">

                            Anda belum memiliki pendaftaran lomba
                            yang dapat dibuat menjadi kartu peserta.

                        </p>


                        <a href="{{ route('competitions.index') }}"
                           class="btn btn-primary">

                            <i class="fas fa-trophy mr-1"></i>

                            Daftar Lomba

                        </a>

                    </div>

                </div>

            </div>

        </div>


    {{-- =========================================================
         ADA PENDAFTARAN
         ========================================================= --}}

    @else

        <div class="row">


            @foreach($registrations as $registration)

                <div class="col-md-6 col-lg-4 mb-4">


                    <div class="card h-100 border-0 shadow-sm">


                        {{-- =================================================
                             PREVIEW KARTU
                             ================================================= --}}

                        <div class="card-body d-flex flex-column">


                            <div class="text-center mb-4">


                                <div
                                    style="
                                        display: inline-flex;
                                        align-items: center;
                                        justify-content: center;
                                        width: 68px;
                                        height: 68px;
                                        border-radius: 16px;
                                        background: #eff6ff;
                                        color: #2563eb;
                                        font-size: 1.6rem;
                                        margin-bottom: 15px;
                                    "
                                >

                                    <i class="fas fa-id-card"></i>

                                </div>


                                <div
                                    class="font-weight-bold text-primary"
                                    style="
                                        font-size: 1.5rem;
                                        letter-spacing: 1px;
                                    "
                                >

                                    {{ $registration->registration_code }}

                                </div>


                                <div class="text-muted small mt-1">

                                    {{ $registration->competition->name }}

                                </div>

                            </div>



                            {{-- =================================================
                                 INFO SEKOLAH
                                 ================================================= --}}

                            <div class="mb-3">

                                <div class="text-muted small mb-1">

                                    Nama Sekolah

                                </div>


                                <div class="font-weight-bold">

                                    {{ $registration->unit->school_name }}

                                </div>

                            </div>



                            {{-- =================================================
                                 KATEGORI
                                 ================================================= --}}

                            <div class="mb-3">

                                <div class="text-muted small mb-1">

                                    Kategori

                                </div>


                                <span
                                    class="badge badge-secondary"
                                    style="
                                        font-size: 0.75rem;
                                    "
                                >

                                    {{ $registration->unit->level }}

                                </span>

                            </div>



                            {{-- =================================================
                                 JUMLAH PESERTA
                                 ================================================= --}}

                            <div class="mb-4">

                                <div class="text-muted small mb-1">

                                    Peserta

                                </div>


                                <div class="font-weight-bold">

                                    <i class="fas fa-users mr-1"></i>

                                    {{ $registration->participants->count() }}
                                    peserta

                                </div>

                            </div>



                            {{-- =================================================
                                 BUTTON
                                 ================================================= --}}

                            <div class="mt-auto">


                                <div class="row">


                                    {{-- LIHAT --}}

                                    <div class="col-6">

                                        <a
                                            href="{{ route(
                                                'participant-cards.show',
                                                $registration->registration_code
                                            ) }}"
                                            class="btn btn-primary btn-block"
                                        >

                                            <i class="fas fa-eye mr-1"></i>

                                            Lihat

                                        </a>

                                    </div>


                                    {{-- PNG --}}

                                    <div class="col-6">

                                        <a
                                            href="{{ route(
                                                'participant-cards.png',
                                                $registration->registration_code
                                            ) }}"
                                            class="btn btn-outline-primary btn-block"
                                        >

                                            <i class="fas fa-image mr-1"></i>

                                            PNG

                                        </a>

                                    </div>

                                </div>

                            </div>


                        </div>

                    </div>

                </div>

            @endforeach


        </div>

    @endif

</div>

@endsection