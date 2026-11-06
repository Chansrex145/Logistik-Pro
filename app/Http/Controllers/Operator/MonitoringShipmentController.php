<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Shipment;

class MonitoringShipmentController extends Controller
{
    public function index()
    {
        $shipments = Shipment::with([
            'order.customer',
            'order.destination',
            'driver',
            'truck'
        ])->get();

        return view(
            'operator.monitoring_shipment.index',
            compact('shipments')
        );
    }
}
