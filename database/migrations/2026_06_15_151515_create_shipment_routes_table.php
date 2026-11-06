<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipment_routes', function (Blueprint $table) {

            $table->id('idshipments_route');

            $table->integer('urutan');

            $table->unsignedBigInteger('shipments_idshipments');

            $table->unsignedBigInteger('locations_idlocations');

            $table->timestamps();

            $table->foreign('shipments_idshipments')
                ->references('idshipment')
                ->on('shipments')
                ->onDelete('cascade');

            $table->foreign('locations_idlocations')
                ->references('idlocations')
                ->on('locations')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_routes');
    }
};