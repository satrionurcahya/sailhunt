<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('uploads', function (Blueprint $table) {
            if (!Schema::hasColumn('uploads', 'drive_file_id')) {
                $table->string('drive_file_id')->nullable()->after('file_path')
                      ->comment('ID file Google Drive untuk caching');
            }

            if (!Schema::hasIndex('uploads', 'uploads_drive_file_id_index')) {
                $table->index('drive_file_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('uploads', function (Blueprint $table) {
            $table->dropColumn('drive_file_id');
        });
    }
};