@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Buat Order & Surat Jalan (Terintegrasi)</h6>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{--
                PERTAMA: Form Pra-Cek (GET)
                Ketika form ini di-submit, halaman reload dan PHP (controller) memproses:
                  - Cek ketersediaan armada (query database truk & driver)
                  - Kalkulasi ETA menggunakan algoritma Dijkstra
            --}}
                <form method="GET" action="{{ route('shipments.create_integrated') }}"
                    class="mb-4 p-3 bg-light border rounded">
                    <h5 class="text-secondary border-bottom pb-2 mb-3">1. Pilih Customer, Tujuan & Tanggal</h5>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Customer</label>
                            <select name="customers_id" class="form-control" onchange="this.form.submit()">
                                <option value="">-- Pilih Customer --</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" @if ($selectedCustomerId == $customer->id) selected @endif>
                                        {{ $customer->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Tujuan (Lokasi Pengiriman)</label>
                            <select name="destination_id" class="form-control">
                                <option value="">-- Pilih Tujuan --</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->idlocations }}"
                                        @if ($destinationId == $location->idlocations) selected @endif>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Tanggal Estimasi Kirim</label>
                            <input type="date" name="tanggal_estimasi_kirim" class="form-control"
                                value="@if (!empty($tanggalEstimasiKirim)) {{ $tanggalEstimasiKirim }} @endif">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-info">
                        <i class="fa fa-search"></i> Cek Armada & Hitung ETA
                    </button>
                </form>


                {{--
                Hasil Cek Armada — ditampilkan oleh PHP setelah form di atas di-submit.
                $fleetChecked = true jika tanggal sudah diisi dan query sudah dijalankan.
            --}}
                @if ($fleetChecked)
                    @if (count($availableTrucks) > 0 && count($availableDrivers) > 0)
                        <div class="alert alert-success">
                            <i class="fa fa-check-circle"></i>
                            <strong>Armada Tersedia:</strong>
                            {{ count($availableTrucks) }} Truk &amp; {{ count($availableDrivers) }} Driver tersedia
                            pada tanggal
                            <strong>{{ \Carbon\Carbon::parse($tanggalEstimasiKirim)->format('d/m/Y') }}</strong>.
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle"></i>
                            <strong>Peringatan:</strong> Tidak ada armada yang tersedia pada tanggal tersebut.
                            ({{ count($availableTrucks) }} Truk, {{ count($availableDrivers) }} Driver tersedia)
                        </div>
                    @endif
                @endif

                {{--
                Hasil Kalkulasi ETA & Dijkstra — ditampilkan oleh PHP setelah form di-submit.
                $etaData = array berisi hasil kalkulasi, null jika belum/gagal dihitung.
            --}}
                @if ($etaData)
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-header font-weight-bold">
                                    <i class="fa fa-map"></i> Keputusan Algoritma Dijkstra
                                </div>
                                <div class="card-body">
                                    <p class="mb-1 text-muted">Jarak Terpendek:</p>
                                    <h5 class="text-primary font-weight-bold">{{ $etaData['distance'] }} km</h5>

                                    <p class="mb-1 mt-3 text-muted">Rute Terpilih (Optimal):</p>
                                    <div class="alert alert-success p-2" style="font-size: 0.9em;">
                                        {{ $etaData['optimal_path_names'] }}
                                    </div>

                                    {{-- Peta Leaflet — library peta wajib JS, tapi data koordinat dari PHP --}}
                                    <div id="map" style="height: 350px; border-radius: 8px; z-index: 1;"
                                        class="mt-3"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-header font-weight-bold">
                                    <i class="fa fa-money-bill"></i> Rincian Kalkulasi Tarif &amp; ETA
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td>Jarak</td>
                                            <td class="text-right"><strong>{{ $etaData['distance'] }} km</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Estimasi Waktu Perjalanan</td>
                                            <td class="text-right"><strong>{{ $etaData['days'] }} hari</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Biaya Tol (Standard Tujuan)</td>
                                            <td class="text-right">Rp
                                                {{ number_format($etaData['biaya_tol'], 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Biaya Bongkar Muat</td>
                                            <td class="text-right">Rp
                                                {{ number_format($etaData['biaya_bongkar_muat'], 0, ',', '.') }}</td>
                                        </tr>
                                    </table>
                                    <div class="alert alert-info mt-2 p-2" style="font-size: 0.9em;">
                                        <i class="fa fa-clock"></i>
                                        <strong>Estimasi Tiba:</strong>
                                        {{ \Carbon\Carbon::parse($etaData['estimasi_tiba'])->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- LANGKAH-LANGKAH ALGORITMA DIJKSTRA -->
                    @if (!empty($etaData['dijkstra_steps']))
                        <div class="card mb-4">
                            <div class="card-header font-weight-bold bg-dark text-white">
                                <i class="fa fa-code"></i>
                                Cara Kerja Algoritma Dijkstra — Iterasi per Iterasi
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    @foreach ($etaData['dijkstra_steps'] as $step)
                                        <div class="p-3 border-bottom @if ($step['finished']) bg-success-light @endif"
                                            @if ($step['finished']) style="background:#d4edda;" @endif>

                                            <!-- HEADER ITERASI -->
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="badge badge-dark mr-2" style="font-size:0.85em;">
                                                    Iterasi #{{ $step['iteration'] }}
                                                </span>
                                                <strong
                                                    class="@if ($step['finished']) text-success @else text-primary @endif">
                                                    <i class="fa fa-map-marker-alt"></i>
                                                    Kunjungi node: {{ $step['current_node'] }}
                                                </strong>
                                                <span class="ml-2 text-muted" style="font-size:0.85em;">
                                                    (dist = @if ($step['current_dist'] === INF || $step['current_dist'] == '∞')
                                                        ∞
                                                    @else
                                                        {{ $step['current_dist'] }}
                                                    @endif km)
                                                </span>
                                                @if ($step['finished'])
                                                    <span class="badge badge-success ml-2">✓ Tujuan Ditemukan!</span>
                                                @endif
                                            </div>

                                            <div class="row">

                                                {{-- TABEL DIST --}}
                                                <div class="col-md-6">
                                                    <p class="mb-1"><small><strong>Tabel Jarak (dist[]) setelah iterasi
                                                                ini:</strong></small></p>
                                                    <table class="table table-sm table-bordered mb-0"
                                                        style="font-size:0.8em;">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>Node</th>
                                                                <th>Jarak Terbaik</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($step['dist_table'] as $nodeName => $d)
                                                                <tr
                                                                    class="@if ($nodeName === $step['current_node']) table-primary font-weight-bold @endif">
                                                                    <td>{{ $nodeName }}</td>
                                                                    <td>
                                                                        @if ($d === '∞')
                                                                            ∞
                                                                        @else
                                                                            {{ $d }} km
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                                {{-- RELAKSASI TETANGGA --}}
                                                <div class="col-md-6">
                                                    <p class="mb-1"><small><strong>Relaksasi Tetangga:</strong></small>
                                                    </p>
                                                    @if (empty($step['relaxations']))
                                                        <p class="text-muted" style="font-size:0.8em;">Tidak ada tetangga
                                                            yang diperiksa.</p>
                                                    @else
                                                        <table class="table table-sm table-bordered mb-0"
                                                            style="font-size:0.8em;">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>Tetangga</th>
                                                                    <th>Bobot</th>
                                                                    <th>Dist Lama</th>
                                                                    <th>Dist Baru</th>
                                                                    <th>Update?</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($step['relaxations'] as $rel)
                                                                    <tr
                                                                        class="@if ($rel['updated']) table-success @endif">
                                                                        <td>{{ $rel['neighbor'] }}</td>
                                                                        <td>{{ $rel['edge_weight'] }} km</td>
                                                                        <td>
                                                                            @if ($rel['old_dist'] === '∞')
                                                                                ∞
                                                                            @else
                                                                                {{ $rel['old_dist'] }} km
                                                                            @endif
                                                                        </td>
                                                                        <td>{{ $rel['new_dist'] }} km</td>
                                                                        <td>
                                                                            @if ($rel['updated'])
                                                                                <span class="badge badge-success">✓
                                                                                    Diperbarui</span>
                                                                            @else
                                                                                <span class="badge badge-secondary">Tidak
                                                                                    (lebih jauh)
                                                                                </span>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                {{--
                LANGKAH 2: Form Simpan (POST)
                Hanya tampil jika armada tersedia (hasil cek PHP di atas).
                Data dari langkah 1 (tanggal, tujuan, ETA) dikirim ulang sebagai hidden fields.
            --}}
                @if ($fleetChecked && count($availableTrucks) > 0 && count($availableDrivers) > 0)
                    <form action="{{ route('shipments.store_integrated') }}" method="POST">
                        @csrf

                        {{-- Hidden fields: kirim ulang nilai dari form GET ke proses POST --}}
                        <input type="hidden" name="destination_id" value="{{ $destinationId }}">
                        <input type="hidden" name="tanggal_estimasi_kirim" value="{{ $tanggalEstimasiKirim }}">
                        @if ($etaData)
                            <input type="hidden" name="estimasi_tiba" value="{{ $etaData['estimasi_tiba'] }}">
                        @endif

                        <h5 class="text-secondary border-bottom pb-2 mb-3">2. Informasi Pesanan</h5>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Tanggal Pesanan</label>
                                <input type="date" name="tanggal_pesanan" class="form-control" required
                                    value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Customer</label>
                                <select name="customers_id" class="form-control" required>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                            @if ($selectedCustomerId == $customer->id) selected @endif>
                                            {{ $customer->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Tujuan (terpilih)</label>
                            <input type="text" class="form-control"
                                value="{{ optional(collect($locations)->firstWhere('idlocations', $destinationId))->name ?? '-' }}">
                        </div>

                        <h5 class="text-secondary border-bottom pb-2 mt-4 mb-3">3. Item Barang (Order Items)</h5>
                        <div id="items_container">
                            <div class="row item-row mb-2">
                                <div class="col-md-9">
                                    <select name="items[0][barang_idbarang]" class="form-control" required>
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach ($barangs as $barang)
                                            <option value="{{ $barang->idbarang }}">
                                                {{ $barang->nama_barang }} ({{ $barang->berat }} kg)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="items[0][jumlah]" class="form-control"
                                        placeholder="Jumlah" required min="1">
                                </div>
                            </div>
                        </div>

                        <h5 class="text-secondary border-bottom pb-2 mt-4 mb-3">4. Biaya Tambahan Opsional (Surcharge)</h5>
                        <div class="form-group bg-light p-3 border rounded">
                            <label class="font-weight-bold d-block mb-2">Pilih Biaya Tambahan (Jika Ada)</label>
                            <div class="row">
                                @forelse($komponenBiaya as $kb)
                                    <div class="col-md-4 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                id="surcharge_{{ $kb->idkomponen_biaya_operasional }}"
                                                name="komponen_tambahan[]"
                                                value="{{ $kb->idkomponen_biaya_operasional }}">
                                            <label class="custom-control-label"
                                                for="surcharge_{{ $kb->idkomponen_biaya_operasional }}">
                                                {{ $kb->nama_komponen }}<br>
                                                <small class="text-success">+ Rp
                                                    {{ number_format($kb->cost_value, 0, ',', '.') }}</small>
                                            </label>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-muted">Belum ada master data komponen biaya tambahan.</div>
                                @endforelse
                            </div>
                        </div>

                        <h5 class="text-secondary border-bottom pb-2 mt-4 mb-3">5. Pilih Armada</h5>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Pilih Truk</label>
                                <select name="truck_idtruck" class="form-control" required>
                                    <option value="">-- Pilih Truk --</option>
                                    @foreach ($availableTrucks as $truck)
                                        <option value="{{ $truck->idtruck }}">
                                            {{ $truck->nama_truck }}
                                            (Kapasitas: {{ $truck->kapasitas_berat }} kg |
                                            Rp {{ number_format($truck->tarif_per_km, 0, ',', '.') }}/km)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Pilih Driver</label>
                                <select name="driver_iddrivers" class="form-control" required>
                                    <option value="">-- Pilih Driver --</option>
                                    @foreach ($availableDrivers as $driver)
                                        <option value="{{ $driver->iddrivers }}">
                                            {{ $driver->nama }}
                                            (Rp {{ number_format($driver->tarif_harian, 0, ',', '.') }}/hari)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg mt-4 w-100">
                            <i class="fa fa-save"></i> Simpan Order &amp; Buat Shipment
                        </button>
                    </form>
                @elseif (!$fleetChecked)
                    <div class="alert alert-secondary mt-3">
                        <i class="fa fa-info-circle"></i>
                        Silakan isi <strong>Tanggal Estimasi Kirim</strong> dan <strong>Tujuan</strong>,
                        lalu klik <strong>"Cek Armada &amp; Hitung ETA"</strong> untuk melanjutkan.
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{--
    Peta Leaflet: Library ini wajib JavaScript karena rendering peta di browser.
    Namun data koordinat (titik-titik rute) TIDAK diambil via AJAX/fetch,
    melainkan dikirim langsung dari PHP menggunakan @json() Blade directive.
--}}
    @if ($etaData && count($etaData['optimal_coords']) > 0)
        <style>
            .graph-node-tooltip {
                background-color: rgba(15, 23, 42, 0.85) !important;
                border: none !important;
                border-radius: 4px !important;
                color: #ffffff !important;
                font-size: 11px !important;
                font-weight: 600 !important;
                padding: 2px 6px !important;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2) !important;
            }

            .graph-node-tooltip::before {
                border-top-color: rgba(15, 23, 42, 0.85) !important;
            }
        </style>
        <script>
            // Variabel JavaScript diisi dari PHP (bukan AJAX/fetch)
            var allLocations = @json($locations);
            var allRoutes = @json($routes ?? []);
            var optimalCoords = @json($etaData['optimal_coords']);
            var optimalNames = @json(explode('->', $etaData['optimal_path_names']));

            var map = L.map('map').setView([-7.45, 110.5], 7);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // 1. Gambar seluruh jaringan graph (garis abu-abu putus-putus)
            allRoutes.forEach(function(r) {
                if (r.source && r.destination && r.source.lintang && r.destination.lintang) {
                    var p1 = [parseFloat(r.source.lintang), parseFloat(r.source.bujur)];
                    var p2 = [parseFloat(r.destination.lintang), parseFloat(r.destination.bujur)];
                    L.polyline([p1, p2], {
                            color: '#210ce2ff',
                            weight: 3.5,
                            opacity: 0.65,
                            dashArray: '6, 6'
                        })
                        .bindTooltip(r.source.name + ' ⟷ ' + r.destination.name + ' (' + parseFloat(r.distance) +
                            ' km)', {
                                sticky: true
                            })
                        .addTo(map);
                }
            });

            // 2. Gambar seluruh node graph
            allLocations.forEach(function(loc) {
                if (loc.lintang && loc.bujur) {
                    L.circleMarker([parseFloat(loc.lintang), parseFloat(loc.bujur)], {
                            radius: 6,
                            color: '#525533ff',
                            fillColor: '#ffffff',
                            fillOpacity: 1,
                            weight: 2
                        })
                        .bindTooltip(loc.name, {
                            permanent: true,
                            direction: 'top',
                            offset: [0, -7],
                            className: 'graph-node-tooltip'
                        })
                        .addTo(map);
                }
            });

            // 3. Gambar rute utama optimal Dijkstra (garis biru tebal)
            if (optimalCoords.length > 0) {
                var polyline = L.polyline(optimalCoords, {
                    color: '#2563eb',
                    weight: 6,
                    opacity: 0.95
                }).addTo(map);

                optimalCoords.forEach(function(coord, i) {
                    var name = 'Node ' + (i + 1);
                    if (optimalNames[i]) {
                        name = optimalNames[i].trim();
                    }

                    var isStart = (i === 0);
                    var isEnd = (i === optimalCoords.length - 1);

                    var color = '#2563eb';
                    var radius = 8;
                    var labelRole = '🔵 Transit';

                    if (isStart) {
                        color = '#16a34a';
                        radius = 10;
                        labelRole = '🟢 Titik Asal';
                    } else if (isEnd) {
                        color = '#dc2626';
                        radius = 10;
                        labelRole = '🔴 Titik Tujuan';
                    }

                    L.circleMarker(coord, {
                            radius: radius,
                            color: '#ffffff',
                            fillColor: color,
                            fillOpacity: 1,
                            weight: 3
                        })
                        .bindPopup('<b>' + labelRole + ': ' + name + '</b>')
                        .addTo(map);
                });

                map.fitBounds(polyline.getBounds(), {
                    padding: [50, 50]
                });
            }
        </script>
    @endif
@endsection
