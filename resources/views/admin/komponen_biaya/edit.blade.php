@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h1 class="h3 mb-4">
        Edit Komponen Biaya
    </h1>

    <form action="{{ route('komponen-biaya.update',$data->idkomponen_biaya_operasional) }}"
      method="POST">

        @csrf
        @method('PUT')

        <div class="mb-3">

            <label>Nama Komponen</label>

            <input type="text"
                   name="nama_komponen"
                   class="form-control"
                   value="{{ $data->nama_komponen }}" required>

        </div>

        <div class="mb-3">

            <label>Nilai Biaya</label>

            <input type="number"
                   name="cost_value"
                   class="form-control"
                   value="{{ $data->cost_value }}" required>

        </div>

        <div class="mb-3">

            <label>Keterangan</label>

            <textarea name="keterangan"
                      class="form-control" required>{{ $data->keterangan }}</textarea>

        </div>

        <button class="btn btn-primary">
            Simpan
        </button>

    </form>

</div>

@endsection