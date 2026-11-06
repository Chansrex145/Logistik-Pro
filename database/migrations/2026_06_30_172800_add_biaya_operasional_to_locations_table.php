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
        Schema::table('locations', function (Blueprint $table) {
            $table->decimal('biaya_tol', 15, 2)->default(0)->after('alamat_lengkap');
            $table->decimal('biaya_bongkar_muat', 15, 2)->default(0)->after('biaya_tol');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['biaya_tol', 'biaya_bongkar_muat']);
        });
    }
};
