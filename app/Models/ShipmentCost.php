<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentCost extends Model
{
    protected $table =
    'shipment_costs';

    protected $fillable = [

        'shipment_id',

        'komponen_id',

        'cost_value'
    ];

    public function shipment()
    {
        return $this->belongsTo(
            Shipment::class,
            'shipment_id',
            'idshipment'
        );
    }

    public function komponen()
    {
        return $this->belongsTo(
            KomponenBiayaOperasional::class,
            'komponen_id',
            'idkomponen_biaya_operasional'
        );
    }
}
