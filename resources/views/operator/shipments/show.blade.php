@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <h1 class="h3 mb-4">

            Detail Shipment #{{ $shipment->idshipment }}

        </h1>

        <!-- Informasi Shipment -->
        <div class="card shadow mb-4">

            <div class="card-header">

                Informasi Shipment

            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <tr>
                        <th width="250">Order</th>
                        <td>#{{ $shipment->orders_idorders }}</td>
                    </tr>

                    <tr>
                        <th>Truck</th>
                        <td>{{ $shipment->truck->nama_truck }}</td>
                    </tr>

                    <tr>
                        <th>Driver</th>
                        <td>{{ $shipment->driver->nama }}</td>
                    </tr>

                    <tr>
                        <th>Total Jarak</th>
                        <td>{{ $shipment->jarak_km }} KM</td>
                    </tr>

                    <tr>
                        <th>Total Berat</th>
                        <td>{{ $shipment->total_berat }} KG</td>
                    </tr>

                    <tr>
                        <th>Total Biaya</th>
                        <td>

                            Rp
                            {{ number_format($shipment->total_biaya, 0, ',', '.') }}

                        </td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td>

                            <span class="badge badge-info">

                                {{ $shipment->status_pengiriman }}

                            </span>

                        </td>
                    </tr>

                </table>

            </div>

        </div>

        <!-- Jalur Dijkstra -->
        <div class="card shadow mb-4">

            <div class="card-header">

                Jalur Hasil Dijkstra

            </div>

            <div class="card-body">

                <table class="table table-bordered text-center">
                    <thead class="bg-light">
                        <tr>
                            <th>Urutan Node</th>
                            <th>Lokasi</th>
                            <th>Jarak dari Titik Sebelumnya</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $prevLocationId = null;
                            $totalNodeJarak = 0;
                        @endphp
                        @foreach (collect($shipment->shipmentRoutes)->sortBy('urutan') as $route)
                            @php
                                $jarakNode = 0;
                                if ($prevLocationId) {
                                    $edge = \App\Models\Route::where('source_id', $prevLocationId)
                                        ->where('destination_id', $route->locations_idlocations)
                                        ->first();
                                    if ($edge) {
                                        $jarakNode = $edge->distance;
                                        $totalNodeJarak += $jarakNode;
                                    }
                                }
                                $prevLocationId = $route->locations_idlocations;
                            @endphp
                            <tr>
                                <td>{{ $route->urutan }}</td>
                                <td class="text-left font-weight-bold">{{ $route->location_name }}</td>
                                <td>
                                    @if ($route->urutan == 1)
                                        <span class="text-muted">📍 Titik Keberangkatan</span>
                                    @else
                                        <span class="text-primary">+ {{ $jarakNode }} KM</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-light">
                            <th colspan="2" class="text-right">Total Jarak Akumulasi:</th>
                            <th class="text-success">{{ $totalNodeJarak }} KM</th>
                        </tr>
                    </tfoot>
                </table>

            </div>

        </div>

        <!-- Barang Yang Dikirim -->
        <div class="card shadow mb-4">

            <div class="card-header">

                Barang Yang Dikirim

            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>Barang</th>
                            <th>Jumlah</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($shipment->shipmentItems as $item)
                            <tr>

                                <td>

                                    {{ $item->barang->nama_barang }}

                                </td>

                                <td>

                                    {{ $item->jumlah }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="2">

                                    Belum ada data shipment item

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        <!-- Detail Biaya -->
        <div class="card shadow mb-4">

            <div class="card-header">

                Detail Komponen Biaya

            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>Komponen</th>
                            <th>Biaya</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($shipment->shipmentCosts
                                                                            as $cost)
                            <tr>

                                <td>

                                    {{ $cost->komponen->nama_komponen }}

                                </td>

                                <td>

                                    Rp
                                    {{ number_format($cost->cost_value, 0, ',', '.') }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="2">

                                    Belum ada data shipment cost

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        <a href="{{ route('shipment.map', $shipment->idshipment) }}" class="btn btn-success">

            Lihat Peta

        </a>

        <a href="{{ route('shipment.suratjalan', $shipment->idshipment) }}" class="btn btn-danger">

            Cetak Surat Jalan

        </a>

        <a href="{{ route('shipments.index') }}" class="btn btn-secondary">

            Kembali

        </a>

    </div>
@endsection
