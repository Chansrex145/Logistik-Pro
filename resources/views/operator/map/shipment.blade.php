@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <h1 class="h3 mb-4">

            Peta Shipment
            #{{ $shipment->idshipment }}

        </h1>

        <div id="map" style="
            height:600px;
            width:100%;
         ">
        </div>

    </div>

    <script>
        var map = L.map('map').setView(
            [-7.2575, 112.7521],
            8
        );

        L.tileLayer(
            'https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19
            }
        ).addTo(map);

        var points = [];

        @if (isset($routes))
            var allRoutes = @json($routes);
            allRoutes.forEach(function(r) {
                if (r.source && r.destination && r.source.lintang && r.destination.lintang) {
                    var p1 = [parseFloat(r.source.lintang), parseFloat(r.source.bujur)];
                    var p2 = [parseFloat(r.destination.lintang), parseFloat(r.destination.bujur)];
                    L.polyline([p1, p2], {
                        color: '#64748b',
                        weight: 2.5,
                        opacity: 0.6,
                        dashArray: '6, 6'
                    }).bindTooltip(r.source.name + ' ⟷ ' + r.destination.name + ' (' + parseFloat(r.distance) + ' km)', { sticky: true }).addTo(map);
                }
            });
        @endif

        @foreach ($shipment->shipmentRoutes->sortBy('urutan') as $route)

            var lat =
                {{ $route->location->lintang }};

            var lng =
                {{ $route->location->bujur }};

            points.push([
                lat,
                lng
            ]);

            L.marker([
                    lat,
                    lng
                ])
                .addTo(map)
                .bindPopup(
                    "{{ $route->location->name }}"
                );
        @endforeach

        if (points.length > 1) {
            var polyline =
                L.polyline(
                    points, {
                        weight: 6
                    }
                ).addTo(map);

            map.fitBounds(
                polyline.getBounds()
            );
        }
    </script>
@endsection
