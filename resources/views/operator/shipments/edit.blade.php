@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Update Status Shipment (Surat Jalan & Konfirmasi)</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('shipments.update', $shipment->idshipment) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Status Pengiriman</label>
                    <select name="status_pengiriman" id="status_pengiriman" class="form-control" required>
                        <option value="menunggu" {{ $shipment->status_pengiriman == 'menunggu' ? 'selected' : '' }}>Menunggu (Belum Berangkat)</option>
                        <option value="dalam_perjalanan" {{ $shipment->status_pengiriman == 'dalam_perjalanan' ? 'selected' : '' }}>Dalam Perjalanan (Surat Jalan Dibuat)</option>
                        <option value="selesai" {{ $shipment->status_pengiriman == 'selesai' ? 'selected' : '' }}>Selesai (Diterima)</option>
                        <option value="retur" {{ $shipment->status_pengiriman == 'retur' ? 'selected' : '' }}>Retur / Ditolak</option>
                        <option value="batal" {{ $shipment->status_pengiriman == 'batal' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <div class="form-group" id="group_tanggal_kirim_riil" style="display: none;">
                    <label>Tanggal Kirim Riil (Berangkat)</label>
                    <input type="date" name="tanggal_kirim_riil" id="tanggal_kirim_riil" class="form-control" value="{{ $shipment->tanggal_kirim_riil }}">
                    <small class="text-muted">Diisi ketika armada benar-benar berangkat (Surat Jalan).</small>
                </div>

                <div class="form-group" id="group_tanggal_terima" style="display: none;">
                    <label>Tanggal Terima (Konfirmasi Penerimaan)</label>
                    <input type="date" name="tanggal_terima" id="tanggal_terima" class="form-control" value="{{ $shipment->tanggal_terima }}">
                    <small class="text-muted">Diisi ketika barang sampai di tujuan / diretur.</small>
                </div>

                <button class="btn btn-success mt-3">Update Status</button>
                <a href="{{ route('shipments.index') }}" class="btn btn-secondary mt-3">Kembali</a>
            </form>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusSelect = document.getElementById('status_pengiriman');
    const groupKirim = document.getElementById('group_tanggal_kirim_riil');
    const inputKirim = document.getElementById('tanggal_kirim_riil');
    
    const groupTerima = document.getElementById('group_tanggal_terima');
    const inputTerima = document.getElementById('tanggal_terima');

    function toggleFields() {
        const val = statusSelect.value;
        
        // Sembunyikan semua dulu
        groupKirim.style.display = 'none';
        groupTerima.style.display = 'none';
        
        if (val === 'dalam_perjalanan') {
            groupKirim.style.display = 'block';
            inputKirim.required = true;
            inputTerima.required = false;
        } else if (val === 'selesai' || val === 'retur') {
            // Kalau selesai atau retur, biasanya harus ada tanggal berangkat dan terima
            groupKirim.style.display = 'block';
            groupTerima.style.display = 'block';
            inputKirim.required = true;
            inputTerima.required = true;
        } else {
            inputKirim.required = false;
            inputTerima.required = false;
        }
    }

    statusSelect.addEventListener('change', toggleFields);
    
    // Panggil saat pertama load
    toggleFields();
});
</script>

@endsection