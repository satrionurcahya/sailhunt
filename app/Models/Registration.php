<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Registration extends Model
{
    // di Registration.php
    protected $fillable = ['unit_id', 'competition_id', 'status', 'payment_status'];

    public function uploads() {
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
    
    protected static function booted()
    {
        static::creating(function ($registration) {
            $registration->registration_code = strtoupper(Str::random(8));
        });
    }
}