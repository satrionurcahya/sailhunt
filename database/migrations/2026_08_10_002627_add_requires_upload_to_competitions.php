<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('competitions', function (Blueprint $table) {
            if (!Schema::hasColumn('competitions', 'requires_upload')) {
                $table->boolean('requires_upload')->default(false)->after('max_teams');
            }
            if (!Schema::hasColumn('competitions', 'upload_type')) {
                $table->enum('upload_type', ['file', 'link'])->default('file')->after('requires_upload');
            }
        });
    }

    public function down()
    {
        Schema::table('competitions', function (Blueprint $table) {
            if (Schema::hasColumn('competitions', 'requires_upload')) {
                $table->dropColumn('requires_upload');
            }
            if (Schema::hasColumn('competitions', 'upload_type')) {
                $table->dropColumn('upload_type');
            }
        });
    }
};