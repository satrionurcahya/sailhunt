<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'unit_id',
        'competition_id',
        'status',
        'payment_status',
        'payment_type',
        'amount_paid',
        'registration_code',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
    ];

    /**
     * Relasi ke upload dokumen.
     */
    public function uploads()
    {
        return $this->hasMany(Upload::class);
    }

    /**
     * Relasi ke peserta.
     */
    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    /**
     * Relasi ke unit/sekolah.
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Relasi ke kompetisi.
     */
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    /**
     * Relasi ke skor.
     *
     * Satu registration memiliki satu record score.
     */
    public function score()
    {
        return $this->hasOne(Score::class);
    }
}