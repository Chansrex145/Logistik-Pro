<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipment_costs', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger(
                'shipment_id'
            );

            $table->unsignedBigInteger(
                'komponen_id'
            );

            $table->double(
                'cost_value'
            );

            $table->timestamps();

            $table->foreign(
                'shipment_id'
            )
                ->references('idshipment')
                ->on('shipments')
                ->onDelete('cascade');

            $table->foreign(
                'komponen_id'
            )
                ->references(
                    'idkomponen_biaya_operasional'
                )
                ->on(
                    'komponen_biaya_operasional'
                )
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'shipment_costs'
        );
    }
};
