@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <h1 class="h3 mb-4 text-gray-800">
            Tambah Rute Jalan (Bobot Dijkstra)
        </h1>

        <div class="card shadow">
            <div class="card-body">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                {{-- Form untuk mengirim data rute baru --}}
                <form action="{{ route('routes.store') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Lokasi Asal</label>
                        <select name="source_id" class="form-control" required>
                            <option value="">-- Pilih Lokasi Asal --</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->idlocations }}">
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Lokasi Tujuan</label>
                        <select name="destination_id" class="form-control" required>
                            <option value="">-- Pilih Lokasi Tujuan --</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->idlocations }}">
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>

                    <a href="{{ route('routes.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                </form>

            </div>
        </div>

    </div>
@endsection
