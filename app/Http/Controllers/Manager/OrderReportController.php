<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderReportController extends Controller
{
    public function index()
    {
        // mengambil data relasi sekaligus yang terkait dengan tabel order
        $orders = Order::with([
            'customer',
            'destination'
        ])->get();

        return view(
            'manager.order_report',
            compact('orders')
        );
    }
}
