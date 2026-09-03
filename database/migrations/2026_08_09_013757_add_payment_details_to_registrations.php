<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::table('registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('registrations', 'payment_type')) {
                $table->enum('payment_type', ['dp', 'lunas'])->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('registrations', 'amount_paid')) {
                $table->decimal('amount_paid', 10, 2)->nullable()->after('payment_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            //
        });
    }
};
