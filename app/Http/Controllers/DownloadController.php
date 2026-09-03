<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DownloadController extends Controller
{
    /**
     * Daftar dokumen yang tersedia untuk diunduh.
     */
    private $documents = [
        'juklak-juknis' => [
            'title'       => 'Juklak & Juknis',
            'category'    => 'Dokumen Publik',
            'description' => 'Petunjuk Pelaksanaan dan Petunjuk Teknis Sail & Hunt Chapter I.',
            'file_name'   => 'juklak-juknis.pdf',
            'drive_id'    => '1jBa4JUwByyvwTaAfZauv49VseKVOGxC-',
            'icon'        => 'fa-book',
        ],
        'surat-rekomendasi' => [
            'title'       => 'Surat Rekomendasi',
            'category'    => 'Dokumen Peserta',
            'description' => 'Surat rekomendasi dari kepala sekolah untuk mengikuti Sail & Hunt Chapter I.',
            'file_name'   => 'surat-rekomendasi.pdf',
            'drive_id'    => '1aV4fsguhX9UnYoWPNG_EkfJg44sV8o_b',
            'icon'        => 'fa-file-pdf',
        ],
        'kartu-pp' => [
            'title'       => 'Kartu PP (Pertolongan Pertama)',
            'category'    => 'Dokumen Peserta',
            'description' => 'Kartu peserta untuk lomba Pertolongan Pertama. Wajib dicetak dan dibawa saat lomba.',
            'file_name'   => 'kartu-pp.pdf',
            'drive_id'    => '1Yk33NUBRP3BES2p9aJ73iK7Zlux8jsvL', // Ganti dengan ID file Google Drive
            'icon'        => 'fa-id-card',
        ],
        'kartu-pk' => [
            'title'       => 'Kartu PK (Perawatan Keluarga)',
            'category'    => 'Dokumen Peserta',
            'description' => 'Kartu peserta untuk lomba Perawatan Keluarga. Wajib dicetak dan dibawa saat lomba.',
            'file_name'   => 'kartu-pk.pdf',
            'drive_id'    => '1CnCAkZo5W0E4MEb98bXDAfPZSGPB7kTy', // Ganti dengan ID file Google Drive
            'icon'        => 'fa-id-card',
        ],
        'surat-undangan' => [
            'title'       => 'Surat Undangan',
            'category'    => 'Dokumen Peserta',
            'description' => 'Surat undangan resmi untuk mengikuti Sail & Hunt Chapter I.',
            'file_name'   => 'surat-undangan.pdf',
            'drive_id'    => '1ycXim-gUTvdF_7GzWMJaivYkKcsPf_-T', // Ganti dengan ID file Google Drive
            'icon'        => 'fa-envelope',
        ],
    ];

    /**
     * Menampilkan halaman daftar semua dokumen.
     */
    public function index()
    {
        $documents = Cache::remember('download_documents', 3600, function () {
            return $this->documents;
        });

        return view('download.index', compact('documents'));
    }

    /**
     * Menampilkan halaman detail dan preview dokumen.
     */
    public function show($slug)
    {
        $documents = Cache::remember('download_documents', 3600, function () {
            return $this->documents;
        });

        if (!isset($documents[$slug])) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        $doc = $documents[$slug];
        $doc['slug'] = $slug;
        $doc['download_url'] = 'https://drive.google.com/uc?export=download&id=' . $doc['drive_id'];
        $doc['view_url'] = 'https://drive.google.com/file/d/' . $doc['drive_id'] . '/preview';

        return view('download.show', compact('doc'));
    }

    /**
     * Membersihkan cache dokumen (digunakan setelah update dokumen).
     */
    public function clearCache()
    {
        Cache::forget('download_documents');

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Cache dokumen berhasil dibersihkan.']);
        }

        return redirect()->back()->with('success', 'Cache dokumen berhasil dibersihkan.');
    }
}