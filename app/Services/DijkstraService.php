<?php

namespace App\Services;

use App\Models\Route;

class DijkstraService
{
    //  *  - distance : total jarak terpendek (km)
    //  *  - path     : urutan node ID dari source ke destination
    //  *  - steps    : log langkah-langkah iterasi Dijkstra (untuk keperluan visualisasi)
     
    public function shortestPath($source, $destination)
    {
        // Membuat Graph
        $routes = Route::all();
        $graph  = [];

        foreach ($routes as $route) {
            $graph[$route->source_id][$route->destination_id] = $route->distance;
            $graph[$route->destination_id][$route->source_id] = $route->distance;
        }

        // Jika node sumber tidak terdapat di graph, kembalikan hasil kosong.
        if (!isset($graph[$source])) {
            return ['distance' => 0, 'path' => [], 'steps' => []];
        }

        
        //  INISIALISASI VARIABEL DIJKSTRA

        $dist  = []; // jarak terbaik yang diketahui ke setiap node (awalnya ∞)
        $prev  = []; // node sebelumnya pada jalur terpendek 
        $queue = []; // node yang belum di proses

        foreach ($graph as $node => $edges) {
            $dist[$node]  = INF;
            $prev[$node]  = null;
            $queue[$node] = true;
        }

        $dist[$source] = 0; // Jarak dari source ke dirinya sendiri = 0

        
        // JALANKAN DJIKSTRA 
        //    Setiap iterasi: pilih node yang belum di proses dengan dist terkecil ($u)
        //    lalu relaksasi semua tetangga u.
        
        $steps      = []; // menyimpan log langkah-langkah untuk ditampilkan di UI
        $iteration  = 0; // digunakan untuk menghitung jumlah iterasi

        while (!empty($queue)) {

            $iteration++;

            // Ambil node yang belum diproses beserta jaraknya
            $available = array_intersect_key($dist, $queue);

            if (empty($available)) break;

            // Pilih node dengan jarak terkecil → node u
            $u = array_search(min($available), $available);

            if ($u === false || $dist[$u] == INF) break;

            // Keluarkan u dari antrian (tandai sudah diproses)
            unset($queue[$u]);

            // Catat snapshot tabel dist[] saat iterasi ini
            $distSnapshot = [];
            foreach ($dist as $node => $d) {
                if ($d == INF) {
                    $distSnapshot[$node] = '∞';
                } else {
                    $distSnapshot[$node] = $d;
                }
            }

            // Catat detail relaksasi tetangga yang terjadi pada iterasi ini
            $relaxations = [];

            if (isset($graph[$u])) {
                foreach ($graph[$u] as $v => $cost) {
                    $alt = $dist[$u] + $cost;
                    if ($dist[$v] == INF) {
                        $oldDist = '∞';
                    } else {
                        $oldDist = $dist[$v];
                    }
                    $updated = false;

                    if ($alt < $dist[$v]) {
                        $dist[$v] = $alt;
                        $prev[$v] = $u;
                        $updated  = true;
                    }

                    $relaxations[] = [
                        'neighbor'    => $v,
                        'edge_weight' => $cost,
                        'new_dist'    => $dist[$u] + $cost,
                        'old_dist'    => $oldDist,
                        'updated'     => $updated,
                    ];
                }
            }

            // Rekam satu langkah
            $steps[] = [
                'iteration'    => $iteration,
                'current_node' => $u,
                'current_dist' => $dist[$u],
                'dist_table'   => $distSnapshot,
                'relaxations'  => $relaxations,
                'finished'     => ($u == $destination),
            ];

            // Jika sudah mencapai tujuan, hentikan
            if ($u == $destination) break;
        }

        // REKONSTRUKSI JALUR

        // Lacak mundur dari destination ke source menggunakan array prev[].
        
        if (!isset($dist[$destination]) || $dist[$destination] == INF) {
            return ['distance' => 0, 'path' => [], 'steps' => $steps];
        }

        $path = [];
        $u    = $destination;

        while ($u !== null) {
            array_unshift($path, $u);
            $u = $prev[$u];
        }

        return [
            'distance' => $dist[$destination],
            'path'     => $path,
            'steps'    => $steps,
        ];
    }
}
