<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {

            $table->id('idorders');

            $table->date('tanggal_pesanan');

            $table->dateTime('estimasi_tiba');

            $table->enum('status_order',[
                'pending',
                'diproses',
                'selesai',
                'dibatalkan'
            ]);

            $table->unsignedBigInteger('customers_id');

            $table->unsignedBigInteger('destination_id');

            $table->timestamps();

            $table->foreign('customers_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');

            $table->foreign('destination_id')
                ->references('idlocations')
                ->on('locations')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};