<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Shipment;

class BiayaReportController extends Controller
{
    public function index()
    {
        // count -> SELECT COUNT(*) FROM shipments
        $totalShipment =
            Shipment::count();

        // sum -> SELECT SUM(total_biaya) FROM shipments
        $totalBiaya =
            Shipment::sum(
                'total_biaya'
            );

        // avg -> SELECT AVG(total_biaya) FROM shipments
        $rataBiaya =
            Shipment::avg(
                'total_biaya'
            );

        // max -> SELECT MAX(total_biaya) FROM shipments
        $maxBiaya =
            Shipment::max(
                'total_biaya'
            );

        // min -> SELECT MIN(total_biaya) FROM shipments
        $minBiaya =
            Shipment::min(
                'total_biaya'
            );

        // with -> query(Eloquent) Ambil data relasi sekaligus yang terkait dengan tabel shipment
        // SELECT * FROM shipments JOIN trucks ON shipments.truck_id = trucks.id JOIN drivers ON shipments.driver_id = drivers.id
        $shipments = Shipment::with([
            'truck',
            'driver'
        ])->get();

        return view(
            'manager.biaya_report',
            // compact -> agar variabel bisa dipanggil di view 
            compact(
                'totalShipment',
                'totalBiaya',
                'rataBiaya',
                'maxBiaya',
                'minBiaya',
                'shipments'
            )
        );
    }
}
