<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $primaryKey = 'idorder_items';

    protected $fillable = [
        'jumlah',
        'orders_idorders',
        'barang_idbarang'
    ];

    public function order()
    {
        return $this->belongsTo(
            Order::class,
            'orders_idorders',
            'idorders'
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