@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h1 class="h3 mb-4">
        Tambah Driver
    </h1>

    <form action="{{ route('driver.store') }}"
          method="POST">

        @csrf

        <div class="mb-3">

            <label>Nama Driver</label>

            <input type="text"
                   name="nama"
                   class="form-control"
                   required>

        </div>

        <div class="mb-3">

            <label>Nomor Telepon</label>

            <input type="text"
                   name="nomor_telepon"
                   class="form-control"
                   required>

        </div>

        <div class="mb-3">

            <label>Tarif Harian</label>

            <input type="number"
                   name="tarif_harian"
                   class="form-control"
                   required>

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

        <button class="btn btn-success">
            Simpan
        </button>

        <a href="{{ route('driver.index') }}"
           class="btn btn-secondary">
            Kembali
        </a>

    </form>

</div>

@endsection