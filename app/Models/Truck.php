<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    protected $primaryKey = 'idtruck';

    protected $fillable = [
        'nama_truck',
        'plat_nomor',
        'kapasitas_berat',
        'tarif_per_km',
        'status',
        'users_id'
    ];

    public function shipments()
    {
        return $this->hasMany(
            Shipment::class,
            'truck_idtruck',
            'idtruck'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}