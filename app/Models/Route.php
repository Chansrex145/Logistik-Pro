<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $table = 'routes';

    protected $primaryKey = 'idroutes';

    protected $fillable = [
        'source_id',
        'destination_id',
        'distance'
    ];

    public function source()
    {
        return $this->belongsTo(
            Location::class,
            'source_id',
            'idlocations'
        );
    }

    public function destination()
    {
        return $this->belongsTo(
            Location::class,
            'destination_id',
            'idlocations'
        );
    }

    public function shipmentRoutes()
    {
        return $this->hasMany(
            ShipmentRoute::class,
            'routes_idroutes',
            'idroutes'
        );
    }
}