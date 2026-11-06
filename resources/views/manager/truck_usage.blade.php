@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <h1 class="h3 mb-4">

            Truck Usage

        </h1>

        <div class="card shadow">

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>Ranking</th>
                            <th>Nama Truck</th>
                            <th>Plat Nomor</th>
                            <th>Total Shipment</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($trucks as $index => $truck)
                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    {{ $truck->nama_truck }}
                                </td>

                                <td>
                                    {{ $truck->plat_nomor }}
                                </td>

                                <td>

                                    {{ $truck->shipments_count }}

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>
@endsection
