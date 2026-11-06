<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Truck;
use App\Models\Driver;
use Illuminate\Support\Facades\DB;

class TruckDriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bersihkan tabel untuk mencegah duplikat saat test
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Truck::truncate();
        Driver::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Tambah Truck
        Truck::create([
            'idtruck' => 1,
            'nama_truck' => 'Truk Fuso Andalan',
            'plat_nomor' => 'B 1234 CD',
            'kapasitas_berat' => 8000,
            'konsumsi_bensin' => 10.5,
            'tarif_per_km' => 2000,
            'biaya_operasional_harian' => 150000,
            'status' => 'tersedia',
            'volume_kapasitas' => 30,
            'panjang' => 6.0,
            'lebar' => 2.4,
            'tinggi' => 2.5
        ]);

        Truck::create([
            'idtruck' => 2,
            'nama_truck' => 'Truk Colt Diesel',
            'plat_nomor' => 'B 5678 EF',
            'kapasitas_berat' => 4000,
            'konsumsi_bensin' => 8.0,
            'tarif_per_km' => 1500,
            'biaya_operasional_harian' => 100000,
            'status' => 'tersedia',
            'volume_kapasitas' => 15,
            'panjang' => 4.0,
            'lebar' => 2.0,
            'tinggi' => 2.0
        ]);

        Truck::create([
            'idtruck' => 3,
            'nama_truck' => 'Truk Tronton Hino',
            'plat_nomor' => 'L 9999 XZ',
            'kapasitas_berat' => 20000, // 20 Ton
            'konsumsi_bensin' => 18.5,
            'tarif_per_km' => 4500,
            'biaya_operasional_harian' => 300000,
            'status' => 'tersedia',
            'volume_kapasitas' => 45,
            'panjang' => 9.5,
            'lebar' => 2.5,
            'tinggi' => 2.8
        ]);

        // Tambah Driver
        Driver::create([
            'nama' => 'Driver A (Devan)',
            'nomor_telepon' => '081234567890',
            'tarif_harian' => 200000,
            'status' => 'tersedia'
        ]);

        Driver::create([
            'nama' => 'Driver B (Rian)',
            'nomor_telepon' => '089876543210',
            'tarif_harian' => 200000,
            'status' => 'tersedia'
        ]);
    }
}
