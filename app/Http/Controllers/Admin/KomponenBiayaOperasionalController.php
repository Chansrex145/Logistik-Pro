<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KomponenBiayaOperasional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomponenBiayaOperasionalController extends Controller
{
    public function index()
    {
        $data = KomponenBiayaOperasional::all();

        return view(
            'admin.komponen_biaya.index',
            compact('data')
        );
    }

    public function create()
    {
        return view('admin.komponen_biaya.create');
    }

    public function store(Request $request)
    {
        KomponenBiayaOperasional::create([
            'nama_komponen' => $request->nama_komponen,
            'cost_value' => $request->cost_value,
            'keterangan' => $request->keterangan,
            'users_id' => Auth::id()
        ]);

        return redirect()
            ->route('komponen-biaya.index')
            ->with(
                'success',
                'Data berhasil ditambahkan'
            );
    }

    public function edit($id)
    {
        $data = KomponenBiayaOperasional::findOrFail($id);

        return view(
            'admin.komponen_biaya.edit',
            compact('data')
        );
    }

    public function update(Request $request, $id)
    {
        $data = KomponenBiayaOperasional::findOrFail($id);

        $data->update([
            'nama_komponen' => $request->nama_komponen,
            'cost_value' => $request->cost_value,
            'keterangan' => $request->keterangan
        ]);

        return redirect()
            ->route('komponen-biaya.index')
            ->with(
                'success',
                'Data berhasil diupdate'
            );
    }

    public function destroy($id)
    {
        $data = KomponenBiayaOperasional::find($id);

        if ($data) {
            $data->delete();
        }

        return redirect()
            ->route('komponen-biaya.index')
            ->with(
                'success',
                'Data berhasil dihapus'
            );
    }
}
