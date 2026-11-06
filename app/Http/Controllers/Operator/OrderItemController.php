<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Barang;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        $orderItems = OrderItem::with([
            'order',
            'barang'
        ])->get();

        return view(
            'operator.order_items.index',
            compact('orderItems')
        );
    }

    public function create()
    {
        $orders = Order::all();
        $barangs = Barang::all();

        return view(
            'operator.order_items.create',
            compact(
                'orders',
                'barangs'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'orders_idorders' => 'required',
            'barang_idbarang' => 'required',
            'jumlah' => 'required|integer|min:1'
        ]);

        OrderItem::create([
            'orders_idorders' => $request->orders_idorders,
            'barang_idbarang' => $request->barang_idbarang,
            'jumlah' => $request->jumlah
        ]);

        return redirect()
            ->route('order-items.index')
            ->with(
                'success',
                'Order Item berhasil ditambahkan'
            );
    }

    public function edit($id)
    {
        $orderItem = OrderItem::findOrFail($id);

        $orders = Order::all();
        $barangs = Barang::all();

        return view(
            'operator.order_items.edit',
            compact(
                'orderItem',
                'orders',
                'barangs'
            )
        );
    }

    public function update(Request $request, $id)
    {
        $orderItem = OrderItem::findOrFail($id);

        $orderItem->update([
            'orders_idorders' => $request->orders_idorders,
            'barang_idbarang' => $request->barang_idbarang,
            'jumlah' => $request->jumlah
        ]);

        return redirect()
            ->route('order-items.index')
            ->with(
                'success',
                'Order Item berhasil diupdate'
            );
    }

    public function destroy($id)
    {
        OrderItem::destroy($id);

        return redirect()
            ->route('order-items.index')
            ->with(
                'success',
                'Order Item berhasil dihapus'
            );
    }
}