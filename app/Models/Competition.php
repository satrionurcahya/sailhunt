<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    protected $fillable = [
        'name',
        'category',
        'type',
        'competition_category', // <-- PASTIKAN ADA
        'fee',
        'team_size',
        'max_teams',
        'description',
        'requires_upload',
        'upload_type',
        'registration_deadline',
        'upload_deadline',
        're_registration_deadline',
    ];

    protected $casts = [
        'registration_deadline' => 'datetime',
        'upload_deadline' => 'datetime',
        're_registration_deadline' => 'datetime',
        'fee' => 'decimal:2',
        'competition_category' => 'string',
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Cek apakah lomba termasuk kategori Treasure.
     */
    public function isTreasure(): bool
    {
        return $this->competition_category === 'treasure';
    }

    /**
     * Cek apakah lomba termasuk kategori Bounty.
     */
    public function isBounty(): bool
    {
        return $this->competition_category === 'bounty';
    }

    /**
     * Label kategori untuk ditampilkan.
     */
    public function getCategoryLabelAttribute(): string
    {
        return $this->isTreasure() ? 'Treasure' : 'Bounty';
    }

    /**
     * Badge HTML untuk kategori.
     */
    public function getCategoryBadgeAttribute(): string
    {
        if ($this->isTreasure()) {
            return '<span class="badge badge-warning" style="background: #D4AF37; color: #1e293b; font-weight: 700;"><i class="fas fa-crown mr-1"></i> Treasure</span>';
        }
        return '<span class="badge badge-secondary" style="background: #64748b; color: #fff; font-weight: 700;"><i class="fas fa-medal mr-1"></i> Bounty</span>';
    }
}