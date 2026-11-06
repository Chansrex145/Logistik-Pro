@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <h1 class="h3 mb-4">

            Laporan Shipment

        </h1>

        <div class="card shadow">

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>ID Shipment</th>
                            <th>Order</th>
                            <th>Truck</th>
                            <th>Driver</th>
                            <th>Jarak (KM)</th>
                            <th>Total Berat</th>
                            <th>Total Biaya</th>
                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($shipments as $shipment)
                            <tr>

                                <td>
                                    {{ $shipment->idshipment }}
                                </td>

                                <td>
                                    #{{ $shipment->orders_idorders }}
                                </td>

                                <td>
                                    {{ $shipment->truck->nama_truck ?? '-' }}
                                </td>

                                <td>
                                    {{ $shipment->driver->nama ?? '-' }}
                                </td>

                                <td>
                                    {{ $shipment->jarak_km }}
                                </td>

                                <td>
                                    {{ $shipment->total_berat }}
                                </td>

                                <td>

                                    Rp
                                    {{ number_format($shipment->total_biaya, 0, ',', '.') }}

                                </td>

                                <td>
                                    {{ $shipment->status_pengiriman }}
                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>
@endsection
