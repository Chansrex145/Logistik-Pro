<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipment_items', function (Blueprint $table) {

            $table->id('idshipment_items');

            $table->integer('jumlah');

            $table->unsignedBigInteger(
                'shipments_idshipments'
            );

            $table->unsignedBigInteger(
                'barang_idbarang'
            );

            $table->timestamps();

            $table->foreign(
                'shipments_idshipments'
            )
                ->references('idshipment')
                ->on('shipments')
                ->onDelete('cascade');

            $table->foreign(
                'barang_idbarang'
            )
                ->references('idbarang')
                ->on('barangs')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'shipment_items'
        );
    }
};
