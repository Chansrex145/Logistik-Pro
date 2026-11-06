<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{

    // =========================
    // TAMPIL DATA
    // =========================
    public function index()
    {
        $customers = Customer::all();
        // SELECT * FROM customers

        return view('admin.customers.index', compact('customers'));
    }

    // =========================
    // SIMPAN DATA
    // =========================
    public function store(Request $request)
    {
        // INSERT INTO customers (nama, alamat, no_hp, users_id) VALUES ($request->nama, $request->alamat, $request->no_hp, auth()->id())
        Customer::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'users_id' => Auth::id()
        ]);

        return redirect('/customers')
            ->with('success', 'Customer berhasil ditambahkan');
    }

    // =========================
    // UPDATE DATA
    // =========================
    public function update(Request $request, string $id)
    {
        // SELECT * FROM customers WHERE id = $id LIMIT 1;
        $customer = Customer::findOrFail($id);

        $customer->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp
        ]);

        return redirect('/customers')
            ->with('success', 'Customer berhasil diupdate');
    }

    // =========================
    // HAPUS DATA
    // =========================
    public function destroy(string $id)
    {
        // SELECT * FROM customers WHERE id = $id
        $customer = Customer::findOrFail($id);
        // DELETE FROM customers WHERE id = $id
        $customer->delete();

        return redirect('/customers')
            ->with('success', 'Customer berhasil dihapus');
    }
}
