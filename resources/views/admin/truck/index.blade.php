@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">
        Data Truck
    </h1>

    <a href="{{ route('truck.create') }}"
       class="btn btn-primary mb-3">
        Tambah Truck
    </a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Truck</th>
                            <th>Plat Nomor</th>
                            <th>Kapasitas</th>
                            <th>Status</th>
                            <th>Dibuat Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($truck as $t)

                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $t->nama_truck }}</td>

                            <td>{{ $t->plat_nomor }}</td>

                            <td>{{ $t->kapasitas_berat }}</td>

                            <td>{{ $t->status }}</td>

                            <td>{{ $t->user->name ?? 'System' }}</td>

                            <td>

                                <a href="{{ route('truck.edit', $t->idtruck) }}"
                                   class="btn btn-warning btn-sm">
                                   Edit
                                </a>

                                <form action="{{ route('truck.destroy', $t->idtruck) }}"
                                      method="POST"
                                      style="display:inline;">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm">
                                        Hapus
                                    </button>

                                </form>

                            </td>
                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection