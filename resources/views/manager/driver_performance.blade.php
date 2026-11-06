@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <h1 class="h3 mb-4">

            Driver Performance

        </h1>

        <div class="card shadow">

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>Ranking</th>
                            <th>Nama Driver</th>
                            <th>Nomor Telepon</th>
                            <th>Total Shipment</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($drivers as $index => $driver)
                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    {{ $driver->nama }}
                                </td>

                                <td>
                                    {{ $driver->nomor_telepon }}
                                </td>

                                <td>

                                    {{ $driver->shipments_count }}

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>
@endsection
