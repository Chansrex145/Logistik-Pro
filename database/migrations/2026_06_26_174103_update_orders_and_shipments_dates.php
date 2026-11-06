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
        Schema::table('orders', function (Blueprint $table) {
            $table->date('tanggal_estimasi_kirim')->nullable()->after('tanggal_pesanan');
        });

        Schema::table('shipments', function (Blueprint $table) {
            $table->renameColumn('tanggal_kirim', 'tanggal_kirim_riil');
        });
        
        // Since we rename, we also need to make sure the column is nullable.
        // But doing change() requires doctrine/dbal. For simplicity, let's just rename it and assume it can be nullable or we handle nulls at the app level.
        // Actually, let's make sure it's nullable if we can.
        Schema::table('shipments', function (Blueprint $table) {
            $table->date('tanggal_kirim_riil')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('tanggal_estimasi_kirim');
        });

        Schema::table('shipments', function (Blueprint $table) {
            $table->renameColumn('tanggal_kirim_riil', 'tanggal_kirim');
        });
    }
};
