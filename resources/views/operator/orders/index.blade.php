@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">
        Data Order
    </h1>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>No</th>
                            <th>Customer</th>
                            <th>Tujuan</th>
                            <th>Tanggal Pesan</th>
                            <th>Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Perulangan data order yang dikirim dari OrderController --}}
                        @foreach($orders as $order)
                            <tr>
                                {{-- Menampilkan nomor urut menggunakan $loop->iteration --}}
                                <td>{{ $loop->iteration }}</td>
                                
                                {{-- Mengambil nama customer dari relasi customer --}}
                                <td>{{ $order->customer->nama }}</td>
                                
                                {{-- Mengambil nama lokasi tujuan dari relasi destination --}}
                                <td>{{ $order->destination->name }}</td>
                                
                                <td>{{ $order->tanggal_pesanan }}</td>
                                
                                <td>
                                    @if($order->status_order == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($order->status_order == 'selesai')
                                        <span class="badge badge-success">Selesai</span>
                                    @else
                                        <span class="badge badge-secondary">{{ $order->status_order }}</span>
                                    @endif
                                </td>
                                
                                <td>
                                    {{-- Form Hapus Order dengan Method Spoofing DELETE dan Proteksi CSRF --}}
                                    <form action="{{ route('orders.destroy', $order->idorders) }}" 
                                          method="POST" 
                                          style="display:inline"
                                          onclick="return confirm('Apakah Anda yakin ingin menghapus data order ini?')">
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