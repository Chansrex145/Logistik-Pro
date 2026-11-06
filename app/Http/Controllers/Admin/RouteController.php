<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Route;
use App\Models\Location;
use Illuminate\Support\Facades\Http;

class RouteController extends Controller
{
    /**
     * Menampilkan halaman list route
     */
    public function index()
    {
        $routes = Route::with([
            'source',
            'destination'
        ])->get();

        return view(
            'admin.routes.index',
            compact('routes')
        );
    }

    /**
     * Menampilkan halaman create (form) untuk menambah route
     */
    public function create()
    {
        $locations = Location::all();

        return view(
            'admin.routes.create',
            compact('locations')
        );
    }

    /**
     * Menyimpan data route baru yang diinputkan dari form create
     */
    public function store(Request $request)
    {
        $source =
            Location::findOrFail(
                $request->source_id
            );

        $destination =
            Location::findOrFail(
                $request->destination_id
            );

        $url =
            "https://router.project-osrm.org/route/v1/driving/" .
            $source->bujur . "," .
            $source->lintang . ";" .
            $destination->bujur . "," .
            $destination->lintang .
            "?overview=false";

        $response =
            Http::get($url);

        $distance =
            $response['routes'][0]['distance']
            / 1000;

        $sudahAda = Route::where('source_id', $request->source_id)
            ->where('destination_id', $request->destination_id)
            ->exists();

        if ($sudahAda) {
            return back()->with('error', 'Rute sudah ada di database');
        } else {

            Route::create([

                'source_id' =>
                $request->source_id,

                'destination_id' =>
                $request->destination_id,

                'distance' =>
                round($distance, 2)

            ]);

            return redirect()
                ->route('routes.index')
                ->with(
                    'success',
                    'Route berhasil ditambahkan'
                );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Menampilkan halaman edit (form) untuk mengedit route
     */
    public function edit($id)
    {
        $route = Route::findOrFail($id);

        $locations = Location::all();

        return view(
            'admin.routes.edit',
            compact(
                'route',
                'locations'
            )
        );
    }

    /**
     * Menyimpan data route yang sudah diedit
     */
    public function update(Request $request, $id)
    {
        $route =
            Route::findOrFail($id);

        $source =
            Location::findOrFail(
                $request->source_id
            );

        $destination =
            Location::findOrFail(
                $request->destination_id
            );

        $url =
            "https://router.project-osrm.org/route/v1/driving/" .
            $source->bujur . "," .
            $source->lintang . ";" .
            $destination->bujur . "," .
            $destination->lintang .
            "?overview=false";

        $response =
            Http::get($url);

        $distance =
            $response['routes'][0]['distance']
            / 1000;

        $route->update([

            'source_id' =>
            $request->source_id,

            'destination_id' =>
            $request->destination_id,

            'distance' =>
            round($distance, 2)

        ]);

        return redirect()
            ->route('routes.index')
            ->with(
                'success',
                'Route berhasil diupdate'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $route = Route::findOrFail($id);

        $route->delete();

        return redirect()
            ->route('routes.index')
            ->with(
                'success',
                'Data route berhasil dihapus'
            );
    }
}
