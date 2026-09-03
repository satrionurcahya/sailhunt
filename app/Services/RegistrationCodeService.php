<?php

namespace App\Services;

use App\Models\Competition;
use App\Models\Registration;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class RegistrationCodeService
{
    /**
     * Membuat registration baru beserta registration_code.
     *
     * Contoh:
     * MPS-001
     * MPS-002
     * MPPU-001
     * WPS-001
     */
    public function create(array $attributes): Registration
    {
        return DB::transaction(function () use ($attributes) {

            /*
             * Lock data competition selama proses generate kode.
             *
             * Tujuannya agar dua pendaftar yang masuk bersamaan
             * tidak mendapatkan nomor urut yang sama.
             */
            $competition = Competition::query()
                ->lockForUpdate()
                ->findOrFail($attributes['competition_id']);

            /*
             * Ambil unit peserta.
             */
            $unit = Unit::findOrFail($attributes['unit_id']);

            /*
             * Level harus berupa Wira / Madya
             * karena digunakan sebagai key pada config.
             */
            $level = ucfirst(strtolower(trim($unit->level)));

            if (!in_array($level, ['Wira', 'Madya'], true)) {
                throw new RuntimeException(
                    "Level unit tidak valid: {$unit->level}"
                );
            }

            /*
             * Ambil prefix berdasarkan:
             *
             * Wira / Madya
             * +
             * Nama competition
             */
            $prefix = config(
                "competition_codes.{$level}.{$competition->name}"
            );

            if (!$prefix) {
                throw new RuntimeException(
                    "Kode lomba belum dikonfigurasi untuk "
                    . "{$competition->name} ({$level})."
                );
            }

            /*
             * Cari nomor terbesar yang sudah pernah digunakan
             * untuk prefix lomba + level tersebut.
             *
             * Contoh:
             *
             * MPS-001
             * MPS-002
             * MPS-017
             *
             * Maka nomor berikutnya:
             *
             * MPS-018
             */
            $lastSequence = Registration::query()
                ->where('competition_id', $competition->id)
                ->whereHas('unit', function ($query) use ($level) {
                    $query->where('level', $level);
                })
                ->where('registration_code', 'like', $prefix . '-%')
                ->selectRaw(
                    "MAX(
                        CAST(
                            SUBSTRING_INDEX(
                                registration_code,
                                '-',
                                -1
                            ) AS UNSIGNED
                        )
                    ) AS max_sequence"
                )
                ->value('max_sequence');

            /*
             * Jika belum ada pendaftar:
             * 0 + 1 = 1
             */
            $sequence = ((int) $lastSequence) + 1;

            /*
             * Format tiga digit:
             *
             * 1   -> 001
             * 9   -> 009
             * 10  -> 010
             * 100 -> 100
             */
            $registrationCode = sprintf(
                '%s-%03d',
                $prefix,
                $sequence
            );

            /*
             * Pastikan kode belum ada.
             *
             * Secara normal lockForUpdate() di atas sudah mencegah
             * benturan untuk competition yang sama.
             *
             * Pemeriksaan ini menjadi lapisan tambahan.
             */
            if (
                Registration::where(
                    'registration_code',
                    $registrationCode
                )->exists()
            ) {
                throw new RuntimeException(
                    "Registration code {$registrationCode} sudah digunakan."
                );
            }

            /*
             * Buat registration.
             */
            return Registration::create([
                'unit_id'           => $attributes['unit_id'],
                'competition_id'    => $attributes['competition_id'],
                'status'            => $attributes['status'] ?? 'pending',
                'payment_status'    => $attributes['payment_status'] ?? 'pending',
                'payment_type'      => $attributes['payment_type'] ?? null,
                'amount_paid'       => $attributes['amount_paid'] ?? null,
                'registration_code' => $registrationCode,
            ]);
        });
    }
}