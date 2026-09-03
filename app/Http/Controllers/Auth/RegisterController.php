<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AdminNewRegistrationNotification;
use App\Models\Unit;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    use LogsActivity;

    /**
     * Menampilkan halaman registrasi.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi unit.
     */
    public function register(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'level' => [
                'required',
                'in:Madya,Wira',
            ],

            'school_name' => [
                'required',
                'string',
                'max:255',
                'unique:units,school_name',
            ],

            'address' => [
                'required',
                'string',
                'max:500',
            ],

            'city' => [
                'required',
                'string',
                'max:100',
            ],

            'postal_code' => [
                'required',
                'digits:5',
            ],

            'coach_name' => [
                'required',
                'string',
                'max:255',
            ],

            'trainer_name' => [
                'required',
                'string',
                'max:255',
            ],

            'commander_name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'unique:units,email',
            ],

            'username' => [
                'required',
                'string',
                'min:4',
                'unique:units,username',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'agreement' => [
                'required',
                'accepted',
            ],
        ], [
            'level.required' =>
                'Silakan pilih tingkat PMR.',

            'level.in' =>
                'Tingkat PMR harus Madya atau Wira.',

            'school_name.required' =>
                'Nama sekolah wajib diisi.',

            'school_name.unique' =>
                'Sekolah ini sudah memiliki akun/unit. Satu sekolah hanya diperbolehkan memiliki satu akun/unit.',

            'school_name.max' =>
                'Nama sekolah maksimal 255 karakter.',

            'address.required' =>
                'Alamat sekolah wajib diisi.',

            'city.required' =>
                'Kabupaten/Kota wajib dipilih.',

            'postal_code.required' =>
                'Kode pos wajib diisi.',

            'postal_code.digits' =>
                'Kode pos harus terdiri dari 5 digit.',

            'coach_name.required' =>
                'Nama pembina wajib diisi.',

            'trainer_name.required' =>
                'Nama pelatih wajib diisi.',

            'commander_name.required' =>
                'Nama komandan wajib diisi.',

            'email.required' =>
                'Email wajib diisi.',

            'email.email' =>
                'Format email tidak valid.',

            'email.unique' =>
                'Email tersebut sudah digunakan.',

            'username.required' =>
                'Username wajib diisi.',

            'username.min' =>
                'Username minimal 4 karakter.',

            'username.unique' =>
                'Username tersebut sudah digunakan.',

            'password.required' =>
                'Password wajib diisi.',

            'password.min' =>
                'Password minimal 8 karakter.',

            'password.confirmed' =>
                'Konfirmasi password tidak cocok.',

            'agreement.required' =>
                'Anda harus menyetujui syarat dan ketentuan.',

            'agreement.accepted' =>
                'Anda harus menyetujui syarat dan ketentuan.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | NORMALISASI NAMA SEKOLAH
        |--------------------------------------------------------------------------
        */

        $validated['school_name'] = strtoupper(
            trim($validated['school_name'])
        );

        /*
        |--------------------------------------------------------------------------
        | CEK DUPLIKASI SEKOLAH
        |--------------------------------------------------------------------------
        |
        | Sebenarnya rule unique di atas sudah melakukan pengecekan.
        | Pengecekan kedua ini tetap dipertahankan sebagai lapisan tambahan.
        |
        */

        if (
            Unit::where(
                'school_name',
                $validated['school_name']
            )->exists()
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'school_name' =>
                        'Sekolah ini sudah memiliki akun/unit. Satu sekolah hanya diperbolehkan memiliki satu akun/unit.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | BUAT UNIT
        |--------------------------------------------------------------------------
        */

        $unit = Unit::create($validated);

        /*
        |--------------------------------------------------------------------------
        | LOG REGISTRASI
        |--------------------------------------------------------------------------
        */

        $this->logActivity(
            'register',
            'auth',
            'Unit baru mendaftar',
            [
                'unit_id' => $unit->id,
                'school_name' => $unit->school_name,
                'email' => $unit->email,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | EMAIL VERIFIKASI
        |--------------------------------------------------------------------------
        |
        | Jangan gunakan send().
        |
        | Method di Unit sekarang harus menggunakan queue().
        |
        */

        try {
            $unit->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            Log::error(
                'Gagal memasukkan email verifikasi ke queue.',
                [
                    'unit_id' => $unit->id,
                    'email' => $unit->email,
                    'error' => $e->getMessage(),
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | KONFIRMASI PENDAFTARAN
        |--------------------------------------------------------------------------
        */

        try {
            Mail::to($unit->email)->queue(
                new \App\Mail\RegistrationConfirmation($unit)
            );
        } catch (\Throwable $e) {
            Log::error(
                'Gagal memasukkan email konfirmasi ke queue.',
                [
                    'unit_id' => $unit->id,
                    'email' => $unit->email,
                    'error' => $e->getMessage(),
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI ADMIN
        |--------------------------------------------------------------------------
        |
        | Semua admin mendapatkan notifikasi.
        |
        | Menggunakan queue, bukan send().
        |
        */

        try {
            $admins = Unit::where(
                'is_admin',
                true
            )->get();

            foreach ($admins as $admin) {
                /*
                | Jangan kirim notifikasi admin ke email
                | yang sama dengan unit pendaftar jika suatu saat
                | akun admin juga memiliki data pendaftaran.
                */
                if (
                    !empty($admin->email) &&
                    strcasecmp(
                        trim($admin->email),
                        trim($unit->email)
                    ) !== 0
                ) {
                    Mail::to($admin->email)->queue(
                        new AdminNewRegistrationNotification($unit)
                    );
                }
            }
        } catch (\Throwable $e) {
            Log::error(
                'Gagal memasukkan notifikasi registrasi admin ke queue.',
                [
                    'unit_id' => $unit->id,
                    'error' => $e->getMessage(),
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Pendaftaran berhasil! Silakan cek email ' .
                $unit->email .
                ' untuk verifikasi. Link verifikasi berlaku 60 menit.'
            );
    }

    /**
     * VERIFIKASI EMAIL
     */
    public function verifyEmail($id, $hash)
    {
        /*
        |--------------------------------------------------------------------------
        | CARI UNIT
        |--------------------------------------------------------------------------
        */

        $unit = Unit::find($id);

        if (!$unit) {
            abort(404, 'Unit tidak ditemukan.');
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI HASH
        |--------------------------------------------------------------------------
        */

        if (!hash_equals(
            sha1($unit->email),
            $hash
        )) {
            abort(403, 'Link verifikasi tidak valid.');
        }

        /*
        |--------------------------------------------------------------------------
        | CEK STATUS VERIFIKASI
        |--------------------------------------------------------------------------
        */

        if ($unit->email_verified_at !== null) {
            return redirect()
                ->route('login')
                ->with(
                    'info',
                    'Email sudah diverifikasi. Silakan login.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | VERIFIKASI
        |--------------------------------------------------------------------------
        */

        $unit->email_verified_at = now();
        $unit->save();

        /*
        |--------------------------------------------------------------------------
        | LOG
        |--------------------------------------------------------------------------
        */

        $this->logActivity(
            'email_verified',
            'auth',
            'Email berhasil diverifikasi',
            [
                'unit_id' => $unit->id,
                'email' => $unit->email,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('login')
            ->with(
                'success',
                '✅ Email berhasil diverifikasi! Silakan login untuk melanjutkan.'
            );
    }

    /**
     * KIRIM ULANG EMAIL VERIFIKASI
     */
    public function resendVerification(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'email' => [
                'required',
                'email',
                'exists:units,email',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | CARI UNIT
        |--------------------------------------------------------------------------
        */

        $unit = Unit::where(
            'email',
            $request->email
        )->first();

        if (!$unit) {
            return back()->withErrors([
                'email' =>
                    'Data unit tidak ditemukan.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CEK SUDAH VERIFIKASI
        |--------------------------------------------------------------------------
        */

        if ($unit->email_verified_at !== null) {
            return back()->with(
                'info',
                'Email sudah diverifikasi. Silakan login.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | QUEUE EMAIL VERIFIKASI
        |--------------------------------------------------------------------------
        */

        try {
            $unit->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            Log::error(
                'Gagal memasukkan resend email verifikasi ke queue.',
                [
                    'unit_id' => $unit->id,
                    'email' => $unit->email,
                    'error' => $e->getMessage(),
                ]
            );

            return back()->with(
                'error',
                'Gagal mengirim ulang email verifikasi. Silakan coba lagi.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | LOG
        |--------------------------------------------------------------------------
        */

        $this->logActivity(
            'resend_verification',
            'auth',
            'Kirim ulang link verifikasi',
            [
                'unit_id' => $unit->id,
                'email' => $unit->email,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'success',
            'Link verifikasi baru telah dimasukkan ke antrean pengiriman ke ' .
            $unit->email .
            '.'
        );
    }
}