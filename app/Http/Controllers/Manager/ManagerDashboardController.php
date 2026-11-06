<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use App\Models\Shipment;
use App\Models\Driver;
use App\Models\Truck;
use Carbon\Carbon;


class ManagerDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->month;
        $thisYear = Carbon::now()->year;

        $pengirimanHariIni = Shipment::whereDate('tanggal_kirim_riil', $today->toDateString())->count();

        $totalPengirimanBulanIni = Shipment::whereMonth('tanggal_kirim_riil', $thisMonth)
            ->whereYear('tanggal_kirim_riil', $thisYear)
            ->count();

        $shipmentSelesai = Shipment::where('status_pengiriman', 'selesai')
            ->whereMonth('tanggal_terima', $thisMonth)
            ->whereYear('tanggal_terima', $thisYear)
            ->count();

        // SELECT COUNT(*) FROM shipments 
        // WHERE 
        // (status_pengiriman = 'selesai' AND tanggal_terima > estimasi_tiba) -- Kurung 1 (diwakili $query pertama)
        // OR 
        // (status_pengiriman != 'selesai' AND estimasi_tiba < '2026-07-18'); -- Kurung 2 (diwakili $query kedua)
        $shipmentTerlambat = Shipment::where(function ($query) use ($today) {
            $query->where('status_pengiriman', 'selesai')
                ->whereColumn('tanggal_terima', '>', 'estimasi_tiba');
        })->orWhere(function ($query) use ($today) {
            $query->where('status_pengiriman', '!=', 'selesai')
                ->where('estimasi_tiba', '<', $today->toDateString());
        })->count();

        // Financials
        // "total_biaya" pada tabel shipment adalah nilai Tagihan (Invoice) yang dibebankan kepada Customer.
        // Oleh karena itu, jumlah dari total_biaya merupakan Total Pendapatan perusahaan.
        $totalPendapatan = Shipment::whereMonth('tanggal_kirim_riil', $thisMonth)
            ->whereYear('tanggal_kirim_riil', $thisYear)
            ->sum('total_biaya');

        // Karena sistem belum mencatat detail laba bersih secara terpisah di tabel master, 
        // kita asumsikan Biaya Operasional (HPP) adalah hasil potongan margin 30% dari Pendapatan.
        // (Rumus: Pendapatan / 1.3)
        $totalBiayaOperasional = $totalPendapatan / 1.3;

        // Top 5 driver teraktif 
        $topDrivers = Driver::withCount(['shipments' => function ($query) use ($thisMonth, $thisYear) {
            $query->whereMonth('tanggal_kirim_riil', $thisMonth)->whereYear('tanggal_kirim_riil', $thisYear);
        }])->orderByDesc('shipments_count')->take(5)->get();

        // Top 5 truck terbanyak digunakan 
        $topTrucks = Truck::withCount(['shipments' => function ($query) use ($thisMonth, $thisYear) {
            $query->whereMonth('tanggal_kirim_riil', $thisMonth)->whereYear('tanggal_kirim_riil', $thisYear);
        }])->orderByDesc('shipments_count')->take(5)->get();

        $aktivitasTerbaru = Shipment::with(['order.destination', 'truck', 'driver'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view(
            'manager.dashboard',
            compact(
                'pengirimanHariIni',
                'totalPengirimanBulanIni',
                'shipmentSelesai',
                'shipmentTerlambat',
                'totalBiayaOperasional',
                'totalPendapatan',
                'topDrivers',
                'topTrucks',
                'aktivitasTerbaru'
            )
        );
    }
}
