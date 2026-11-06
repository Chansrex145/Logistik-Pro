<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    /**
     * Mengambil semua data lokasi dan menampilkannya di halaman index (Locations)
     */
    public function index()
    {
        // latest() -> Mengurutkan data berdasarkan tanggal pembuatan (terbaru)
        // SELECT * FROM locations ORDER BY created_at DESC
        $locations = Location::latest()->get();

        return view('admin.locations.index',compact('locations'));
    }

    /**
     * Menampilkan halaman create (form) untuk menambah lokasi
     */
    public function create()
    {
        return view('admin.locations.create');
    }

    /**
     * Menyimpan data lokasi baru yang diinputkan dari form create
     */
    public function store(Request $request)
    {
        $request->validate([
        'name' => 'required|max:100',
        'lintang' => 'required|numeric',
        'bujur' => 'required|numeric',
        'alamat_lengkap' => 'nullable|string',
        'biaya_tol' => 'required|numeric|min:0',
        'biaya_bongkar_muat' => 'required|numeric|min:0'
    ]);

    Location::create([
        'name' => $request->name,
        'lintang' => $request->lintang,
        'bujur' => $request->bujur,
        'alamat_lengkap' => $request->alamat_lengkap,
        'biaya_tol' => $request->biaya_tol,
        'biaya_bongkar_muat' => $request->biaya_bongkar_muat
    ]);

    return redirect()
        ->route('locations.index')
        ->with('success', 'Data lokasi berhasil ditambahkan');
    }

    // Menampilkan halaman edit (form) untuk mengedit lokasi
    public function edit(string $id)
    {
        $location = Location::findOrFail($id);

        return view('admin.locations.edit',compact('location'));
    }

    /**
     * Menyimpan data lokasi yang sudah diedit
     */
    public function update(Request $request, string $id)
    {
         $request->validate([
        'name' => 'required|max:100',
        'lintang' => 'required|numeric',
        'bujur' => 'required|numeric',
        'alamat_lengkap' => 'nullable|string',
        'biaya_tol' => 'required|numeric|min:0',
        'biaya_bongkar_muat' => 'required|numeric|min:0'
    ]);

    $location = Location::findOrFail($id);

    $location->update([
        'name' => $request->name,
        'lintang' => $request->lintang,
        'bujur' => $request->bujur,
        'alamat_lengkap' => $request->alamat_lengkap,
        'biaya_tol' => $request->biaya_tol,
        'biaya_bongkar_muat' => $request->biaya_bongkar_muat
    ]);

    return redirect()
        ->route('locations.index')
        ->with('success', 'Data lokasi berhasil diupdate');
    }

    /**
     * Menghapus data lokasi
     */
    public function destroy(string $id)
    {
        Location::destroy($id);

        return redirect()->route('locations.index');
    }
}
