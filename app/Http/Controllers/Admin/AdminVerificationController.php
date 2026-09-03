<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use Illuminate\Http\Request;
use App\Traits\LogsActivity;

class AdminVerificationController extends Controller
{
    use LogsActivity;

    public function daftarUlang(Request $request)
    {
        $query = Upload::where('type', 'daftar_ulang')
            ->with('unit');

        // ============================================================
        // SEARCH BERDASARKAN NAMA UNIT
        // ============================================================
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('unit', function ($q) use ($search) {
                $q->where('school_name', 'LIKE', '%' . $search . '%');
            });
        }

        // ============================================================
        // FILTER BERDASARKAN STATUS
        // ============================================================
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $uploads = $query->latest()->paginate(20);
        $uploads->appends($request->all());

        return view('admin.daftar-ulang', compact('uploads'));
    }

    public function verifyDaftarUlang(Request $request, Upload $upload)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected',
        ]);

        $oldStatus = $upload->status;

        $upload->update(['status' => $request->status]);

        // ============================================================
        // LOG AKTIVITAS VERIFIKASI DAFTAR ULANG
        // ============================================================
        $this->logAdminActivity('daftar_ulang_verify', 'admin', 'Verifikasi dokumen daftar ulang', [
            'upload_id' => $upload->id,
            'unit_id' => $upload->unit_id,
            'old_status' => $oldStatus,
            'new_status' => $request->status,
        ]);

        return back()->with('success', 'Status dokumen berhasil diperbarui.');
    }
}