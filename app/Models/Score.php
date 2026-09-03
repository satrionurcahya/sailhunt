<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $fillable = ['registration_id', 'score', 'rank', 'notes', 'points'];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Konfigurasi poin berdasarkan peringkat.
     * Sesuai dengan ketentuan Juara Umum di Juklak.
     */
    public static function getPointsByRank(int $rank): int
    {
        return match ($rank) {
            1 => 10,   // Juara I
            2 => 7,    // Juara II
            3 => 5,    // Juara III
            4 => 3,    // Juara Harapan I
            5 => 1,    // Juara Harapan II
            default => 0,
        };
    }

    /**
     * Hitung dan simpan poin berdasarkan rank.
     */
    public function calculatePoints(): void
    {
        $this->points = self::getPointsByRank($this->rank ?? 0);
        $this->save();
    }
}