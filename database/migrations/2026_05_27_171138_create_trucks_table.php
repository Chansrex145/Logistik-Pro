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
        Schema::create('trucks', function (Blueprint $table) {

            $table->id('idtruck');
            $table->string('nama_truck', 100);
            $table->string('plat_nomor', 20);
            $table->integer('kapasitas_berat');
            $table->double('tarif_per_km');
            $table->enum('status', [
                'tersedia',
                'digunakan',
                'maintenance'
            ]);
            $table->timestamps();
            $table->unsignedBigInteger('users_id')->nullable();
            
            $table->foreign('users_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trucks');
    }
};
