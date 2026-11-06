@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">
        Tambah Truck
    </h1>

    <div class="card shadow">

        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('truck.store') }}"
                  method="POST">

                @csrf

                <div class="mb-3"> 
                    <label>Nama Truck</label>

                    <input type="text"
                           name="nama_truck"
                           class="form-control">
                </div>

                <div class="mb-3">
                    <label>Plat Nomor</label>

                    <input type="text"
                           name="plat_nomor"
                           class="form-control">
                </div>

                <div class="mb-3">
                    <label>Kapasitas Berat</label>

                    <input type="text"
                           name="kapasitas_berat"
                           class="form-control">
                </div>


                <div class="mb-3">
                    <label>Tarif per KM</label>

                    <input type="number"
                           step="0.01"
                           name="tarif_per_km"
                           class="form-control">
                </div>

                <div class="mb-3">
                    <label>Status</label>

                    <select name="status"
                            class="form-control">

                        <option value="tersedia">
                            tersedia
                        </option>

                        <option value="digunakan">
                            digunakan
                        </option>

                        <option value="maintenance">
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