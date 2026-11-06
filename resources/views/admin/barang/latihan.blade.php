@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <h1 class="h3 mb-4 text-gray-800">
            Data Barang
        </h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- {{-- BUTTON --}} -->
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTmbh">
            <!-- # = mencari elemen berdasarkan id nya-->

            + Tambah Barang

        </button>

        {{-- TABLE --}}
        <div class="card shadow">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered">

                        <thead class="bg-primary text-white">

                            <tr>

                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Berat</th>

                            </tr>

                        </thead>

                        <tbody>
                            <!-- $barang berasal dari function index di controller BarangController-->
                            @foreach ($brg as $item)
                                <tr>
                                    <!--menampilkan nomor urut (mulai dari 1) pada perulangan -->
                                    <td>{{ $loop->iteration }}</td>

                                    <td>{{ $item->nama_barang }}</td>

                                    <td>{{ $item->berat }} Kg</td>

                                    <td>

                                        {{-- EDIT --}}
                                        <button class="btn btn-warning btn-sm" data-toggle="modal"
                                            data-target="#edit{{ $item->idbarang }}">
                                            <!--menentukan modal popup edit mana yang harus dibuka saat tombol "Edit" diklik-->

                                            Edit

                                        </button>

                                        {{-- DELETE --}}
                                        <!--Menentukan ke URL mana data form ini akan dikirim-->
                                        <form action="/latihan/{{ $item->idbarang }}" method="POST" class="d-inline">
                                            <!-- d-inline agar tombol "Hapus" berada sejajar di sebelah tombol "Edit"-->

                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus data?')">

                                                Hapus

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                                {{-- MODAL EDIT --}}
                                <div class="modal fade" id="edit{{ $item->idbarang }}">

                                    <div class="modal-dialog">

                                        <form action="/latihan/{{ $item->idbarang }}" method="POST">
                                            {{-- idbarang karena mengasih tau controller barang mana yang mau diubah menggunakan id barang --}}

                                            @csrf
                                            @method('PUT')

                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h5>Edit Barang</h5>
                                                </div>

                                                <div class="modal-body">

                                                    <input type="text" name="nama_barang" class="form-control mb-2"
                                                        value="{{ $item->nama_barang }}" required>

                                                    <input type="number" name="berat" class="form-control mb-2"
                                                        value="{{ $item->berat }}" required>
                                                    <select name="customers_id" class="form-control" required>

                                                        <option value="">
                                                            -- Pilih Customer --
                                                        </option>

                                                        <!-- $customers berasal dari function index di controller BarangController-->
                                                        @foreach ($customers as $customer)
                                                            <option value="{{ $customer->id }}">

                                                                <!-- Memilih customer untuk barang yang akan ditambahkan -->
                                                                {{ $customer->nama }}

                                                            </option>
                                                        @endforeach

                                                    </select>

                                                </div>

                                                <div class="modal-footer">

                                                    <button class="btn btn-primary">

                                                        Update

                                                    </button>

                                                </div>

                                            </div>

                                        </form>

                                    </div>

                                </div>
                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

    {{-- MODAL TAMBAH --}}
    <div class="modal fade" id="modalTmbh">

        <div class="modal-dialog">

            <form action="/latihan" method="POST">

                @csrf

                <div class="modal-content">

                    <div class="modal-header">
                        <h5>Tambah Barang</h5>
                    </div>

                    <div class="modal-body">

                        <input type="text" name="nama_barang" class="form-control mb-2" placeholder="Nama Barang"
                            required>

                        <input type="number" name="berat" class="form-control mb-2" placeholder="Berat" required>

                        <select name="customers_id" class="form-control" required>

                            <option value="">
                                -- Pilih Customer --
                            </option>

                            <!-- $customers berasal dari function index di controller BarangController-->
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">

                                    <!-- Memilih customer untuk barang yang akan ditambahkan -->
                                    {{ $customer->nama }}

                                </option>
                            @endforeach

                        </select>

                    </div>

                    <div class="modal-footer">

                        <button class="btn btn-primary">

                            Simpan

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>
@endsection
