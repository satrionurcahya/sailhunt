<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\LogsActivity;

class AdminVerificationController extends Controller
{
    use LogsActivity;

    // ============================================================
    // DAFTAR DOKUMEN DAFTAR ULANG
    // ============================================================

    public function daftarUlang(Request $request)
    {
        $query = Upload::query()
            ->where('type', 'daftar_ulang')
            ->with('unit');

        // ============================================================
        // SEARCH BERDASARKAN NAMA UNIT
        // ============================================================

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->whereHas('unit', function ($q) use ($search) {

                $q->where(
                    'school_name',
                    'LIKE',
                    '%' . $search . '%'
                );

            });
        }

        // ============================================================
        // FILTER STATUS
        // ============================================================

        if (
            $request->filled('status')
            && $request->status !== 'all'
        ) {

            $query->where(
                'status',
                $request->status
            );
        }

        // ============================================================
        // PAGINATION
        // ============================================================

        $uploads = $query
            ->latest('id')
            ->paginate(20);

        $uploads->appends(
            $request->all()
        );

        return view(
            'admin.daftar-ulang',
            compact('uploads')
        );
    }


    // ============================================================
    // VERIFIKASI DAFTAR ULANG
    // ============================================================

    public function verifyDaftarUlang(
        Request $request,
        Upload $upload
    ) {

        // ============================================================
        // VALIDASI INPUT ADMIN
        // ============================================================

        $request->validate([
            'status' => 'required|in:verified,rejected',
        ]);


        // ============================================================
        // PASTIKAN UPLOAD MEMANG DAFTAR ULANG
        // ============================================================

        if ($upload->type !== 'daftar_ulang') {

            return back()->with(
                'error',
                'Dokumen yang dipilih bukan dokumen daftar ulang.'
            );
        }


        // ============================================================
        // STATUS SAAT INI
        // ============================================================

        $oldStatus = $upload->status;


        // ============================================================
        // JIKA SUDAH VERIFIED, KUNCI PERMANEN
        // ============================================================

        if ($oldStatus === 'verified') {

            return back()->with(
                'error',
                'Dokumen daftar ulang yang sudah diverifikasi tidak dapat diubah kembali.'
            );
        }


        // ============================================================
        // CEGAH PERUBAHAN KE STATUS YANG SAMA
        // ============================================================

        if ($oldStatus === $request->status) {

            return back()->with(
                'error',
                'Dokumen sudah memiliki status tersebut.'
            );
        }


        // ============================================================
        // UPDATE SECARA ATOMIK
        // ============================================================

        DB::transaction(function () use (
            $upload,
            $request
        ) {

            $upload->update([
                'status' => $request->status,
            ]);
        });


        // ============================================================
        // LOG AKTIVITAS ADMIN
        // ============================================================

        $this->logAdminActivity(
            'daftar_ulang_verify',
            'admin',
            'Verifikasi dokumen daftar ulang',
            [
                'upload_id' => $upload->id,
                'unit_id'   => $upload->unit_id,
                'old_status'=> $oldStatus,
                'new_status'=> $request->status,
            ]
        );


        // ============================================================
        // RESPONSE
        // ============================================================

        if ($request->status === 'verified') {

            return back()->with(
                'success',
                'Dokumen daftar ulang berhasil diverifikasi. Dokumen sekarang terkunci.'
            );
        }


        return back()->with(
            'success',
            'Dokumen daftar ulang ditolak. Unit dapat mengunggah dokumen pengganti.'
        );
    }
}