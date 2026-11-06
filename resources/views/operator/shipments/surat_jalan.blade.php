<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">

    <title>Surat Jalan</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 5px;
        }

        .border {
            border: 1px solid black;
        }

        .text-center {
            text-align: center;
        }

        .mt {
            margin-top: 20px;
        }
    </style>

</head>

<body>

    <h2 class="text-center">

        PT X

    </h2>

    <h3 class="text-center">

        SURAT JALAN

    </h3>

    <hr>

    <table>

        <tr>
            <td width="30%">
                No Shipment
            </td>

            <td>
                :
                {{ $shipment->idshipment }}
            </td>
        </tr>

        <tr>
            <td>
                Tanggal Kirim
            </td>

            <td>
                :
                {{ $shipment->tanggal_kirim }}
            </td>
        </tr>

        <tr>
            <td>
                Estimasi Tiba
            </td>

            <td>
                :
                {{ $shipment->estimasi_tiba }}
            </td>
        </tr>

    </table>

    <br>

    <table class="border">

        <tr>
            <td width="30%">
                Customer
            </td>

            <td>

                {{ $shipment->order->customer->nama ?? '-' }}

            </td>
        </tr>

        <tr>
            <td>
                Driver
            </td>

            <td>

                {{ $shipment->driver->nama }}

            </td>
        </tr>

        <tr>
            <td>
                Truck
            </td>

            <td>

                {{ $shipment->truck->nama_truck }}

                -
                {{ $shipment->truck->plat_nomor }}

            </td>
        </tr>

        <tr>
            <td>
                Total Berat
            </td>

            <td>

                {{ $shipment->total_berat }}

                Kg

            </td>
        </tr>

        <tr>
            <td>
                Jarak
            </td>

            <td>

                {{ $shipment->jarak_km }}

                Km

            </td>
        </tr>

    </table>

    <div class="mt">

        <h4>

            Rute Pengiriman

        </h4>

        <ol>

            @foreach ($shipment->shipmentRoutes as $route)
                <li>

                    {{ $route->location->name }}

                </li>
            @endforeach

        </ol>

    </div>

    <br><br>

    <table>

        <tr>

            <td class="text-center">

                Pengirim

                <br><br><br><br>

                _________________

            </td>

            <td class="text-center">

                Driver

                <br><br><br><br>

                {{ $shipment->driver->nama }}

            </td>

            <td class="text-center">

                Penerima

                <br><br><br><br>

                _________________

            </td>

        </tr>

    </table>

</body>

</html>
