<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            if (!Schema::hasColumn('competitions', 'requires_upload')) {
                $table->boolean('requires_upload')->default(false)->after('description');
            }
            if (!Schema::hasColumn('competitions', 'upload_type')) {
                $table->enum('upload_type', ['file', 'link'])->nullable()->after('requires_upload');
            }
            if (!Schema::hasColumn('competitions', 'registration_deadline')) {
                $table->timestamp('registration_deadline')->nullable()->after('upload_type');
            }
            if (!Schema::hasColumn('competitions', 'upload_deadline')) {
                $table->timestamp('upload_deadline')->nullable()->after('registration_deadline');
            }
            if (!Schema::hasColumn('competitions', 're_registration_deadline')) {
                $table->timestamp('re_registration_deadline')->nullable()->after('upload_deadline');
            }
        });
    }

    public function down(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            $table->dropColumn([
                'requires_upload',
                'upload_type',
                'registration_deadline',
                'upload_deadline',
                're_registration_deadline'
            ]);
        });
    }
};