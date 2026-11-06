@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">
        Data Lokasi
    </h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('locations.create') }}"
       class="btn btn-primary mb-3">

        Tambah Data

    </a>

    <div class="card shadow mb-4">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>No</th>
                            <th>Nama Lokasi</th>
                            <th>Alamat Lengkap</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($locations as $index => $location)
                        <!-- pakai for else agar jika data lokasi tidak ada, maka akan menampilkan tulisan "Data belum tersedia" tepat di tengah tabel -->

                        <tr>

                            <td>{{ $index + 1 }}</td> 
                            <!-- agar nomor urut yang ditampilkan dimulai dari angka 1, bukan dari 0 -->


                            <td>
                                {{ $location->name }}
                            </td>
                            <td>
                                {{ $location->alamat_lengkap ?? '-' }}
                            </td>

                            <td>
                                {{ $location->lintang }}
                            </td>

                            <td>
                                {{ $location->bujur }}
                            </td>

                            <td>

                                <a href="{{ route('locations.edit',
                                $location->idlocations) }}"
                                class="btn btn-warning btn-sm">

                                    Edit

                                </a>

                                <form action="{{ route('locations.destroy',
                                $location->idlocations) }}"
                                method="POST"
                                style="display:inline;">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin hapus data?')">

                                        Hapus

                                    </button>

                                </form>

                            </td>

                        </tr>
                        
                        @empty

                        <tr>

                            <td colspan="6"
                                class="text-center">

                                Data belum tersedia

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection