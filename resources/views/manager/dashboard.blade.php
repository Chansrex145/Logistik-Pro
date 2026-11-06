@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard Manager</h1>
            <a href="{{ route('manager.management.pdf') }}" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">
                <i class="fas fa-download fa-sm text-white-50"></i> Export Laporan Manajemen PDF
            </a>
        </div>

        <!-- Baris 1: 4 Small Cards -->
        <div class="row">
            <!-- Pengiriman Hari Ini -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Pengiriman Hari Ini
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $pengirimanHariIni }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Pengiriman Bulan Ini -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Pengiriman (Bulan Ini)
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $totalPengirimanBulanIni }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-cubes fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipment Selesai -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Shipment Selesai (Bulan Ini)
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $shipmentSelesai }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-double fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipment Terlambat/Bermasalah -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Shipment Terlambat (Warning)
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $shipmentTerlambat }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Baris 2: Financial Cards -->
        <div class="row">
            <!-- Total Pendapatan -->
            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Pendapatan (Estimasi Bulan Ini)
                                </div>
                                <div class="h3 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-hand-holding-usd fa-3x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Biaya Operasional -->
            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Total Biaya Operasional (Bulan Ini)
                                </div>
                                <div class="h3 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($totalBiayaOperasional, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-file-invoice-dollar fa-3x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Baris 3: Top Performer (Driver & Truck) -->
        <div class="row">
            <!-- Top 5 Driver Teraktif -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Top 5 Driver Teraktif (Bulan Ini)</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Nama Driver</th>
                                        <th>Status</th>
                                        <th>Jumlah Trip</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($topDrivers as $driverData)
                                        <tr>
                                            <td>{{ $driverData->driver->nama ?? '-' }}</td>
                                            <td>{{ $driverData->driver->status ?? '-' }}</td>
                                            <td><span class="badge badge-success">{{ $driverData->total_trip }} Trip</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Belum ada data driver.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top 5 Truck Terbanyak -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">Top 5 Truck Terbanyak Digunakan (Bulan Ini)</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Plat Nomor</th>
                                        <th>Jenis Truck</th>
                                        <th>Jumlah Trip</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($topTrucks as $truckData)
                                        <tr>
                                            <td>{{ $truckData->truck->plat_nomor ?? '-' }}</td>
                                            <td>{{ $truckData->truck->nama_truck ?? '-' }}</td>
                                            <td><span class="badge badge-info">{{ $truckData->total_trip }} Trip</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Belum ada data truck.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Baris 4: Ringkasan Aktivitas Pengiriman -->
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-secondary">Ringkasan Aktivitas Pengiriman Terbaru</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Waktu Update</th>
                                        <th>No. Resi</th>
                                        <th>Tujuan</th>
                                        <th>Driver/Truck</th>
                                        <th>Status Pengiriman</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($aktivitasTerbaru as $aktivitas)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($aktivitas->updated_at)->format('d M Y H:i') }}</td>
                                            <td>SHP-{{ sprintf('%04d', $aktivitas->idshipment) }}</td>
                                            <td>{{ $aktivitas->order->destination->name ?? '-' }}</td>
                                            <td>{{ $aktivitas->driver->nama ?? '-' }} / {{ $aktivitas->truck->plat_nomor ?? '-' }}</td>
                                            <td>
                                                @if($aktivitas->status_pengiriman == 'menunggu')
                                                    <span class="badge badge-warning">Menunggu</span>
                                                @elseif($aktivitas->status_pengiriman == 'dalam_perjalanan')
                                                    <span class="badge badge-info">Berjalan</span>
                                                @else
                                                    <span class="badge badge-success">Selesai</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Belum ada aktivitas.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
