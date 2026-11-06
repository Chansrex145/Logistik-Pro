<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Truck;

class TruckUsageController extends Controller
{
    public function index()
    {
        // menghitung seberapa banyak shipment yang dilakukan oleh setiap truck 
        $trucks = Truck::withCount(
            'shipments'
        )
            // dan mengurutkannya dari terbesar ke terkecil
            ->orderByDesc(
                'shipments_count'
            )
            ->get();

        return view(
            'manager.truck_usage',
            compact('trucks')
        );
    }
}
