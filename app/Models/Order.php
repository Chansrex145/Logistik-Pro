<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $primaryKey = 'idorders';

    protected $fillable = [
        'tanggal_pesanan',
        'tanggal_estimasi_kirim',
        'estimasi_tiba',
        'status_order',
        'customers_id',
        'destination_id'

    ];

    public function customer()
    {
        return $this->belongsTo(
            Customer::class,
            'customers_id',
            'id'
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

    public function orderItems()
    {
        return $this->hasMany(
            OrderItem::class,
            'orders_idorders',
            'idorders'
        );
    }
    public function shipment()
    {
        return $this->hasOne(
            Shipment::class,
            'orders_idorders',
            'idorders'
        );
    }
}
