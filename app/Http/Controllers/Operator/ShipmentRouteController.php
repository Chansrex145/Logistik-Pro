<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Shipment;

class ShipmentRouteController extends Controller
{
    public function index()
    {
        $shipments = Shipment::with([
            'shipmentRoutes.location'
        ])->get();

        return view(
            'operator.shipment_routes.index',
            compact('shipments')
        );
    }
}