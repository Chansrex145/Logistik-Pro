@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Cek Data Order Customer</h1>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Pilih Customer / PT</h6>
            </div>
            <div class="card-body">

                <form action="{{ route('orders.by_customer') }}" method="get">
                    <div class="form-group">
                        <label>Customer</label>
                        <!-- dikasih name agar form HTML mengirim data berdasarkan name bukan id -->
                        <select name="customers_id" id="customer_select" class="form-control select2">
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{-- Apakah $selectedCustomerId sama dengan $customer->id? --}}
                                    {{ $selectedCustomerId == $customer->id ? 'selected' : '' }}>{{ $customer->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button class="btn btn-primary">Cek Order</button>
                </form>
                @if ($selectedCustomerId)
                    <hr>
                    <h6 class="font-weight-bold text-secondary mb-3">Data Order</h6>

                    @if ($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>No</th>
                                        <th>Id Order</th>
                                        <th>Nama Barang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $order->customer->id }}</td>
                                            @foreach ($order->orderItems as $item)
                                                <td>{{ $item->barang->nama_barang }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning">Belum ada order untuk customer ini.</div>
                    @endif
                @endif
            </div>
        </div>
    </div>


@endsection
