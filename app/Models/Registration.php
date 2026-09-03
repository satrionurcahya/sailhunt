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

    public function uploads()
    {
        return $this->hasMany(Upload::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }
}