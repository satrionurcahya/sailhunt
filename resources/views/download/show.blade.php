@extends('layouts.app')

@section('title', $doc['title'])

@section('content')

<section style="padding: 40px 0 60px;">
    <div class="container" style="max-width: 900px;">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" style="margin-bottom: 24px;">
            <ol class="breadcrumb" style="background: transparent; padding: 0; margin: 0;">
                <li class="breadcrumb-item">
                    <a href="{{ route('download.index') }}" style="color: #0D4A85; font-weight: 600;">
                        <i class="fas fa-home"></i> Dokumen
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #475569; font-weight: 600;">
                    {{ $doc['title'] }}
                </li>
            </ol>
        </nav>

        {{-- Kartu Dokumen --}}
        <div class="card" style="border-radius: 18px; border: 1px solid #e2e8f0; box-shadow: 0 8px 30px rgba(0,0,0,0.06); overflow: hidden;">

            {{-- Header --}}
            <div class="card-header" style="background: linear-gradient(135deg, #0D4A85, #1872B5); padding: 24px 28px; border-bottom: none;">
                <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 12px; width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; color: #FFCC80; font-size: 1.8rem;">
                        <i class="fas {{ $doc['icon'] }}"></i>
                    </div>
                    <div>
                        <h1 style="color: #fff; font-size: 1.5rem; font-weight: 800; margin: 0; letter-spacing: -0.02em;">
                            {{ $doc['title'] }}
                        </h1>
                        <span style="color: rgba(255,255,255,0.7); font-size: 0.85rem;">
                            <i class="fas fa-folder"></i> {{ $doc['category'] }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="card-body" style="padding: 30px 28px;">

                {{-- Deskripsi --}}
                <div style="margin-bottom: 24px;">
                    <h4 style="font-size: 0.95rem; font-weight: 700; color: #0D4A85; margin-bottom: 8px;">
                        <i class="fas fa-info-circle"></i> Tentang Dokumen Ini
                    </h4>
                    <p style="color: #475569; font-size: 0.95rem; line-height: 1.8; margin: 0;">
                        {{ $doc['description'] }}
                    </p>
                </div>

                {{-- Informasi File --}}
                <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 24px; padding: 16px 20px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                    <div>
                        <span style="display: block; font-size: 0.7rem; text-transform: uppercase; color: #94a3b8; font-weight: 700; letter-spacing: 0.05em;">Nama File</span>
                        <span style="font-weight: 600; color: #0D4A85;">{{ $doc['file_name'] }}</span>
                    </div>
                    <div>
                        <span style="display: block; font-size: 0.7rem; text-transform: uppercase; color: #94a3b8; font-weight: 700; letter-spacing: 0.05em;">Kategori</span>
                        <span style="font-weight: 600;">{{ $doc['category'] }}</span>
                    </div>
                    <div>
                        <span style="display: block; font-size: 0.7rem; text-transform: uppercase; color: #94a3b8; font-weight: 700; letter-spacing: 0.05em;">Format</span>
                        <span style="font-weight: 600;">PDF</span>
                    </div>
                </div>

                {{-- Preview PDF (Embed Google Drive) --}}
                <div style="margin-bottom: 24px;">
                    <h4 style="font-size: 0.95rem; font-weight: 700; color: #0D4A85; margin-bottom: 10px;">
                        <i class="fas fa-eye"></i> Preview Dokumen
                    </h4>
                    <div style="background: #f1f5f9; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;">
                        <iframe src="{{ $doc['view_url'] }}" style="width: 100%; min-height: 500px; border: none;" allow="autoplay"></iframe>
                    </div>
                    <p style="font-size: 0.75rem; color: #94a3b8; margin-top: 8px;">
                        <i class="fas fa-info-circle"></i> Jika preview tidak muncul, silakan klik tombol download di bawah.
                    </p>
                </div>

                {{-- Tombol Aksi --}}
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <a href="{{ $doc['download_url'] }}" target="_blank" class="btn btn-primary" style="padding: 12px 28px; border-radius: 12px; font-weight: 700; background: #0D4A85; border-color: #0D4A85;">
                        <i class="fas fa-download"></i> Download Dokumen
                    </a>
                    <a href="{{ route('download.index') }}" class="btn btn-outline-secondary" style="padding: 12px 24px; border-radius: 12px; font-weight: 700;">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                    </a>
                </div>

            </div>
        </div>

    </div>
</section>

@endsection