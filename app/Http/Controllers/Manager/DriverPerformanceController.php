<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Driver;

class DriverPerformanceController extends Controller
{
    public function index()
    {
        // SELECT drivers.*,
        // (SELECT COUNT(*) FROM shipments WHERE shipments.driver_iddrivers = drivers.iddrivers)
        // AS shipments_count FROM drivers
        // menghitung seberapa banyak pengiriman yang dilakukan oleh setiap driver
        $drivers = Driver::withCount(
            'shipments'
        )
            // orderByDesc -> Mengurutkan hasil dari terbesar ke terkecil 
            ->orderByDesc(
                'shipments_count'
            // ORDER BY shipments_count DESC;
            )
            ->get();

        return view(
            'manager.driver_performance',
            compact('drivers')
        );
    }
}
