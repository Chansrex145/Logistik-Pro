<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $table = 'shipments';

    protected $primaryKey = 'idshipment';

    protected $fillable = [
        'orders_idorders',
        'truck_idtruck',
        'driver_iddrivers',
        'jarak_km',
        'total_berat',
        'total_biaya',
        'tanggal_kirim_riil',
        'tanggal_terima',
        'estimasi_tiba',
        'status_pengiriman',
        'users_id'
    ];

    public function order()
    {
        return $this->belongsTo(
            Order::class,
            'orders_idorders',
            'idorders'
        );
    }

    public function truck()
    {
        return $this->belongsTo(
            Truck::class,
            'truck_idtruck',
            'idtruck'
        );
    }

    public function driver()
    {
        return $this->belongsTo(
            Driver::class,
            'driver_iddrivers',
            'iddrivers'
        );
    }
    public function shipmentRoutes()
    {
        return $this->hasMany(
            ShipmentRoute::class,
            'shipments_idshipments',
            'idshipment'
        );
    }

    public function shipmentItems()
    {
        return $this->hasMany(
            ShipmentItem::class,
            'shipments_idshipments',
            'idshipment'
        );
    }

    public function shipmentCosts()
    {
        return $this->hasMany(
            ShipmentCost::class,
            'shipment_id',
            'idshipment'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
