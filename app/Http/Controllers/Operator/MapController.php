<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Route;
use App\Models\Shipment;
use App\Services\DijkstraService;
use Illuminate\Http\Request;

class MapController extends Controller
{
    // Menampilkan halaman utama simulasi rute dengan graph lengkap
    public function index()
    {
        $locations = Location::all();
        $routes = Route::with(['source', 'destination'])->get();

        // Default asal: Surabaya jika ada
        $defaultSource = Location::where('name', 'Surabaya')->orWhere('name', 'Gudang Surabaya')->first();
        $defaultSourceId = null;
        if ($defaultSource) {
            $defaultSourceId = $defaultSource->idlocations;
        } else {
            $firstLocation = $locations->first();
            if ($firstLocation) {
                $defaultSourceId = $firstLocation->idlocations;
            }
        }

        return view(
            'operator.map.index',
            compact('locations', 'routes', 'defaultSourceId')
        );
    }

    // Menghitung rute terpendek Dijkstra dan menampilkan visualisasi graph
    public function showRoute(
        Request $request,
        DijkstraService $dijkstra
    ) {
        $locations = Location::all();
        $routes = Route::with(['source', 'destination'])->get();

        $sourceId = (int) $request->source_id;
        $destinationId = (int) $request->destination_id;

        $result = $dijkstra->shortestPath(
            $sourceId,
            $destinationId
        );

        $allLocationsById = $locations->keyBy('idlocations');

        $pathLocations = [];
        foreach ($result['path'] as $id) {
            $loc = $allLocationsById->get($id);
            if ($loc) {
                $pathLocations[] = $loc;
            }
        }

        // Resolving nama lokasi pada setiap langkah Dijkstra
        $dijkstraSteps = [];
        if (!empty($result['steps'])) {
            foreach ($result['steps'] as $step) {
                $distTableNamed = [];
                foreach ($step['dist_table'] as $nodeId => $d) {
                    $name = $allLocationsById->get($nodeId)?->name ?? 'Node ' . $nodeId;
                    $distTableNamed[$name] = $d;
                }
                $relaxationsNamed = [];
                foreach ($step['relaxations'] as $rel) {
                    $relaxationsNamed[] = [
                        'neighbor'    => $allLocationsById->get($rel['neighbor'])?->name ?? 'Node ' . $rel['neighbor'],
                        'edge_weight' => $rel['edge_weight'],
                        'new_dist'    => $rel['new_dist'],
                        'old_dist'    => $rel['old_dist'],
                        'updated'     => $rel['updated'],
                    ];
                }
                $dijkstraSteps[] = [
                    'iteration'    => $step['iteration'],
                    'current_node' => $allLocationsById->get($step['current_node'])?->name ?? 'Node ' . $step['current_node'],
                    'current_dist' => $step['current_dist'],
                    'dist_table'   => $distTableNamed,
                    'relaxations'  => $relaxationsNamed,
                    'finished'     => $step['finished'],
                ];
            }
        }

        return view(
            'operator.map.index',
            compact(
                'locations',
                'routes',
                'result',
                'pathLocations',
                'sourceId',
                'destinationId',
                'dijkstraSteps'
            )
        );
    }

    public function shipment($id, DijkstraService $dijkstra)
    {
        $shipment = Shipment::with([
            'shipmentRoutes.location'
        ])->findOrFail($id);

        $routesList    = $shipment->shipmentRoutes->sortBy('urutan');
        $sourceId      = $routesList->first()->locations_idlocations;
        $destinationId = $routesList->last()->locations_idlocations;

        $result = $dijkstra->shortestPath($sourceId, $destinationId);
        $routes = Route::with(['source', 'destination'])->get();

        return view(
            'operator.map.shipment',
            compact('shipment', 'result', 'routes')
        );
    }
}
