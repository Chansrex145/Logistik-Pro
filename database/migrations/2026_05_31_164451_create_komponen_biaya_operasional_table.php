<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komponen_biaya_operasional', function (Blueprint $table) {

            $table->bigIncrements('idkomponen_biaya_operasional');

            $table->string('nama_komponen', 100);

            $table->double('cost_value');

            $table->text('keterangan')->nullable();

            $table->timestamps();
            $table->unsignedBigInteger('users_id')->nullable();
            
            $table->foreign('users_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komponen_biaya_operasional');
    }
};