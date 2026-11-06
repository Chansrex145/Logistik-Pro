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
    Schema::create('barangs', function (Blueprint $table) {

        $table->id('idbarang');
        $table->string('nama_barang', 100);
        $table->double('berat');
       
        // FK CUSTOMER
        $table->unsignedBigInteger('customers_id');
        $table->foreign('customers_id')
              ->references('id')
              ->on('customers')
              ->onDelete('cascade');
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
        Schema::dropIfExists('barangs');
    }
};
