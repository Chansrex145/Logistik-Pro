<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'locations';

    protected $primaryKey = 'idlocations';

    protected $fillable = [
        'name',
        'lintang',
        'bujur',
        'alamat_lengkap',
        'biaya_tol',
        'biaya_bongkar_muat'
    ];
    public function routesFrom()
    {
        return $this->hasMany(
            Route::class,
            'source_id',
            'idlocations'
        );
    }

    public function routesTo()
    {
        return $this->hasMany(
            Route::class,
            'destination_id',
            'idlocations'
        );
    }

    public function shipmentRoutes()
    {
        return $this->hasMany(
            ShipmentRoute::class,
            'locations_idlocations',
            'idlocations'
        );
    }
}
