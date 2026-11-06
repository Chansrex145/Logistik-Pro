<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routes', function (Blueprint $table) {

            $table->id('idroutes');

            // lokasi asal
            $table->unsignedBigInteger('source_id');

            // lokasi tujuan
            $table->unsignedBigInteger('destination_id');

            // jarak dalam KM
            $table->decimal('distance',10,2);

            $table->timestamps();

            $table->foreign('source_id')
                  ->references('idlocations')
                  ->on('locations')
                  ->onDelete('cascade');

            $table->foreign('destination_id')
                  ->references('idlocations')
                  ->on('locations')
                  ->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};