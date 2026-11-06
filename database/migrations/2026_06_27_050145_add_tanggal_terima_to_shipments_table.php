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
        Schema::table('shipments', function (Blueprint $table) {
            $table->date('tanggal_terima')->nullable()->after('tanggal_kirim_riil');
        });

        // Use raw statement to update ENUM without dropping
        DB::statement("ALTER TABLE shipments MODIFY COLUMN status_pengiriman ENUM('menunggu', 'dalam_perjalanan', 'selesai', 'batal', 'retur') DEFAULT 'menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn('tanggal_terima');
        });

        // Revert to old ENUM (assuming old was menunggu, dikirim, selesai based on previous migration)
        DB::statement("ALTER TABLE shipments MODIFY COLUMN status_pengiriman ENUM('menunggu', 'dikirim', 'selesai') DEFAULT 'menunggu'");
    }
};
