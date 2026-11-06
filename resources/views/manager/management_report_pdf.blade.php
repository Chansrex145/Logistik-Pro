<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">

    <style>
        body {
            font-family: Arial;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 5px;
        }

        h2,
        h3 {
            margin-top: 20px;
        }
    </style>

</head>

<body>

    <h1>
        LAPORAN MANAJEMEN TRANSPORTASI
    </h1>

    <p>
        Tanggal Cetak :
        {{ date('d-m-Y') }}
    </p>

    <hr>

    <h2>
        Ringkasan KPI
    </h2>

    <table>

        <tr>
            <th>Total Order</th>
            <td>{{ $totalOrder }}</td>
        </tr>

        <tr>
            <th>Total Shipment</th>
            <td>{{ $totalShipment }}</td>
        </tr>

        <tr>
            <th>Total Driver</th>
            <td>{{ $totalDriver }}</td>
        </tr>

        <tr>
            <th>Total Truck</th>
            <td>{{ $totalTruck }}</td>
        </tr>

        <tr>
            <th>Total Biaya</th>
            <td>
                Rp {{ number_format($totalBiaya, 0, ',', '.') }}
            </td>
        </tr>

    </table>

    <h2>
        Laporan Order
    </h2>

    <table>

        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Tujuan</th>
            <th>Status</th>
        </tr>

        @foreach ($orders as $order)
            <tr>

                <td>{{ $order->idorders }}</td>

                <td>
                    {{ $order->customer->nama ?? '-' }}
                </td>

                <td>
                    {{ $order->destination->name ?? '-' }}
                </td>

                <td>
                    {{ $order->status_order }}
                </td>

            </tr>
        @endforeach

    </table>

    <h2>
        Laporan Shipment
    </h2>

    <table>

        <tr>
            <th>ID</th>
            <th>Truck</th>
            <th>Driver</th>
            <th>Biaya</th>
        </tr>

        @foreach ($shipments as $shipment)
            <tr>

                <td>{{ $shipment->idshipment }}</td>

                <td>
                    {{ $shipment->truck->nama_truck ?? '-' }}
                </td>

                <td>
                    {{ $shipment->driver->nama ?? '-' }}
                </td>

                <td>
                    Rp {{ number_format($shipment->total_biaya, 0, ',', '.') }}
                </td>

            </tr>
        @endforeach

    </table>

</body>

</html>
