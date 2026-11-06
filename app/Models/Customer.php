<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';

    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
        'users_id'
    ];

    public function barang()
    {
        return $this->hasMany(Barang::class, 'customers_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}