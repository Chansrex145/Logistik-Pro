<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::all();

        return view('admin.driver.index', compact('drivers'));
    }

    public function create()
    {
        return view('admin.driver.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nomor_telepon' => 'required',
            'tarif_harian' => 'required|numeric',
            'status' => 'required'
        ]);

        Driver::create([
            'nama' => $request->nama,
            'nomor_telepon' => $request->nomor_telepon,
            'tarif_harian' => $request->tarif_harian,
            'status' => $request->status,
            'users_id' => Auth::id()
        ]);

        return redirect('/driver')
            ->with('success', 'Data driver berhasil ditambahkan');
    }

    public function edit($id)
    {
        $driver = Driver::findOrFail($id);

        return view('admin.driver.edit', compact('driver'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'nomor_telepon' => 'required',
            'tarif_harian' => 'required|numeric',
            'status' => 'required'
        ]);

        $driver = Driver::findOrFail($id);

        $driver->update([
            'nama' => $request->nama,
            'nomor_telepon' => $request->nomor_telepon,
            'tarif_harian' => $request->tarif_harian
        ]);

        return redirect('/driver')
            ->with('success', 'Data driver berhasil diupdate');
    }

    public function destroy($id)
    {
        Driver::destroy($id);

        return redirect('/driver')
            ->with('success', 'Data driver berhasil dihapus');
    }
}
