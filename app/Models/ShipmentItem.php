<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentItem extends Model
{
    protected $table = 'shipment_items';

    protected $primaryKey =
    'idshipment_items';

    protected $fillable = [

        'jumlah',

        'shipments_idshipments',

        'barang_idbarang'
    ];

    public function shipment()
    {
        return $this->belongsTo(
            Shipment::class,
            'shipments_idshipments',
            'idshipment'
        );
    }

    public function barang()
    {
        return $this->belongsTo(
            Barang::class,
            'barang_idbarang',
            'idbarang'
        );
    }
}
