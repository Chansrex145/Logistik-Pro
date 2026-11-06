<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Order;
use App\Models\Shipment;
use App\Models\Driver;
use App\Models\Truck;

class ManagementReportController extends Controller
{
    public function exportPdf()
    {
        //Ambil data relasi sekaligus yang terkait dengan tabel order
        $orders = Order::with([
            'customer',
            'destination'
        ])->get();

        //Ambil data relasi sekaligus yang terkait dengan tabel shipment
        $shipments = Shipment::with([
            'truck',
            'driver'
        ])->get();
        
        // Menghitung seberapa banyak pengiriman yang dilakukan oleh setiap driver 
        $drivers = Driver::withCount(
            'shipments'
        )
            // dan mengurutkannya dari terbesar ke terkecil
            ->orderByDesc(
                'shipments_count'
            )
            ->get();
        
        // menghitung seberapa banyak shipment yang dilakukan oleh setiap truck 
        $trucks = Truck::withCount(
            'shipments'
        )
            // dan mengurutkannya dari terbesar ke terkecil
            ->orderByDesc(
                'shipments_count'
            )
            ->get();

        $totalOrder = Order::count();

        $totalShipment = Shipment::count();

        $totalDriver = Driver::count();

        $totalTruck = Truck::count();

        $totalBiaya = Shipment::sum(
            'total_biaya'
        );

        $pdf = Pdf::loadView(
            'manager.management_report_pdf',
            compact(
                'orders',
                'shipments',
                'drivers',
                'trucks',
                'totalOrder',
                'totalShipment',
                'totalDriver',
                'totalTruck',
                'totalBiaya'
            )
        );

        return $pdf->download(
            'laporan_manajemen.pdf'
        );
    }
}
