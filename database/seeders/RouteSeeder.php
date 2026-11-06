<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;
use App\Models\Route;

class RouteSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus route lama sebelum membuat koneksi baru.
        Route::query()->delete();

        // Ambil ID lokasi berdasarkan nama.
        $locations = Location::pluck('idlocations', 'name');

        // [Kota asal, Kota tujuan, jarak km]
        $routes = [
            // Banten dan Jawa Barat
            ['Serang', 'Jakarta', 90],
            ['Serang', 'Bandung', 200],
            ['Jakarta', 'Bandung', 150],
            ['Jakarta', 'Cirebon', 220],
            ['Bandung', 'Cirebon', 150],

            // Koneksi menuju Jawa Tengah
            ['Bandung', 'Semarang', 200],
            ['Bandung', 'Yogyakarta', 200],
            ['Cirebon', 'Semarang', 200],

            // Jawa Tengah
            ['Semarang', 'Yogyakarta', 170],
            ['Semarang', 'Solo', 170],
            ['Semarang', 'Madiun', 250],
            ['Yogyakarta', 'Solo', 130],

            // Koneksi menuju Jawa Timur
            ['Yogyakarta', 'Malang', 170],
            ['Solo', 'Madiun', 170],
            ['Solo', 'Malang', 170],
            ['Madiun', 'Malang', 170],

            // Jawa Timur
            ['Madiun', 'Surabaya', 170],
            ['Malang', 'Surabaya', 150],
            ['Surabaya', 'Jember', 200],
            ['Malang', 'Jember', 200],
        ];

        foreach ($routes as [$source, $destination, $distance]) {
            if (
                !isset($locations[$source]) ||
                !isset($locations[$destination])
            ) {
                throw new \RuntimeException(
                    "Lokasi {$source} atau {$destination} belum tersedia."
                );
            }

            Route::create([
                'source_id' => $locations[$source],
                'destination_id' => $locations[$destination],
                'distance' => $distance,
            ]);
        }
    }
}
