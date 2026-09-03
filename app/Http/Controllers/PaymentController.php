<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Upload;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminNewPaymentNotification;
use App\Traits\LogsActivity;

class PaymentController extends Controller
{
    use LogsActivity;

    public function storeBatch(Request $request)
    {
        $unitId = session('unit_id');

        if (!$unitId) {
            return redirect()
                ->route('login')
                ->with('error', 'Sesi peserta telah berakhir. Silakan login kembali.');
        }

        /*
        |--------------------------------------------------------------------------
        | HANYA AMBIL REGISTRATION YANG BENAR-BENAR BELUM DIBAYAR
        |--------------------------------------------------------------------------
        |
        | Jangan menggunakan:
        | payment_status != verified
        |
        | karena status "paid" berarti pembayaran sedang menunggu
        | verifikasi admin dan tidak boleh dibuatkan batch baru.
        |
        */
        $registrations = Registration::query()
            ->with('competition')
            ->where('unit_id', $unitId)
            ->where('payment_status', 'pending')
            ->orderBy('id')
            ->get();

        if ($registrations->isEmpty()) {
            $hasWaitingPayment = Registration::where('unit_id', $unitId)
                ->where('payment_status', 'paid')
                ->exists();

            if ($hasWaitingPayment) {
                return back()->with(
                    'error',
                    'Masih ada pembayaran yang menunggu verifikasi admin. Silakan tunggu proses verifikasi sebelum melakukan pembayaran baru.'
                );
            }

            return back()->with(
                'error',
                'Tidak ada lomba yang perlu dibayar.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */
        $request->validate([
            'payment_type' => 'required|in:dp,lunas',

            'file' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
            ],
        ]);

        $unit = Unit::findOrFail($unitId);

        /*
        |--------------------------------------------------------------------------
        | NAMA FILE BATCH
        |--------------------------------------------------------------------------
        |
        | Satu file = satu batch pembayaran.
        |
        */
        $extension = $request->file('file')
            ->getClientOriginalExtension();

        $fileName =
            $unit->school_name
            . '_pembayaran_'
            . now()->format('YmdHis')
            . '.'
            . $extension;

        /*
        |--------------------------------------------------------------------------
        | TOTAL PEMBAYARAN
        |--------------------------------------------------------------------------
        */
        $totalAmount = $registrations->sum(function ($registration) use ($request) {

            $fee = (float) $registration->competition->fee;

            return $request->payment_type === 'dp'
                ? $fee * 0.6
                : $fee;
        });


        /*
        |--------------------------------------------------------------------------
        | UPLOAD FILE
        |--------------------------------------------------------------------------
        */
        try {

            $fileContent = file_get_contents(
                $request->file('file')->getRealPath()
            );

            Storage::disk('google_pembayaran')->put(
                $fileName,
                $fileContent,
                'public'
            );

        } catch (\Throwable $e) {

            \Log::error(
                'Gagal upload bukti pembayaran: '
                . $e->getMessage(),
                [
                    'unit_id' => $unitId,
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Bukti pembayaran gagal diunggah. Silakan coba kembali.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | SIMPAN DATABASE SECARA ATOMIK
        |--------------------------------------------------------------------------
        */
        try {

            DB::transaction(function () use (
                $registrations,
                $unitId,
                $fileName,
                $request
            ) {

                foreach ($registrations as $registration) {

                    /*
                    |--------------------------------------------------------------------------
                    | HITUNG NOMINAL
                    |--------------------------------------------------------------------------
                    */
                    $fee = (float) $registration->competition->fee;

                    $amount = $request->payment_type === 'dp'
                        ? $fee * 0.6
                        : $fee;


                    /*
                    |--------------------------------------------------------------------------
                    | BUAT UPLOAD BUKTI PEMBAYARAN
                    |--------------------------------------------------------------------------
                    */
                    Upload::create([
                        'unit_id'         => $unitId,
                        'registration_id' => $registration->id,
                        'type'            => 'pembayaran',
                        'category'        => $registration->competition->name,
                        'file_path'       => $fileName,
                        'status'          => 'pending',
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE REGISTRATION
                    |--------------------------------------------------------------------------
                    */
                    $registration->update([
                        'payment_status' => 'paid',
                        'payment_type'   => $request->payment_type,
                        'amount_paid'    => $amount,
                    ]);
                }
            });

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | LOG ERROR
            |--------------------------------------------------------------------------
            */
            \Log::error(
                'Gagal menyimpan pembayaran batch: '
                . $e->getMessage(),
                [
                    'unit_id'   => $unitId,
                    'file_name' => $fileName,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | HAPUS FILE GOOGLE DRIVE JIKA DATABASE GAGAL
            |--------------------------------------------------------------------------
            */
            try {
                Storage::disk('google_pembayaran')
                    ->delete($fileName);
            } catch (\Throwable $deleteException) {
                \Log::error(
                    'Gagal menghapus file pembayaran setelah rollback: '
                    . $deleteException->getMessage()
                );
            }

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Data pembayaran gagal disimpan. Silakan coba kembali.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | LOG AKTIVITAS
        |--------------------------------------------------------------------------
        */
        $this->logUnitActivity(
            'payment_upload_batch',
            'payment',
            'Upload bukti pembayaran batch',
            [
                'total_registrations' => $registrations->count(),
                'payment_type'        => $request->payment_type,
                'file_name'           => $fileName,
                'total_amount'        => $totalAmount,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI ADMIN
        |--------------------------------------------------------------------------
        */
        try {

            $admins = Unit::where('is_admin', true)->get();

            /*
             * Kirim satu notifikasi saja per batch.
             *
             * Tidak perlu satu email untuk setiap registration.
             */
            $firstUpload = Upload::where('unit_id', $unitId)
                ->where('type', 'pembayaran')
                ->where('file_path', $fileName)
                ->latest('id')
                ->first();

            if ($firstUpload) {

                foreach ($admins as $admin) {

                    if (!empty($admin->email)) {

                        Mail::to($admin->email)
                            ->send(
                                new AdminNewPaymentNotification(
                                    $firstUpload
                                )
                            );
                    }
                }
            }

        } catch (\Throwable $e) {

            \Log::error(
                'Gagal mengirim notifikasi pembayaran batch ke admin: '
                . $e->getMessage()
            );
        }


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */
        return back()->with(
            'success',
            'Bukti pembayaran berhasil diunggah untuk seluruh lomba yang belum dibayar. Silakan menunggu verifikasi admin.'
        );
    }
}