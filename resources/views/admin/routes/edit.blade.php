@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">
        Ubah Rute Jalan (Bobot Dijkstra)
    </h1>

    <div class="card shadow">
        <div class="card-body">
            
            {{-- Form untuk mengupdate data rute jalan --}}
            <form action="{{ route('routes.update', $route->idroutes) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label class="font-weight-bold">Lokasi Asal</label>
                    <select name="source_id" class="form-control" required>
                        @foreach($locations as $location)
                            <option value="{{ $location->idlocations }}" 
                                {{ $route->source_id == $location->idlocations ? 'selected' : '' }}>
                                {{ $location->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold">Lokasi Tujuan</label>
                    <select name="destination_id" class="form-control" required>
                        @foreach($locations as $location)
                            <option value="{{ $location->idlocations }}" 
                                {{ $route->destination_id == $location->idlocations ? 'selected' : '' }}>
                                {{ $location->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-warning">
                    Update
                </button>

                <a href="{{ route('routes.index') }}" class="btn btn-secondary">
                    Kembali
                </a>
            </form>

        </div>
    </div>

</div>
@endsection