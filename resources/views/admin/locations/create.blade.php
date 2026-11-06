@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">
        Tambah Lokasi
    </h1>

    <div class="card shadow">

        <div class="card-body">

            <form action="{{ route('locations.store') }}"
                  method="POST">

                @csrf

                <div class="form-group">
                    <label>Nama Lokasi <small class="text-muted">(Contoh: Gudang Cabang Madiun, Hub Ngawi 1)</small></label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           placeholder="Masukkan nama entitas fisik gudang spesifik..."
                           required>
                </div>

                <div id="map"
                     style="height:500px;"
                     class="mb-3">
                </div>

                <div class="form-group">
                    <label>Latitude</label>

                    <input type="text"
                           id="lintang"
                           name="lintang"
                           class="form-control"
                           readonly
                           required>
                </div>

                <div class="form-group">
                    <label>Longitude</label>

                    <input type="text"
                           id="bujur"
                           name="bujur"
                           class="form-control"
                           readonly
                           required>
                </div>

                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <textarea id="alamat_lengkap"
                              name="alamat_lengkap"
                              class="form-control"
                              rows="3"
                              placeholder="Alamat otomatis terisi saat peta diklik..."></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Biaya Tol Standard (Rp)</label>
                        <input type="number" 
                               name="biaya_tol" 
                               class="form-control" 
                               placeholder="Contoh: 50000" 
                               required min="0" value="0">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Biaya Bongkar Muat Standard (Rp)</label>
                        <input type="number" 
                               name="biaya_bongkar_muat" 
                               class="form-control" 
                               placeholder="Contoh: 100000" 
                               required min="0" value="0">
                    </div>
                </div>

                <button class="btn btn-primary">
                    Simpan
                </button>

            </form>

        </div>

    </div>

</div>

<script>

var map = L.map('map').setView(
    [-7.2575,112.7521],
    8
);

L.tileLayer(
'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
{
    maxZoom:19
}
).addTo(map);

var marker;

map.on('click', function(e){

    if(marker){
        map.removeLayer(marker);
    }

    marker = L.marker(e.latlng).addTo(map);

    const lat = e.latlng.lat;
    const lng = e.latlng.lng;

    document.getElementById('lintang').value = lat;
    document.getElementById('bujur').value = lng;

    const alamatField = document.getElementById('alamat_lengkap');
    alamatField.value = "Sedang mencari alamat...";

    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
        .then(response => {
            if (!response.ok) {
                throw new Error("API Nominatim bermasalah atau limit tercapai");
            }
            return response.json();
        })
        .then(data => {
            if (data && data.display_name) {
                alamatField.value = data.display_name;
            } else {
                alamatField.value = "Alamat tidak ditemukan pada koordinat ini, silakan isi manual.";
            }
        })
        .catch(error => {
            console.error("Error Fetch Nominatim:", error);
            alamatField.value = "Gagal mengambil alamat otomatis, silakan isi manual.";
        });
});

</script>

@endsection