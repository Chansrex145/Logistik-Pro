<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Models\Truck;
use App\Models\Driver;
use App\Models\Barang;
use App\Models\Shipment;
use App\Models\ShipmentRoute;
use App\Models\ShipmentItem;
use App\Models\ShipmentCost;
use App\Models\KomponenBiayaOperasional;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::with([
            'customer',
            'destination'
        ])->get();

        return view(
            'operator.orders.index',
            compact('orders')
        );
    }



    public function destroy($id)
    {
        Order::destroy($id);

        return redirect()
            ->route('orders.index')
            ->with(
                'success',
                'Order berhasil dihapus'
            );
    }

    public function orderByCustomer(Request $request)
    {
        $customers = Customer::all(); // ambil semua data customers untuk dropdown di blade
        $selectedCustomerId = $request->customers_id; // ambil nilai customers_id dari request
        $orders = collect(); // collect() -> membuat collection kosong/wadah untuk menyimpan sekumpulan data 

        if ($selectedCustomerId) {
            $orders = Order::with('orderItems.barang')->where('customers_id', $selectedCustomerId)->get();
            // $orders = Order::where('customers_id', $selectedCustomerId)->get(); // mengambil data dari database dan hasilnya berupa collection
        }

        return view('operator.orders.by_customer', compact('customers', 'orders', 'selectedCustomerId'));
    }
}
