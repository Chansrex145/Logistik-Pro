@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">
        Edit Lokasi
    </h1>

    <div class="card shadow">

        <div class="card-body">

            <form action="{{ route('locations.update',
            $location->idlocations) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Nama Lokasi <small class="text-muted">(Contoh: Gudang Cabang Madiun, Hub Ngawi 1)</small></label>

                    <input type="text"
                           name="name"
                           value="{{ $location->name }}"
                           placeholder="Masukkan nama entitas fisik gudang spesifik..."
                           class="form-control">
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
                           value="{{ $location->lintang }}"
                           class="form-control"
                           readonly>
                </div>

                <div class="form-group">
                    <label>Longitude</label>

                    <input type="text"
                           id="bujur"
                           name="bujur"
                           value="{{ $location->bujur }}"
                           class="form-control"
                           readonly>
                </div>

                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <textarea id="alamat_lengkap"
                              name="alamat_lengkap"
                              class="form-control"
                              rows="3"
                              placeholder="Alamat otomatis terisi saat peta diklik...">{{ $location->alamat_lengkap ?? '' }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Biaya Tol Standard (Rp)</label>
                        <input type="number" 
                               name="biaya_tol" 
                               class="form-control" 
                               value="{{ $location->biaya_tol ?? 0 }}"
                               placeholder="Contoh: 50000" 
                               required min="0">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Biaya Bongkar Muat Standard (Rp)</label>
                        <input type="number" 
                               name="biaya_bongkar_muat" 
                               class="form-control" 
                               value="{{ $location->biaya_bongkar_muat ?? 0 }}"
                               placeholder="Contoh: 100000" 
                               required min="0">
                    </div>
                </div>

                <button class="btn btn-warning">
                    Update
                </button>

            </form>

        </div>

    </div>

</div>

<script>

var lat =
{{ $location->lintang }};

var lng =
{{ $location->bujur }};

var map = L.map('map')
.setView([lat,lng],10);

L.tileLayer(
'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
{
    maxZoom:19
}
).addTo(map);

var marker = L.marker([lat,lng])
.addTo(map);

map.on('click', function(e){

    marker.setLatLng(e.latlng);

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