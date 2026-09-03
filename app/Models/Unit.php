<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Unit extends Model
{
    protected $table = 'units';

    protected $fillable = [
        'level', 'school_name', 'address', 'city', 'postal_code',
        'coach_name', 'trainer_name', 'commander_name',
        'email', 'username', 'password', 'status', 'is_admin'
    ];

    protected $hidden = [
        'password',
    ];

    // ============================================================
    // PASSWORD MUTATOR (Otomatis hash saat set)
    // ============================================================
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn($value) => bcrypt($value),
        );
    }

    // ============================================================
    // RELASI
    // ============================================================
    public function registrations()
    {
        return $this->hasMany(\App\Models\Registration::class);
    }

    public function uploads()
    {
        return $this->hasMany(\App\Models\Upload::class);
    }

    public function scores()
    {
        return $this->hasManyThrough(
            \App\Models\Score::class,
            \App\Models\Registration::class,
            'unit_id',
            'registration_id',
            'id',
            'id'
        );
    }

    // ============================================================
    // BUSINESS LOGIC
    // ============================================================
    public function autoRegisterGPS()
    {
        $gps = \App\Models\Competition::where('name', 'Gerakan Pungut Sampah (GPS)')->first();

        if ($gps && !$this->registrations()->where('competition_id', $gps->id)->exists()) {
            $this->registrations()->create([
                'competition_id'  => $gps->id,
                'status'          => 'confirmed',
                'payment_status'  => 'verified',
            ]);
        }
    }

    public function getTotalPoints(): int
    {
        return $this->scores()->sum('points');
    }

    public function getTreasureCount(): int
    {
        return $this->registrations()
            ->whereHas('competition', function ($q) {
                $q->where('competition_category', 'treasure');
            })
            ->whereHas('score', function ($q) {
                $q->whereNotNull('rank');
            })
            ->count();
    }

    public function isEligibleForFavoriteAward(): bool
    {
        $hasVideoKreatif = $this->registrations()
            ->whereHas('competition', function ($q) {
                $q->where('name', 'Video Kreatif');
            })
            ->whereHas('score', function ($q) {
                $q->whereNotNull('rank');
            })
            ->exists();

        if (!$hasVideoKreatif) {
            return false;
        }

        $otherCompetitions = $this->registrations()
            ->whereHas('competition', function ($q) {
                $q->where('name', '!=', 'Video Kreatif');
            })
            ->whereHas('score', function ($q) {
                $q->whereNotNull('rank');
            })
            ->count();

        return $otherCompetitions >= 2;
    }

    public static function getRankingData()
    {
        return self::with(['scores', 'registrations.competition'])
            ->get()
            ->map(function ($unit) {
                return (object) [
                    'unit' => $unit,
                    'total_points' => $unit->getTotalPoints(),
                    'treasure_count' => $unit->getTreasureCount(),
                    'is_favorite' => $unit->isEligibleForFavoriteAward(),
                    'competitions_count' => $unit->registrations()
                        ->whereHas('score', fn($q) => $q->whereNotNull('rank'))
                        ->count(),
                ];
            })
            ->sortByDesc('total_points')
            ->values();
    }

    // ============================================================
    // EMAIL VERIFICATION (FITUR VERIFIKASI)
    // ============================================================
    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function markEmailAsVerified(): bool
    {
        $this->email_verified_at = $this->freshTimestamp();
        return $this->save();
    }

    public function sendEmailVerificationNotification(): void
    {
        \Illuminate\Support\Facades\Mail::to($this->email)
            ->send(new \App\Mail\VerifyEmail($this));
    }
}