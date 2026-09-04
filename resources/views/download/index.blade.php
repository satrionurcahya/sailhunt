@extends('layouts.app')

@section('title', 'Daftar Dokumen')

@section('content')

<section style="padding: 40px 0 60px;">
    <div class="container">

        <div class="section-header">
            <span>DOWNLOAD</span>
            <h2>Daftar Dokumen</h2>
            <p>Pilih dokumen yang ingin Anda lihat dan unduh.</p>
        </div>

        <div class="row">

            {{-- =====================================================
                 DAFTAR DOKUMEN
                 ===================================================== --}}
            @foreach($documents as $slug => $doc)
            <div class="col-md-6 col-lg-4 mb-4">
                <div
                    class="card h-100 shadow-sm"
                    style="
                        border-radius: 16px;
                        overflow: hidden;
                        transition: transform 0.2s, box-shadow 0.2s;
                        border: 1px solid #e2e8f0;
                    "
                >
                    <div
                        class="card-body text-center"
                        style="padding: 30px 20px;"
                    >
                        <div
                            style="
                                font-size: 3rem;
                                color: #0D4A85;
                                margin-bottom: 15px;
                            "
                        >
                            <i class="fas {{ $doc['icon'] }}"></i>
                        </div>

                        <h5
                            class="card-title"
                            style="
                                font-weight: 700;
                                color: #0f172a;
                            "
                        >
                            {{ $doc['title'] }}
                        </h5>

                        <span
                            class="badge"
                            style="
                                background: #0D4A85;
                                color: #FFCC80;
                                padding: 4px 14px;
                                border-radius: 20px;
                                font-size: 0.7rem;
                                font-weight: 700;
                            "
                        >
                            {{ $doc['category'] }}
                        </span>

                        <p
                            class="card-text mt-3"
                            style="
                                color: #64748b;
                                font-size: 0.9rem;
                                line-height: 1.6;
                            "
                        >
                            {{ Str::limit($doc['description'], 100) }}
                        </p>
                    </div>

                    <div
                        class="card-footer"
                        style="
                            background: #f8fafc;
                            border-top: 1px solid #e2e8f0;
                            padding: 14px 20px;
                            text-align: center;
                        "
                    >
                        <a
                            href="{{ route('download.show', $slug) }}"
                            class="btn btn-primary btn-sm"
                            style="
                                border-radius: 10px;
                                font-weight: 700;
                                padding: 8px 20px;
                                background: #0D4A85;
                                border-color: #0D4A85;
                            "
                        >
                            <i class="fas fa-eye"></i>
                            Lihat & Download
                        </a>
                    </div>
                </div>
            </div>
            @endforeach


            {{-- =====================================================
                 SUMBER MATERI
                 ===================================================== --}}
            <div class="col-12 mt-2">
                <div
                    class="card shadow-sm"
                    style="
                        border-radius: 16px;
                        overflow: hidden;
                        border: 1px solid #e2e8f0;
                        background: linear-gradient(135deg, #f8fafc, #eef6ff);
                    "
                >
                    <div
                        class="card-body text-center"
                        style="padding: 30px 20px;"
                    >

                        <div
                            style="
                                font-size: 3rem;
                                color: #0D4A85;
                                margin-bottom: 15px;
                            "
                        >
                            <i class="fas fa-folder-open"></i>
                        </div>

                        <h5
                            class="card-title"
                            style="
                                font-weight: 700;
                                color: #0f172a;
                            "
                        >
                            Sumber Materi
                        </h5>

                        <span
                            class="badge"
                            style="
                                background: #0D4A85;
                                color: #FFCC80;
                                padding: 4px 14px;
                                border-radius: 20px;
                                font-size: 0.7rem;
                                font-weight: 700;
                            "
                        >
                            MATERI LOMBA
                        </span>

                        <p
                            class="card-text mt-3"
                            style="
                                color: #64748b;
                                font-size: 0.9rem;
                                line-height: 1.6;
                                max-width: 650px;
                                margin-left: auto;
                                margin-right: auto;
                            "
                        >
                            Akses kumpulan materi dan bahan persiapan
                            Sail & Hunt Chapter I melalui folder Google Drive.
                        </p>

                        <a
                            href="{{ $materialsUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn btn-primary"
                            style="
                                border-radius: 10px;
                                font-weight: 700;
                                padding: 10px 24px;
                                background: #0D4A85;
                                border-color: #0D4A85;
                            "
                        >
                            <i class="fas fa-folder-open"></i>
                            Buka Sumber Materi
                        </a>

                    </div>
                </div>
            </div>

        </div>


        {{-- =====================================================
             KEMBALI KE DASHBOARD
             ===================================================== --}}
        <div
            style="
                text-align: center;
                margin-top: 30px;
            "
        >
            <a
                href="{{ route('dashboard') }}"
                class="btn btn-outline-secondary"
                style="
                    border-radius: 10px;
                    font-weight: 700;
                "
            >
                <i class="fas fa-arrow-left"></i>
                Kembali ke Dashboard
            </a>
        </div>

    </div>
</section>

@endsection