@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <h1 class="h3 mb-4 text-gray-800">
            Data Shipment (Pengiriman)
        </h1>

        <div class="mb-3">
            <a href="{{ route('shipments.create_integrated') }}" class="btn btn-primary">
                Buat Order & Shipment Terintegrasi
            </a>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>ID</th>
                                <th>Order</th>
                                <th>Truck</th>
                                <th>Driver</th>
                                <th>Jarak</th>
                                <th>Total Berat</th>
                                <th>Total Biaya</th>
                                <th>Status</th>
                                <th>Dibuat Oleh</th>
                                <th width="280">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shipments as $shipment)
                                <tr>
                                    <td>{{ $shipment->idshipment }}</td>

                                    <td>#{{ $shipment->orders_idorders }}</td>

                                    <td>{{ $shipment->truck->nama_truck }}</td>

                                    <td>{{ $shipment->driver->nama }}</td>

                                    <td>{{ $shipment->jarak_km }} Km</td>

                                    <td>{{ number_format($shipment->total_berat, 0, ',', '.') }} Kg</td>

                                    <td>Rp {{ number_format($shipment->total_biaya, 0, ',', '.') }}</td>

                                    <td>
                                        @if ($shipment->status_pengiriman == 'menunggu')
                                            <span class="badge badge-warning">Menunggu</span>
                                        @elseif($shipment->status_pengiriman == 'dalam_perjalanan')
                                            <span class="badge badge-info">Dalam Perjalanan</span>
                                        @elseif($shipment->status_pengiriman == 'selesai')
                                            <span class="badge badge-success">Selesai</span>
                                        @elseif($shipment->status_pengiriman == 'batal')
                                            <span class="badge badge-danger">Batal</span>
                                        @else
                                            <span class="badge badge-secondary">{{ $shipment->status_pengiriman }}</span>
                                        @endif
                                    </td>

                                    <td>{{ $shipment->user->name ?? 'System' }}</td>

                                    <td>
                                        {{-- Mengarah ke halaman detail rute (show.blade.php) --}}
                                        <a href="{{ route('shipments.show', $shipment->idshipment) }}"
                                            class="btn btn-info btn-sm">
                                            Detail Rute
                                        </a>

                                        {{-- Mengarah ke form update status pengiriman (edit.blade.php) --}}
                                        <a href="{{ route('shipments.edit', $shipment->idshipment) }}"
                                            class="btn btn-warning btn-sm">
                                            Update Status
                                        </a>

                                        {{-- Form Hapus dengan proteksi csrf dan method spoofing DELETE --}}
                                        <form action="{{ route('shipments.destroy', $shipment->idshipment) }}"
                                            method="POST" class="d-inline"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data shipment ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
