<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\Truck;
use App\Models\Driver;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();

        // 1. Jumlah shipment yang masih menunggu
        $shipmentPending = Shipment::where(
            'status_pengiriman',
            'menunggu'
        )->count();

        // 2. Jumlah shipment yang dikirim hari ini
        $shipmentHariIni = Shipment::whereDate(
            'tanggal_kirim_riil',
            $today
        )->count();

        // 3. Jumlah shipment yang sedang dalam perjalanan
        $shipmentSedangBerjalan = Shipment::where(
            'status_pengiriman',
            'dalam_perjalanan'
        )->count();

        // 4. Jumlah shipment selesai hari ini
        $shipmentSelesaiHariIni = Shipment::where(
            'status_pengiriman',
            'selesai'
        )
        ->whereDate('tanggal_terima', $today)
        ->count();

        // 5. Jumlah truck yang tersedia
        $truckTersedia = Truck::where(
            'status',
            'tersedia'
        )->count();

        // 6. Jumlah driver yang tersedia
        $driverTersedia = Driver::where(
            'status',
            'tersedia'
        )->count();

        // 7. Jadwal shipment hari ini
        $jadwalHariIni = Shipment::with([
            'order.destination',
            'truck',
            'driver'
        ])
        ->whereDate('tanggal_kirim_riil', $today)
        ->get();

        // 8. Shipment yang membutuhkan tindakan
        $butuhTindakan = Shipment::with([
            'order.destination'
        ])
        ->where(
            'status_pengiriman',
            'menunggu'
        )
        ->orderBy(
            'created_at',
            'asc'
        )
        ->limit(5)
        ->get();

        // 9. Riwayat shipment terbaru
        $riwayatTerbaru = Shipment::with([
            'order.destination',
            'truck',
            'driver'
        ])
        ->orderBy(
            'updated_at',
            'desc'
        )
        ->limit(5)
        ->get();

        return view(
            'admin.dashboard',
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