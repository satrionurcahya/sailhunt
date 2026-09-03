<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = ['unit_id', 'competition_id', 'registration_id', 'name'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}