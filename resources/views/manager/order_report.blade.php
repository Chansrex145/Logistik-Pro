@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <h1 class="h3 mb-4">

            Laporan Order

        </h1>
        @if (session('success'))
            <div class="alert alert-success">

                {{ session('success') }}

            </div>
        @endif

        <div class="card shadow">

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>ID Order</th>
                            <th>Customer</th>
                            <th>Tujuan</th>
                            <th>Tanggal Pesan</th>
                            <th>Estimasi Tiba</th>
                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($orders as $order)
                            <tr>

                                <td>
                                    {{ $order->idorders }}
                                </td>

                                <td>
                                    {{ $order->customer->nama ?? '-' }}
                                </td>

                                <td>
                                    {{ $order->destination->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $order->tanggal_pesanan }}
                                </td>

                                <td>
                                    {{ $order->estimasi_tiba }}
                                </td>

                                <td>
                                    {{ $order->status_order }}
                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>
@endsection
