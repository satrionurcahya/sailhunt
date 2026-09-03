<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\RegistrationCodeService;

class Unit extends Model
{
    protected $table = 'units';

    protected $fillable = [
        'level',
        'school_name',
        'address',
        'city',
        'postal_code',
        'coach_name',
        'trainer_name',
        'commander_name',
        'email',
        'username',
        'password',
        'status',
        'is_admin',
    ];

    protected $hidden = [
        'password',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function registrations()
    {
        return $this->hasMany(
            Registration::class
        );
    }

    public function uploads()
    {
        return $this->hasMany(
            Upload::class
        );
    }

    public function scores()
    {
        return $this->hasManyThrough(
            Score::class,
            Registration::class,
            'unit_id',
            'registration_id',
            'id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AUTOMATIC GPS REGISTRATION
    |--------------------------------------------------------------------------
    */

    public function autoRegisterGPS()
    {
        $gps = Competition::where(
            'name',
            'Gerakan Pungut Sampah (GPS)'
        )->first();

        if (
            $gps &&
            !$this->registrations()
                ->where(
                    'competition_id',
                    $gps->id
                )
                ->exists()
        ) {
            app(RegistrationCodeService::class)->create([
                'unit_id' => $this->id,

                'competition_id' => $gps->id,

                'status' => 'confirmed',

                'payment_status' => 'verified',
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | EVALUATION
    |--------------------------------------------------------------------------
    |
    | Score dan points digunakan sebagai data evaluasi/rekap nilai.
    | Total points BUKAN dasar penentuan Juara Umum.
    |
    */

    public function getTotalPoints(): int
    {
        return (int) $this->scores()
            ->sum('points');
    }

    public function getCompetitionCount(): int
    {
        return $this->registrations()
            ->whereHas(
                'score',
                function ($query) {
                    $query->whereNotNull('rank');
                }
            )
            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | JUARA 1
    |--------------------------------------------------------------------------
    |
    | Setiap Score dengan rank = 1 dihitung sebagai satu kemenangan
    | Juara 1.
    |
    | Nilai ini menjadi dasar utama Juara Umum.
    |
    */

    public function getChampionCount(): int
    {
        return (int) $this->scores()
            ->where('rank', 1)
            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | TREASURE
    |--------------------------------------------------------------------------
    */

    public function getTreasureCount(): int
    {
        return $this->registrations()
            ->whereHas(
                'competition',
                function ($query) {
                    $query->where(
                        'competition_category',
                        'treasure'
                    );
                }
            )
            ->whereHas(
                'score',
                function ($query) {
                    $query->whereNotNull('rank');
                }
            )
            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | JUARA FAVORIT
    |--------------------------------------------------------------------------
    |
    | Syarat saat ini:
    |
    | 1. Memiliki hasil Video Kreatif.
    | 2. Memiliki minimal 2 lomba lainnya yang memiliki hasil.
    |
    */

    public function isEligibleForFavoriteAward(): bool
    {
        $hasVideoKreatif = $this->registrations()
            ->whereHas(
                'competition',
                function ($query) {
                    $query->where(
                        'name',
                        'Video Kreatif'
                    );
                }
            )
            ->whereHas(
                'score',
                function ($query) {
                    $query->whereNotNull('rank');
                }
            )
            ->exists();

        if (!$hasVideoKreatif) {
            return false;
        }

        $otherCompetitions = $this->registrations()
            ->whereHas(
                'competition',
                function ($query) {
                    $query->where(
                        'name',
                        '!=',
                        'Video Kreatif'
                    );
                }
            )
            ->whereHas(
                'score',
                function ($query) {
                    $query->whereNotNull('rank');
                }
            )
            ->count();

        return $otherCompetitions >= 2;
    }

    /*
    |--------------------------------------------------------------------------
    | RANKING DATA
    |--------------------------------------------------------------------------
    |
    | Seluruh unit dikumpulkan dalam satu klasemen.
    |
    | champion_count
    |     = jumlah Juara 1
    |     = dasar Juara Umum
    |
    | total_points
    |     = total points evaluasi
    |     = bukan dasar Juara Umum
    |
    */

    public static function getRankingData()
    {
        return self::query()
            ->where(
                'is_admin',
                false
            )
            ->with([
                'scores',
                'registrations.competition',
            ])
            ->get()
            ->map(
                function ($unit) {
                    return (object) [
                        'unit' => $unit,

                        /*
                        |--------------------------------------------------------------------------
                        | JUARA 1
                        |--------------------------------------------------------------------------
                        */

                        'champion_count' =>
                            $unit->getChampionCount(),

                        /*
                        |--------------------------------------------------------------------------
                        | EVALUASI
                        |--------------------------------------------------------------------------
                        */

                        'total_points' =>
                            $unit->getTotalPoints(),

                        /*
                        |--------------------------------------------------------------------------
                        | DATA PENDUKUNG
                        |--------------------------------------------------------------------------
                        */

                        'treasure_count' =>
                            $unit->getTreasureCount(),

                        'is_favorite' =>
                            $unit->isEligibleForFavoriteAward(),

                        'competitions_count' =>
                            $unit->getCompetitionCount(),
                    ];
                }
            )
            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | EMAIL VERIFICATION
    |--------------------------------------------------------------------------
    */

    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function markEmailAsVerified(): bool
    {
        $this->email_verified_at =
            $this->freshTimestamp();

        return $this->save();
    }

    public function sendEmailVerificationNotification(): void
    {
        \Illuminate\Support\Facades\Mail::to(
            $this->email
        )->send(
            new \App\Mail\VerifyEmail($this)
        );
    }
}