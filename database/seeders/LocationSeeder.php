<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Serang',
                'lintang' => -6.1201,
                'bujur' => 106.1500,
                'alamat_lengkap' => 'Jl. Raya Serang, Kota Serang, Banten',
                'biaya_tol' => 15000,
                'biaya_bongkar_muat' => 100000,
            ],
            [
                'name' => 'Jakarta',
                'lintang' => -6.2088,
                'bujur' => 106.8456,
                'alamat_lengkap' => 'Jl. Raya Cakung, Jakarta Timur, DKI Jakarta',
                'biaya_tol' => 35000,
                'biaya_bongkar_muat' => 150000,
            ],
            [
                'name' => 'Bandung',
                'lintang' => -6.9175,
                'bujur' => 107.6191,
                'alamat_lengkap' => 'Jl. Soekarno-Hatta, Kota Bandung, Jawa Barat',
                'biaya_tol' => 45000,
                'biaya_bongkar_muat' => 125000,
            ],
            [
                'name' => 'Cirebon',
                'lintang' => -6.7063,
                'bujur' => 108.5570,
                'alamat_lengkap' => 'Jl. Brigjen Dharsono, Kota Cirebon, Jawa Barat',
                'biaya_tol' => 55000,
                'biaya_bongkar_muat' => 100000,
            ],
            [
                'name' => 'Semarang',
                'lintang' => -6.9667,
                'bujur' => 110.4167,
                'alamat_lengkap' => 'Jl. Kaligawe, Kota Semarang, Jawa Tengah',
                'biaya_tol' => 75000,
                'biaya_bongkar_muat' => 150000,
            ],
            [
                'name' => 'Yogyakarta',
                'lintang' => -7.7956,
                'bujur' => 110.3695,
                'alamat_lengkap' => 'Jl. Ring Road, Kota Yogyakarta, DI Yogyakarta',
                'biaya_tol' => 25000,
                'biaya_bongkar_muat' => 125000,
            ],
            [
                'name' => 'Solo',
                'lintang' => -7.5755,
                'bujur' => 110.8243,
                'alamat_lengkap' => 'Jl. Adi Sumarmo, wilayah Solo Raya, Jawa Tengah',
                'biaya_tol' => 30000,
                'biaya_bongkar_muat' => 100000,
            ],
            [
                'name' => 'Madiun',
                'lintang' => -7.6292,
                'bujur' => 111.5231,
                'alamat_lengkap' => 'Jl. Raya Madiun, Kota Madiun, Jawa Timur',
                'biaya_tol' => 25000,
                'biaya_bongkar_muat' => 100000,
            ],
            [
                'name' => 'Malang',
                'lintang' => -7.9666,
                'bujur' => 112.6326,
                'alamat_lengkap' => 'Jl. Raya Karanglo, wilayah Malang, Jawa Timur',
                'biaya_tol' => 30000,
                'biaya_bongkar_muat' => 125000,
            ],
            [
                'name' => 'Surabaya',
                'lintang' => -7.2575,
                'bujur' => 112.7521,
                'alamat_lengkap' => 'Jl. Margomulyo, Kota Surabaya, Jawa Timur',
                'biaya_tol' => 40000,
                'biaya_bongkar_muat' => 150000,
            ],
            [
                'name' => 'Jember',
                'lintang' => -8.1722,
                'bujur' => 113.7000,
                'alamat_lengkap' => 'Jl. Raya Ajung, Kabupaten Jember, Jawa Timur',
                'biaya_tol' => 20000,
                'biaya_bongkar_muat' => 100000,
            ],
        ];

        foreach ($locations as $location) {
            Location::updateOrCreate(
                ['name' => $location['name']],
                $location
            );
        }
    }
}
