<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    /**
     * Log aktivitas ke database.
     */
    public function logActivity(string $action, string $module = null, string $description = null, array $data = null): void
    {
        $unitId = session('unit_id');

        ActivityLog::create([
            'unit_id' => $unitId,
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'data' => $data,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Log aktivitas untuk admin.
     */
    public function logAdminActivity(string $action, string $module = null, string $description = null, array $data = null): void
    {
        $this->logActivity('admin_' . $action, $module, $description, $data);
    }

    /**
     * Log aktivitas untuk unit.
     */
    public function logUnitActivity(string $action, string $module = null, string $description = null, array $data = null): void
    {
        $this->logActivity($action, $module, $description, $data);
    }
}