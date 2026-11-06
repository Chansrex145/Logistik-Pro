<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $table = 'drivers';

    protected $primaryKey = 'iddrivers';

    protected $fillable = [
        'nama',
        'nomor_telepon',
        'tarif_harian',
        'status',
        'users_id'
    ];

    public function shipments()
    {
        return $this->hasMany(
            Shipment::class,
            'driver_iddrivers',
            'iddrivers'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}