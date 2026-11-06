@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <h1 class="h3 mb-4">

            Laporan Biaya

        </h1>

        <div class="row">

            <div class="col-md-3">

                <div class="card border-left-primary shadow mb-4">

                    <div class="card-body">

                        <h6>Total Shipment</h6>

                        <h3>
                            {{ $totalShipment }}
                        </h3>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border-left-success shadow mb-4">

                    <div class="card-body">

                        <h6>Total Biaya</h6>

                        <h5>

                            Rp
                            {{ number_format($totalBiaya, 0, ',', '.') }}

                        </h5>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border-left-info shadow mb-4">

                    <div class="card-body">

                        <h6>Rata-rata Biaya</h6>

                        <h5>

                            Rp
                            {{ number_format($rataBiaya, 0, ',', '.') }}

                        </h5>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border-left-danger shadow mb-4">

                    <div class="card-body">

                        <h6>Biaya Tertinggi</h6>

                        <h5>

                            Rp
                            {{ number_format($maxBiaya, 0, ',', '.') }}

                        </h5>

                    </div>

                </div>

            </div>

        </div>

        <div class="card shadow">

            <div class="card-header">

                Detail Biaya Shipment

            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>ID Shipment</th>
                            <th>Truck</th>
                            <th>Driver</th>
                            <th>Total Biaya</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($shipments as $shipment)
                            <tr>

                                <td>
                                    {{ $shipment->idshipment }}
                                </td>

                                <td>
                                    {{ $shipment->truck->nama_truck ?? '-' }}
                                </td>

                                <td>
                                    {{ $shipment->driver->nama ?? '-' }}
                                </td>

                                <td>

                                    Rp
                                    {{ number_format($shipment->total_biaya, 0, ',', '.') }}

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>
@endsection
