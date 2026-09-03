<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\LogsActivity;

class AdminPaymentController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        $query = Upload::query()
            ->where('type', 'pembayaran')
            ->with([
                'unit',
                'registration.competition',
            ]);

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->whereHas('unit', function ($sub) use ($search) {

                    $sub->where(
                        'school_name',
                        'LIKE',
                        '%' . $search . '%'
                    );

                })->orWhereHas(
                    'registration.competition',
                    function ($sub) use ($search) {

                        $sub->where(
                            'name',
                            'LIKE',
                            '%' . $search . '%'
                        );
                    }
                );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS UPLOAD
        |--------------------------------------------------------------------------
        */
        if (
            $request->filled('status')
            && $request->status !== 'all'
        ) {

            $query->where(
                'status',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER PAYMENT TYPE
        |--------------------------------------------------------------------------
        */
        if (
            $request->filled('payment_type')
            && $request->payment_type !== 'all'
        ) {

            $query->whereHas(
                'registration',
                function ($q) use ($request) {

                    $q->where(
                        'payment_type',
                        $request->payment_type
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | HILANGKAN DUPLIKASI BATCH
        |--------------------------------------------------------------------------
        |
        | Satu batch memiliki file_path yang sama.
        |
        | Tetapi kita tetap menggunakan upload terbaru agar
        | tampilan admin tidak terlalu penuh dengan baris identik.
        |
        */
        $uploads = $query
            ->latest('id')
            ->paginate(20);

        $uploads->appends(
            $request->all()
        );

        return view(
            'admin.payments.index',
            compact('uploads')
        );
    }


    public function verify(
        Request $request,
        Upload $upload
    ) {

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */
        $request->validate([
            'status' => 'required|in:verified,rejected',
        ]);


        /*
        |--------------------------------------------------------------------------
        | PASTIKAN INI UPLOAD PEMBAYARAN
        |--------------------------------------------------------------------------
        */
        if ($upload->type !== 'pembayaran') {

            return back()->with(
                'error',
                'File yang dipilih bukan bukti pembayaran.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PASTIKAN RELASI REGISTRATION ADA
        |--------------------------------------------------------------------------
        */
        $registration = $upload->registration;

        if (!$registration) {

            return back()->with(
                'error',
                'Registration pembayaran tidak ditemukan.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | IDENTITAS BATCH
        |--------------------------------------------------------------------------
        |
        | Semua upload yang dibuat pada satu proses pembayaran
        | mempunyai:
        |
        | unit_id yang sama
        | file_path yang sama
        | type = pembayaran
        |
        */
        $batchUploads = Upload::query()
            ->where('type', 'pembayaran')
            ->where('unit_id', $upload->unit_id)
            ->where('file_path', $upload->file_path)
            ->get();


        if ($batchUploads->isEmpty()) {

            return back()->with(
                'error',
                'Data batch pembayaran tidak ditemukan.'
            );
        }


        $oldStatus = $upload->status;


        /*
        |--------------------------------------------------------------------------
        | PROSES DATABASE SECARA ATOMIK
        |--------------------------------------------------------------------------
        */
        DB::transaction(function () use (
            $batchUploads,
            $request
        ) {

            /*
            |--------------------------------------------------------------------------
            | UPDATE SEMUA UPLOAD DALAM BATCH
            |--------------------------------------------------------------------------
            */
            Upload::whereIn(
                'id',
                $batchUploads->pluck('id')
            )->update([
                'status' => $request->status,
            ]);


            /*
            |--------------------------------------------------------------------------
            | AMBIL SEMUA REGISTRATION TERKAIT
            |--------------------------------------------------------------------------
            */
            $registrationIds = $batchUploads
                ->pluck('registration_id')
                ->filter()
                ->unique()
                ->values();


            if ($registrationIds->isEmpty()) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | UPDATE STATUS PEMBAYARAN
            |--------------------------------------------------------------------------
            */
            Registration::whereIn(
                'id',
                $registrationIds
            )->update([
                'payment_status' =>
                    $request->status === 'verified'
                        ? 'verified'
                        : 'pending',
            ]);
        });


        /*
        |--------------------------------------------------------------------------
        | LOG ADMIN
        |--------------------------------------------------------------------------
        */
        $this->logAdminActivity(
            'payment_verify',
            'admin',
            'Verifikasi pembayaran batch',
            [
                'upload_id'          => $upload->id,
                'unit_id'            => $upload->unit_id,
                'old_status'         => $oldStatus,
                'new_status'         => $request->status,
                'batch_file'         => $upload->file_path,
                'total_uploads'      => $batchUploads->count(),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */
        if ($request->status === 'verified') {

            return back()->with(
                'success',
                'Pembayaran batch berhasil diverifikasi untuk seluruh lomba terkait.'
            );
        }


        return back()->with(
            'success',
            'Pembayaran batch ditolak. Seluruh lomba terkait dapat melakukan upload pembayaran ulang.'
        );
    }
}