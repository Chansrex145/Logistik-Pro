@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">
        Data Routes
    </h1>

    <a href="{{ route('routes.create') }}"
       class="btn btn-primary mb-3">

       Tambah Route

    </a>

    <div class="card shadow mb-4">

        <div class="card-body">

            <table class="table table-bordered">

                <thead>

                    <tr>

                        <th>No</th>
                        <th>Asal</th>
                        <th>Tujuan</th>
                        <th>Jarak (KM)</th>
                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($routes as $index => $route)

                    <tr>

                        <td>
                            {{ $index + 1 }}
                        </td>

                        <td>
                            {{ $route->source->name }}
                        </td>

                        <td>
                            {{ $route->destination->name }}
                        </td>

                        <td>
                            {{ $route->distance }}
                        </td>

                        <td>

                            <a href="{{ route('routes.edit',
                            $route->idroutes) }}"
                            class="btn btn-warning btn-sm">

                                Edit

                            </a>

                            <form
                            action="{{ route('routes.destroy',
                            $route->idroutes) }}"
                            method="POST"
                            style="display:inline;">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin hapus route ini ?')">

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

@endsection