<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'unit_id', 'action', 'module', 'description', 'data', 'ip_address', 'user_agent'
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function scopeForUnit($query, $unitId)
    {
        return $query->where('unit_id', $unitId);
    }

    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeModule($query, $module)
    {
        return $query->where('module', $module);
    }
}