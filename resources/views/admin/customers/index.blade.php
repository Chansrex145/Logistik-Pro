@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">
        Data Customer
    </h1>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- BUTTON TAMBAH --}}
    <button class="btn btn-primary mb-3"
            data-toggle="modal"
            data-target="#modalTambah">

        + Tambah Customer

    </button>

    {{-- TABLE --}}
    <div class="card shadow mb-4">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead class="bg-primary text-white">

                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>No HP</th>
                            <th>Dibuat Oleh</th>
                            <th width="200">Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($customers as $item)

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $item->nama }}</td>

                            <td>{{ $item->alamat }}</td>

                            <td>{{ $item->no_hp }}</td>

                            <td>{{ $item->user->name ?? 'System' }}</td>

                            <td>

                                {{-- EDIT --}}
                                <button class="btn btn-warning btn-sm"
                                        data-toggle="modal"
                                        data-target="#edit{{ $item->id }}">

                                    Edit

                                </button>

                                {{-- DELETE --}}
                                <form action="/customers/{{ $item->id }}"
                                      method="POST"
                                      class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Hapus data?')">

                                        Hapus

                                    </button>

                                </form>

                            </td>

                        </tr>

                        {{-- MODAL EDIT --}}
                        <div class="modal fade" id="edit{{ $item->id }}">

                            <div class="modal-dialog">

                                <form action="/customers/{{ $item->id }}"
                                      method="POST">

                                    @csrf
                                    @method('PUT')

                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5>Edit Customer</h5>
                                        </div>

                                        <div class="modal-body">

                                            <input type="text"
                                                   name="nama"
                                                   class="form-control mb-2"
                                                   value="{{ $item->nama }}" required>

                                            <input type="text"
                                                   name="alamat"
                                                   class="form-control mb-2"
                                                   value="{{ $item->alamat }}" required>

                                            <input type="text"
                                                   name="no_hp"
                                                   class="form-control"
                                                   value="{{ $item->no_hp }}" required>

                                        </div>

                                        <div class="modal-footer">

                                            <button class="btn btn-primary">

                                                Update

                                            </button>

                                        </div>

                                    </div>

                                </form>

                            </div>

                        </div>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah">

    <div class="modal-dialog">

        <form action="/customers" method="POST">

            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Tambah Customer</h5>
                </div>

                <div class="modal-body">

                    <input type="text"
                           name="nama"
                           class="form-control mb-2"
                           placeholder="Nama" required>

                    <input type="text"
                           name="alamat"
                           class="form-control mb-2"
                           placeholder="Alamat" required>

                    <input type="text"
                           name="no_hp"
                           class="form-control"
                           placeholder="No HP" required>

                </div>

                <div class="modal-footer">

                    <button class="btn btn-primary">

                        Simpan

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection