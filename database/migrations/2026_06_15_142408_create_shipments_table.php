<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {

            $table->id('idshipment');

            $table->unsignedBigInteger('orders_idorders');

            $table->unsignedBigInteger('truck_idtruck');

            $table->unsignedBigInteger('driver_iddrivers');

            $table->double('jarak_km')->default(0);

            $table->double('total_berat')->default(0);

            $table->double('total_biaya')->default(0);

            $table->date('tanggal_kirim');

            $table->dateTime('estimasi_tiba')->nullable();

            $table->enum('status_pengiriman',[
                'menunggu',
                'dikirim',
                'selesai'
            ])->default('menunggu');

            $table->timestamps();

            $table->foreign('orders_idorders')
                ->references('idorders')
                ->on('orders')
                ->onDelete('cascade');

            $table->foreign('truck_idtruck')
                ->references('idtruck')
                ->on('trucks')
                ->onDelete('cascade');

            $table->foreign('driver_iddrivers')
                ->references('iddrivers')
                ->on('drivers')
                ->onDelete('cascade');
                
            $table->unsignedBigInteger('users_id')->nullable();
            
            $table->foreign('users_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};