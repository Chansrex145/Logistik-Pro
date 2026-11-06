@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h1 class="h3 mb-4">
        Tambah Komponen Biaya
    </h1>

    <form action="{{ route('komponen-biaya.store') }}"
          method="POST">

        @csrf

        <div class="mb-3">

            <label>Nama Komponen</label>

            <input type="text"
                   name="nama_komponen"
                   class="form-control" required>

        </div>

        <div class="mb-3">

            <label>Nilai Biaya</label>

            <input type="number"
                   name="cost_value"
                   class="form-control" required>

        </div>

        <div class="mb-3">

            <label>Keterangan</label>

            <textarea name="keterangan"
                      class="form-control" required></textarea>

        </div>

        <button class="btn btn-primary">
            Simpan
        </button>

    </form>

</div>

@endsection