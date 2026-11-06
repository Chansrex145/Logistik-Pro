@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <h1 class="h3 mb-4 text-gray-800">
            Dashboard Admin
        </h1>

        <!-- Baris 1: 4 Small Cards -->
        <div class="row">
            <!-- Pesanan Menunggu -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Pesanan Menunggu (Belum Direspon)
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $shipmentPending }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pengiriman Hari Ini -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Jadwal Pengiriman Hari Ini
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $shipmentHariIni }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-truck-loading fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Truck Tersedia -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Truk Tersedia
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $truckTersedia }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-truck fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Driver Tersedia -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Driver Tersedia
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $driverTersedia }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-id-badge fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Tabel Butuh Tindakan -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-danger">Pesanan Butuh Tindakan Segera</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Waktu Pesan</th>
                                        <th>Tujuan</th>
                                        <th>Estimasi Kirim</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($butuhTindakan as $shipment)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($shipment->created_at)->format('d M Y H:i') }}</td>
                                            <td>{{ $shipment->order->destination->name ?? '-' }}</td>
                                            <td>{{ $shipment->order->tanggal_estimasi_kirim }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Semua pesanan sudah direspon.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Jadwal Hari Ini -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Jadwal Pengiriman Hari Ini</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Tujuan</th>
                                        <th>Armada</th>
                                        <th>Supir</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($jadwalHariIni as $jadwal)
                                        <tr>
                                            <td>{{ $jadwal->order->destination->name ?? '-' }}</td>
                                            <td>{{ $jadwal->truck->plat_nomor ?? '-' }}</td>
                                            <td>{{ $jadwal->driver->nama ?? '-' }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $jadwal->status_pengiriman)) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada jadwal pengiriman hari ini.</td>
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
