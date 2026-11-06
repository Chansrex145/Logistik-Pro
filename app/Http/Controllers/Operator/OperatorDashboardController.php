<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\Truck;
use App\Models\Driver;
use Carbon\Carbon;

class OperatorDashboardController extends Controller
{
    // Menampilkan halaman dashboard operator
    public function index()
    {
        $today = Carbon::today();

        // Menghitung shipment dengan status "menunggu"
        $shipmentPending = Shipment::where('status_pengiriman', 'menunggu')
            ->count();

        // Menghitung shipment yang dikirim hari ini
        $shipmentHariIni = Shipment::whereDate('tanggal_kirim_riil', $today)
            ->count();

        // Menghitung shipment yang sedang berjalan
        $shipmentSedangBerjalan = Shipment::where(
            'status_pengiriman',
            'dalam_perjalanan'
        )->count();

        // Menghitung shipment yang selesai hari ini
        $shipmentSelesaiHariIni = Shipment::where('status_pengiriman', 'selesai')
            ->whereDate('tanggal_terima', $today)
            ->count();

        // Menghitung truck yang tersedia
        $truckTersedia = Truck::where('status', 'tersedia')
            ->count();

        // Menghitung driver yang tersedia
        $driverTersedia = Driver::where('status', 'tersedia')
            ->count();

        // Data jadwal pengiriman hari ini
        $jadwalHariIni = Shipment::with([
            'order.destination',
            'truck',
            'driver'
        ])
            ->whereDate('tanggal_kirim_riil', $today)
            ->get();

        // Shipment yang membutuhkan tindakan
        $butuhTindakan = Shipment::with([
            'order.destination'
        ])
            ->where('status_pengiriman', 'menunggu')
            ->orderBy('created_at', 'asc')
            ->limit(5)
            ->get();

        // Riwayat shipment terbaru
        $riwayatTerbaru = Shipment::with([
            'order.destination',
            'truck',
            'driver'
        ])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return view(
            'operator.dashboard',
            compact(
                'shipmentPending',
                'shipmentHariIni',
                'shipmentSedangBerjalan',
                'shipmentSelesaiHariIni',
                'truckTersedia',
                'driverTersedia',
                'jadwalHariIni',
                'butuhTindakan',
                'riwayatTerbaru'
            )
        );
    }
}
