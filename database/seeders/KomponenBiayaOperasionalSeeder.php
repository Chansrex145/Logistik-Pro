<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KomponenBiayaOperasionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $komponen = [
            [
                'nama_komponen' => 'Asuransi Kargo Berat (Heavy Cargo)',
                'cost_value' => 750000,
                'keterangan' => 'Asuransi khusus muatan berat berisiko tinggi',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_komponen' => 'Pengawalan Jalan (Patwal / Voorijder)',
                'cost_value' => 1500000,
                'keterangan' => 'Biaya pengawalan kepolisian untuk kargo over-dimension/over-weight',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_komponen' => 'Sewa Crane Tambahan (Loading/Unloading)',
                'cost_value' => 2000000,
                'keterangan' => 'Sewa alat berat ekstra di luar standar fasilitas lokasi',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_komponen' => 'Lashing & Pengikatan Rantai Baja',
                'cost_value' => 300000,
                'keterangan' => 'Material dan jasa pengikatan rantai untuk baja gulungan/beton',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_komponen' => 'Biaya Jembatan Timbang & Retribusi Khusus',
                'cost_value' => 150000,
                'keterangan' => 'Retribusi lintasan angkutan bermuatan tonase besar',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('komponen_biaya_operasional')->insert($komponen);
    }
}
