<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Latihan extends Model
{
    protected $table = 'barangs';

    protected $primaryKey = 'idbarang';

    protected $fillable = [
        'nama_barang',
        'berat',
        'customers_id'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customers_id');
    }
}
