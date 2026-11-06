@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">
        Tambah Truck
    </h1>

    <div class="card shadow">

        <div class="card-body">

           <form action="{{ route('truck.update', $truck->idtruck) }}"
                method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Nama Truck</label>

                    <input type="text"
                           name="nama_truck"
                           class="form-control"
                           value="{{ $truck->nama_truck }}">
                </div>

                <div class="mb-3">
                    <label>Plat Nomor</label>

                    <input type="text"
                           name="plat_nomor"
                           class="form-control"
                           value="{{ $truck->plat_nomor }}">
                </div>

                <div class="mb-3">
                    <label>Kapasitas Berat</label>

                    <input type="number"
                           name="kapasitas_berat"
                           class="form-control"
                           value="{{ $truck->kapasitas_berat }}">
                </div>

                <div class="mb-3">
                    <label>Tarif per KM</label>

                    <input type="number"
                           step="0.01"
                           name="tarif_per_km"
                           class="form-control"
                           value="{{ $truck->tarif_per_km }}">
                </div>

                <div class="mb-3">
                    <label>Status</label>

                    <select name="status"
                            class="form-control">

                        <option value="tersedia"
                            {{ $truck->status == 'tersedia' ? 'selected' : '' }}>
                            tersedia
                        </option>

                        <option value="digunakan"
                            {{ $truck->status == 'digunakan' ? 'selected' : '' }}>
                            digunakan                        
                        </option>

                        <option value="maintenance"
                            {{ $truck->status == 'maintenance' ? 'selected' : '' }}>
                            maintenance                         
                        </option>

                    </select>
                </div>

                <button class="btn btn-primary">
                    Simpan
                </button>

            </form>

        </div>

    </div>

</div>

@endsection