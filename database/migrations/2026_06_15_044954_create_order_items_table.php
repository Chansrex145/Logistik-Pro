<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {

            $table->id('idorder_items');

            $table->integer('jumlah');

            $table->unsignedBigInteger('orders_idorders');

            $table->unsignedBigInteger('barang_idbarang');

            $table->timestamps();

            $table->foreign('orders_idorders')
                ->references('idorders')
                ->on('orders')
                ->onDelete('cascade');

            $table->foreign('barang_idbarang')
                ->references('idbarang')
                ->on('barangs')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};