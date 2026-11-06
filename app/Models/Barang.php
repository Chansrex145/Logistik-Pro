<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{

    protected $table = 'barangs';

    protected $primaryKey = 'idbarang';

    protected $fillable = [
        'nama_barang',
        'berat',
        'customers_id',
        'users_id',
        'harga'
    ];

    // RELASI CUSTOMER
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customers_id');
    }

    //RELASI ORDER ITEM 
    public function orderItems()
    {
        return $this->hasMany(
            OrderItem::class,
            'barang_idbarang',
            'idbarang'
        );
    }

    public function shipmentItems()
    {
        return $this->hasMany(
            ShipmentItem::class,
            'barang_idbarang',
            'idbarang'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
