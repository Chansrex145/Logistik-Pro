@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h1 class="h3 mb-4">
        Edit Driver
    </h1>

    <form action="{{ route('driver.update',$driver->iddrivers) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="mb-3">

            <label>Nama Driver</label>

            <input type="text"
                   name="nama"
                   class="form-control"
                   value="{{ $driver->nama }}"
                   required>

        </div>

        <div class="mb-3">

            <label>Nomor Telepon</label>

            <input type="text"
                   name="nomor_telepon"
                   class="form-control"
                   value="{{ $driver->nomor_telepon }}"
                   required>

        </div>

        <div class="mb-3">

            <label>Tarif Harian</label>

            <input type="number"
                   name="tarif_harian"
                   class="form-control"
                   value="{{ $driver->tarif_harian }}"
                   required>

        </div>

        <div class="mb-3">
                    <label>Status</label>

                    <select name="status"
                            class="form-control">

                        <option value="tersedia"
                            {{ $driver->status == 'tersedia' ? 'selected' : '' }}> 
                            tersedia
                        </option>

                        <option value="digunakan"
                            {{ $driver->status == 'digunakan' ? 'selected' : '' }}>
                            digunakan                        
                        </option>

                        <option value="maintenance"
                            {{ $driver->status == 'maintenance' ? 'selected' : '' }}>
                            maintenance                         
                        </option>

                    </select>
                </div>

        <button class="btn btn-success">
            Update
        </button>

        <a href="{{ route('driver.index') }}"
           class="btn btn-secondary">
            Kembali
        </a>

    </form>

</div>

@endsection