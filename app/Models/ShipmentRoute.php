<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentRoute extends Model
{
    protected $table = 'shipment_routes';

    protected $primaryKey = 'idshipments_route';

    protected $fillable = [
        'urutan',
        'shipments_idshipments',
        'locations_idlocations'
    ];

    public function shipment()
    {
        return $this->belongsTo(
            Shipment::class,
            'shipments_idshipments',
            'idshipment'
        );
    }

    public function location()
    {
        return $this->belongsTo(
            Location::class,
            'locations_idlocations',
            'idlocations'
        );
    }
}