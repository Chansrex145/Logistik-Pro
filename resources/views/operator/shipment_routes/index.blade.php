@extends('layouts.app')

@section('content')

<div class="container-fluid">

<h1 class="h3 mb-4">
    Shipment Routes
</h1>

<table class="table table-bordered">

    <thead>
        <tr>
            <th>ID Shipment</th>
            <th>Total Titik Rute</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>

    @forelse($shipments as $shipment)

        <tr>

            <td>
                {{ $shipment->idshipment }}
            </td>

            <td>
                {{ $shipment->shipmentRoutes->count() }}
            </td>

            <td>

                <a href="{{ route(
                    'shipments.show',
                    $shipment->idshipment
                ) }}"
                class="btn btn-info btn-sm">

                    Detail Rute

                </a>

            </td>

        </tr>

    @empty

        <tr>
            <td colspan="3" class="text-center">
                Belum ada data shipment
            </td>
        </tr>

    @endforelse

    </tbody>

</table>


</div>

@endsection
