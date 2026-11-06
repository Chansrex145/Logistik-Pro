@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <h1 class="h3 mb-4">
            Data Driver
        </h1>

        <a href="{{ route('driver.create') }}" class="btn btn-primary mb-3">
            Tambah Driver
        </a>

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Driver</th>
                    <th>Nomor Telepon</th>
                    <th>Tarif Harian</th>
                    <th>Status</th>
                    <th>Dibuat Oleh</th>
                    <th width="200">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($drivers as $driver)
                    <tr>

                        <td>
                            {{ $driver->iddrivers }}
                        </td>

                        <td>
                            {{ $driver->nama }}
                        </td>

                        <td>
                            {{ $driver->nomor_telepon }}
                        </td>

                        <td>
                            Rp {{ number_format($driver->tarif_harian, 0, ',', '.') }}
                        </td>

                        <td>

                            @if ($driver->status == 'tersedia')
                                <span class="badge badge-success">
                                    Tersedia
                                </span>
                            @elseif($driver->status == 'digunakan')
                                <span class="badge badge-danger">
                                    Digunakan
                                </span>
                            @else
                                <span class="badge badge-secondary">
                                    {{ $driver->status }}
                                </span>
                            @endif

                        </td>
                        
                        <td>
                            {{ $driver->user->name ?? 'System' }}
                        </td>

                        <td>

                            <a href="{{ route('driver.edit', $driver->iddrivers) }}" class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('driver.destroy', $driver->iddrivers) }}" method="POST"
                                style="display:inline">

                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus data?')">
                                    Hapus
                                </button>

                            </form>

                        </td>

                    </tr>
                @endforeach

            </tbody>

        </table>

    </div>
@endsection
