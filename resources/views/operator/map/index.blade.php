@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fa fa-project-diagram text-primary mr-2"></i>Visualisasi Graph & Algoritma Dijkstra
            </h1>
        </div>

        {{-- Form Pemilihan Node Asal & Tujuan --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('map.route') }}" method="POST">
                    @csrf
                    <div class="row align-items-end">
                        <div class="col-md-5 mb-2 mb-md-0">
                            <label class="font-weight-bold text-dark">
                                <i class="fa fa-map-marker-alt text-success mr-1"></i> Lokasi Asal (Source)
                            </label>
                            <select name="source_id" class="form-control" required>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->idlocations }}"
                                        @if ((isset($sourceId) && $sourceId == $location->idlocations) || (!isset($sourceId) && isset($defaultSourceId) && $defaultSourceId == $location->idlocations)) selected @endif>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-5 mb-2 mb-md-0">
                            <label class="font-weight-bold text-dark">
                                <i class="fa fa-flag-checkered text-danger mr-1"></i> Lokasi Tujuan (Destination)
                            </label>
                            <select name="destination_id" class="form-control" required>
                                <option value="">-- Pilih Lokasi Tujuan --</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->idlocations }}"
                                        @if (isset($destinationId) && $destinationId == $location->idlocations) selected @endif>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fa fa-route mr-1"></i> Hitung Rute
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Ringkasan Hasil Dijkstra (Jika Sudah Dihitung) --}}
        @if (isset($result) && isset($pathLocations) && count($pathLocations) > 0)
            <div class="card border-left-success shadow-sm mb-4">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="font-weight-bold text-success mb-1">
                                <i class="fa fa-check-circle mr-1"></i> Jalur Terpendek Dijkstra Ditemukan
                            </h6>
                            <div class="text-dark font-weight-bold">
                                @foreach ($pathLocations as $index => $loc)
                                    @php
                                        $badgeClass = 'badge-primary';
                                        if ($index == 0) {
                                            $badgeClass = 'badge-success';
                                        } elseif ($index == count($pathLocations) - 1) {
                                            $badgeClass = 'badge-danger';
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }} px-2 py-1 mr-1">
                                        {{ $loc->name }}
                                    </span>
                                    @if ($index < count($pathLocations) - 1)
                                        <i class="fa fa-arrow-right text-muted mr-1"></i>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-4 text-md-right mt-2 mt-md-0">
                            <span class="text-muted mr-2">Total Jarak:</span>
                            <span class="h4 font-weight-bold text-primary mb-0">{{ number_format($result['distance'], 0, ',', '.') }} km</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Panel Peta & Legenda Graph --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fa fa-map mr-1"></i> Peta Jaringan Graph & Rute Terpendek
                </h6>
                {{-- Legenda Elemen Graph --}}
                <div class="d-flex flex-wrap align-items-center text-sm small mt-2 mt-sm-0">
                    <span class="mr-3">
                        <span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:#16a34a;vertical-align:middle;" class="mr-1"></span>
                        Asal
                    </span>
                    <span class="mr-3">
                        <span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:#dc2626;vertical-align:middle;" class="mr-1"></span>
                        Tujuan
                    </span>
                    <span class="mr-3">
                        <span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:#2563eb;vertical-align:middle;" class="mr-1"></span>
                        Transit
                    </span>
                    <span class="mr-3">
                        <span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:#ffffff;border:2px solid #334155;vertical-align:middle;" class="mr-1"></span>
                        Node Graph
                    </span>
                    <span class="mr-3">
                        <span style="display:inline-block;width:24px;height:5px;background:#2563eb;border-radius:2px;vertical-align:middle;" class="mr-1"></span>
                        Jalur Optimal Dijkstra
                    </span>
                    <span>
                        <span style="display:inline-block;width:24px;border-top:3px dashed #64748b;vertical-align:middle;" class="mr-1"></span>
                        Jaringan Graph (Rute)
                    </span>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="map" style="height: 600px; width: 100%; border-radius: 0 0 0.35rem 0.35rem;"></div>
            </div>
        </div>

        {{-- Langkah-Langkah Algoritma Dijkstra (Jika Ada) --}}
        @if (!empty($dijkstraSteps))
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-dark">
                        <i class="fa fa-list-ol text-info mr-1"></i> Langkah-Langkah Eksekusi Algoritma Dijkstra
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        Berikut adalah proses eksplorasi node dan relaksasi edge yang dieksekusi oleh Algoritma Dijkstra untuk mencari rute terpendek dari 
                        <strong>{{ $pathLocations[0]->name ?? 'Asal' }}</strong> ke 
                        <strong>{{ $pathLocations[count($pathLocations) - 1]->name ?? 'Tujuan' }}</strong>:
                    </p>

                    <div class="accordion" id="accordionDijkstraSteps">
                        @foreach ($dijkstraSteps as $idx => $step)
                            <div class="card mb-2 border">
                                <div class="card-header py-2 @if ($step['finished']) bg-success text-white @else bg-light @endif"
                                    id="heading{{ $idx }}">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left p-0 font-weight-bold @if ($step['finished']) text-white @else text-dark @endif"
                                            type="button" data-toggle="collapse" data-target="#collapse{{ $idx }}"
                                            aria-expanded="@if ($idx == 0 || $step['finished']) true @else false @endif">
                                            Iterasi {{ $step['iteration'] }}: Mengunjungi Node
                                            <strong>{{ $step['current_node'] }}</strong>
                                            (Jarak Akumulasi: {{ $step['current_dist'] }} km)
                                            @if ($step['finished'])
                                                <span class="badge badge-light text-success float-right">✓ Tujuan Tercapai</span>
                                            @endif
                                        </button>
                                    </h2>
                                </div>

                                <div id="collapse{{ $idx }}"
                                    class="collapse @if ($idx == 0 || $step['finished']) show @endif"
                                    data-parent="#accordionDijkstraSteps">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            {{-- Tabel Jarak Sementara --}}
                                            <div class="col-md-6 mb-3 mb-md-0">
                                                <h6 class="text-xs font-weight-bold text-uppercase text-secondary mb-2">
                                                    Status Jarak Sementara ke Setiap Node (dist[])
                                                </h6>
                                                <div class="table-responsive" style="max-height: 220px; overflow-y: auto;">
                                                    <table class="table table-sm table-bordered mb-0 small">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>Node</th>
                                                                <th class="text-right">Jarak Terpendek</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($step['dist_table'] as $nodeName => $d)
                                                                <tr class="@if ($nodeName == $step['current_node']) table-primary font-weight-bold @endif">
                                                                    <td>
                                                                        {{ $nodeName }}
                                                                        @if ($nodeName == $step['current_node'])
                                                                            <span class="badge badge-primary ml-1">Current</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-right">
                                                                        @if ($d === null)
                                                                            ∞ (Belum Terjangkau)
                                                                        @else
                                                                            {{ $d }} km
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            {{-- Evaluasi Relaksasi Edge Tetangga --}}
                                            <div class="col-md-6">
                                                <h6 class="text-xs font-weight-bold text-uppercase text-secondary mb-2">
                                                    Evaluasi Relaksasi Edge ke Tetangga
                                                </h6>
                                                @if (empty($step['relaxations']))
                                                    <div class="alert alert-secondary py-2 small mb-0">
                                                        Tidak ada tetangga yang dievaluasi pada iterasi ini.
                                                    </div>
                                                @else
                                                    <div class="table-responsive" style="max-height: 220px; overflow-y: auto;">
                                                        <table class="table table-sm table-bordered mb-0 small">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>Tetangga</th>
                                                                    <th>Bobot Edge</th>
                                                                    <th>Jarak Baru</th>
                                                                    <th>Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($step['relaxations'] as $rel)
                                                                    <tr class="@if ($rel['updated']) table-success @endif">
                                                                        <td><strong>{{ $rel['neighbor'] }}</strong></td>
                                                                        <td>{{ $rel['edge_weight'] }} km</td>
                                                                        <td>{{ $rel['new_dist'] }} km</td>
                                                                        <td>
                                                                            @if ($rel['updated'])
                                                                                <span class="badge badge-success">Diperbarui (Jarak Lebih Pendek)</span>
                                                                            @else
                                                                                <span class="badge badge-light text-muted">Tetap ({{ $rel['old_dist'] ?? '∞' }} km)</span>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

    </div>

    {{-- Styling Khusus Tooltip Node --}}
    <style>
        .graph-node-tooltip {
            background-color: rgba(15, 23, 42, 0.85) !important;
            border: none !important;
            border-radius: 4px !important;
            color: #ffffff !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            padding: 2px 6px !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2) !important;
        }
        .graph-node-tooltip::before {
            border-top-color: rgba(15, 23, 42, 0.85) !important;
        }
    </style>

    {{-- Script Inisialisasi Peta Leaflet --}}
    <script>
        // Data dari PHP Blade
        var allLocations = @json($locations);
        var allRoutes    = @json($routes);
        var pathLocations = @json($pathLocations ?? []);

        // Inisialisasi Map (Pusat Pulau Jawa)
        var map = L.map('map').setView([-7.45, 110.5], 7);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // 1. GAMBAR SELURUH JARINGAN GRAPH (EDGES / ROUTES)
        allRoutes.forEach(function(r) {
            if (r.source && r.destination && r.source.lintang && r.destination.lintang) {
                var p1 = [parseFloat(r.source.lintang), parseFloat(r.source.bujur)];
                var p2 = [parseFloat(r.destination.lintang), parseFloat(r.destination.bujur)];
                
                L.polyline([p1, p2], {
                    color: '#64748b',
                    weight: 2.5,
                    opacity: 0.65,
                    dashArray: '6, 6'
                })
                .bindTooltip(r.source.name + ' ⟷ ' + r.destination.name + ' (' + parseFloat(r.distance) + ' km)', {
                    sticky: true
                })
                .addTo(map);
            }
        });

        // 2. GAMBAR SELURUH TITIK LOKASI (NODES)
        allLocations.forEach(function(loc) {
            if (loc.lintang && loc.bujur) {
                var lat = parseFloat(loc.lintang);
                var lng = parseFloat(loc.bujur);

                L.circleMarker([lat, lng], {
                    radius: 6,
                    color: '#334155',
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
                .bindPopup('<b>' + loc.name + '</b><br><small>' + (loc.alamat_lengkap || '') + '</small>')
                .addTo(map);
            }
        });

        // 3. GAMBAR RUTE TERPENDEK DIJKSTRA (JIKA ADA PENCARIAN)
        if (pathLocations && pathLocations.length > 0) {
            var optimalCoords = [];

            pathLocations.forEach(function(loc, index) {
                var lat = parseFloat(loc.lintang);
                var lng = parseFloat(loc.bujur);
                var coord = [lat, lng];
                optimalCoords.push(coord);

                var isStart = (index === 0);
                var isEnd   = (index === pathLocations.length - 1);

                var color = '#2563eb';
                var radius = 8;
                var role = '🔵 Transit #' + index;

                if (isStart) {
                    color = '#16a34a';
                    radius = 10;
                    role = '🟢 Titik Asal';
                } else if (isEnd) {
                    color = '#dc2626';
                    radius = 10;
                    role = '🔴 Titik Tujuan';
                }

                L.circleMarker(coord, {
                    radius: radius,
                    color: '#ffffff',
                    fillColor: color,
                    fillOpacity: 1,
                    weight: 3
                })
                .bindPopup('<b>' + role + ': ' + loc.name + '</b><br>Urutan ke-' + (index + 1))
                .addTo(map);
            });

            // Garis tebal rute optimal
            if (optimalCoords.length > 1) {
                var optimalPolyline = L.polyline(optimalCoords, {
                    color: '#2563eb',
                    weight: 6,
                    opacity: 0.95
                }).addTo(map);

                // Zoom otomatis agar seluruh rute optimal terlihat pas di layar
                map.fitBounds(optimalPolyline.getBounds(), {
                    padding: [50, 50]
                });
            }
        }
    </script>
@endsection
