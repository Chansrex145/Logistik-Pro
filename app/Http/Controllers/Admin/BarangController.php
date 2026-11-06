<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{

    // ==========================
    // TAMPIL DATA
    // ==========================
    public function index()
    {

        $barang = Barang::with('customer')->get();

        $customers = Customer::all();

        return view('admin.barang.index', compact('barang', 'customers'));
    }



    // ==========================
    // TAMBAH dan SIMPAN
    // ==========================
    public function store(Request $request)
    {
        // latian soal : coba kasih validasi 
        // nama barang wajib diisi (required)
        // berat wajib diisi harus berupa angka (numeric)
        // customer wajib dipilih 

        // $request->validate([

        //     'nama_barang' => 'required|max:100',
        //     'berat' => 'required|numeric|min:1',
        //     'customers_id' => 'required'
        // ]);


        Barang::create([

            'nama_barang' => $request->nama_barang, // ambil nilai dari input request yang memiliki nama 'nama_barang'  
            'berat' => $request->berat,
            'customers_id' => $request->customers_id,
            'users_id' => Auth::id()

        ]);

        return redirect('/barang')
            ->with('success', 'Barang berhasil ditambahkan');
    }

    // ==========================
    // UPDATE
    // ==========================
    public function update(Request $request, string $id)
    {
        // findOrFail($id) -> Mencari data berdasarkan id, dan kalau data tidak ditemukan maka 
        // langsung dianggap gagal/akan menghasilkan erorr 404
        // find() -> mencari data , tetapi jika data tidak ditemukan , maka akan menghasilkan null
        //SELECT * FROM barangs WHERE idbarang = $id LIMIT 1;
        $barang = Barang::findOrFail($id);

        $barang->update([

            'nama_barang' => $request->nama_barang,
            'berat' => $request->berat,
            'customers_id' => $request->customers_id


        ]);

        return redirect('/barang')
            ->with('success', 'Barang berhasil diupdate');
    }

    // ==========================
    // DELETE
    // ==========================
    public function destroy(string $id)
    {

        $barang = Barang::findOrFail($id);

        $barang->delete();

        return redirect('/barang')
            ->with('success', 'Barang berhasil dihapus');
    }
}
