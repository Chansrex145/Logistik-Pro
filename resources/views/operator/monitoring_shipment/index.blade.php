@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h1 class="h3 mb-4">
        Monitoring Shipment
    </h1>

    <table class="table table-bordered">

        <thead>
            <tr>

                <th>ID</th>
                <th>Customer</th>
                <th>Tujuan</th>
                <th>Driver</th>
                <th>Truck</th>
                <th>Status</th>

            </tr>
        </thead>

        <tbody>

        @foreach($shipments as $shipment)

            <tr>

                <td>
                    {{ $shipment->idshipment }}
                </td>

                <td>
                    {{ $shipment->order->customer->nama }}
                </td>

                <td>
                    {{ $shipment->order->destination->name }}
                </td>

                <td>
                    {{ $shipment->driver->nama }}
                </td>

                <td>
                    {{ $shipment->truck->nama_truck }}
                </td>

                <td>

                    @if($shipment->status_pengiriman=='menunggu')

                        <span class="badge bg-warning">
                            Menunggu
                        </span>

                    @elseif($shipment->status_pengiriman=='dikirim')

                        <span class="badge bg-primary">
                            Dikirim
                        </span>

                    @else

                        <span class="badge bg-success">
                            Selesai
                        </span>

                    @endif

                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

</div>

@endsection