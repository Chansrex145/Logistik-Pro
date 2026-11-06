<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Shipment;

class ShipmentReportController extends Controller
{
    public function index()
    {
        // mengambil data relasi sekaligus yang terkait dengan tabel shipment
        $shipments = Shipment::with([
            'order',
            'truck',
            'driver'
        ])->get();

        return view(
            'manager.shipment_report',
            compact('shipments')
        );
    }
}
