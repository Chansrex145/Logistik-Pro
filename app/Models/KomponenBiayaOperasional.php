<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomponenBiayaOperasional extends Model
{
    protected $table = 'komponen_biaya_operasional';

    protected $primaryKey = 'idkomponen_biaya_operasional';

    protected $fillable = [
        'nama_komponen',
        'cost_value',
        'keterangan',
        'users_id'
    ];

    public function shipmentCosts()
    {
        return $this->hasMany(
            ShipmentCost::class,
            'komponen_id',
            'idkomponen_biaya_operasional'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
