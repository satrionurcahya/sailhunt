<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $fillable = [
        'unit_id',
        'registration_id',
        'type',
        'category',
        'file_path',
        'submission_link',
        'status',
        'drive_file_id', // <-- TAMBAHKAN UNTUK CACHE
    ];

    public function unit() {
        return $this->belongsTo(Unit::class);
    }

    public function registration() {
        return $this->belongsTo(Registration::class);
    }
}