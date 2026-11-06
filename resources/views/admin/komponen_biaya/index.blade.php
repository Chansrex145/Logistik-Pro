@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h1 class="h3 mb-4">
        Komponen Biaya Tambahan
    </h1>

    <a href="{{ route('komponen-biaya.create') }}"
       class="btn btn-primary mb-3">

       Tambah Data
    </a>

    <table class="table table-bordered">

        <thead>
            <tr>
                <th>No</th>
                <th>Nama Komponen</th>
                <th>Nilai</th>
                <th>Keterangan</th>
                <th>Dibuat Oleh</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

            @foreach($data as $item)

            <tr>

                <td>{{ $loop->iteration }}</td>

                <td>
                    {{ $item->nama_komponen }}
                </td>

                <td>
                    Rp {{ number_format($item->cost_value) }}
                </td>

                <td>
                    {{ $item->keterangan }}
                </td>

                <td>{{ $item->user->name ?? 'System' }}</td>

                <td>

                    <a href="{{ route('komponen-biaya.edit',$item->idkomponen_biaya_operasional) }}"
                       class="btn btn-warning btn-sm">

                       Edit
                    </a>

                    <form action="{{ route('komponen-biaya.destroy',$item->idkomponen_biaya_operasional) }}"
                          method="POST"
                          style="display:inline">

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

@endsection