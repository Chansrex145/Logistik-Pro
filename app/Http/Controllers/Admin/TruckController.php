<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TruckController extends Controller
{
    public function index()
    {
        $truck = Truck::all();

        return view('admin.truck.index', compact('truck'));
    }

    public function create()
    {
        return view('admin.truck.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_truck' => 'required',
            'plat_nomor' => 'required',
            'kapasitas_berat' => 'required|numeric',
            'tarif_per_km' => 'required|numeric',
            'status' => 'required'
        ]);

        $data = $request->all();
        $data['users_id'] = Auth::id();
        Truck::create($data);

        return redirect('/truck')
            ->with('success', 'Data truck berhasil ditambahkan');
    }

    public function edit($id)
    {
        $truck = Truck::findOrFail($id);

        return view('admin.truck.edit', compact('truck'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_truck' => 'required',
            'plat_nomor' => 'required',
            'kapasitas_berat' => 'required|numeric',
            'tarif_per_km' => 'required|numeric',
            'status' => 'required'
        ]);

        $truck = Truck::findOrFail($id);

        $truck->update($request->all());

        return redirect('/truck')
            ->with('success', 'Data truck berhasil diupdate');
    }

    public function destroy($id)
    {
        $truck = Truck::findOrFail($id);

        $truck->delete();

        return redirect('/truck')
            ->with('success', 'Data truck berhasil dihapus');
    }
}
