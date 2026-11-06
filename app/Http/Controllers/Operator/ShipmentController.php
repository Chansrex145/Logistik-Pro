<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\KomponenBiayaOperasional;
use App\Models\Location;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Route;
use App\Models\Shipment;
use App\Models\ShipmentCost;
use App\Models\ShipmentItem;
use App\Models\ShipmentRoute;
use App\Models\Truck;
use App\Services\DijkstraService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShipmentController extends Controller
{
    public function index()
    {
        $shipments = Shipment::with([
            'order',
            'truck',
            'driver'
        ])->get();

        return view(
            'operator.shipments.index',
            compact('shipments')
        );
    }

    public function edit($id)
    {
        $shipment = Shipment::findOrFail($id);

        return view(
            'operator.shipments.edit',
            compact('shipment')
        );
    }

    public function update(Request $request, $id)
    {
        $shipment = Shipment::findOrFail($id);

        // Raw SQL: UPDATE shipments SET status_pengiriman = ?, ... WHERE idshipment = ?
        $shipment->status_pengiriman = $request->status_pengiriman;

        if ($request->has('tanggal_kirim_riil')) {
            $shipment->tanggal_kirim_riil = $request->tanggal_kirim_riil;
        }

        if ($request->has('tanggal_terima')) {
            $shipment->tanggal_terima = $request->tanggal_terima;
        }

        $shipment->save();

        // kalau disuruh kasih validasi : 'status_pengiriman' => 'required|in:dalam_perjalanan,selesai,batal'

        // Pengendalian Status Lintas dan Realokasi Inventaris Armada
        // Jika shipment baru berangkat riil (dalam perjalanan)
        if ($request->status_pengiriman == 'dalam_perjalanan') {
            // Raw SQL: UPDATE trucks SET status = 'digunakan' WHERE idtruck = ?
            Truck::where('idtruck', $shipment->truck_idtruck)
                ->update(['status' => 'digunakan']);

            Driver::where('iddrivers', $shipment->driver_iddrivers)
                ->update(['status' => 'digunakan']);

            // Sinkronisasi status order
            if ($shipment->orders_idorders) {
                Order::where('idorders', $shipment->orders_idorders)
                    ->update([
                        'status_order' => 'diproses'
                    ]);
            }
        }

        // Jika shipment selesai, batal, atau retur, bebaskan armada agar dapat digunakan pesanan lain
        if (in_array($request->status_pengiriman, ['selesai', 'batal', 'retur'])) {
            Truck::where('idtruck', $shipment->truck_idtruck)
                ->update(['status' => 'tersedia']);

            Driver::where('iddrivers', $shipment->driver_iddrivers)
                ->update(['status' => 'tersedia']);

            // Sinkronisasi status order
            if ($shipment->orders_idorders) {
                if ($request->status_pengiriman == 'selesai') {

                    Order::where('idorders', $shipment->orders_idorders)
                        ->update([
                            'status_order' => 'selesai'
                        ]);
                } else {

                    Order::where('idorders', $shipment->orders_idorders)
                        ->update([
                            'status_order' => 'dibatalkan'
                        ]);
                }
            }
        }

        // Jika status dikembalikan ke menunggu
        if ($request->status_pengiriman == 'menunggu') {
            if ($shipment->orders_idorders) {
                Order::where('idorders', $shipment->orders_idorders)
                    ->update([
                        'status_order' => 'pending'
                    ]);
            }
        }

        return redirect()->route('shipments.index');
    }

    public function destroy($id)
    {
        Shipment::findOrFail($id)->delete();

        return redirect()
            ->route('shipments.index');
    }

    public function show($id)
    {
        $shipment = Shipment::with([
            'order.customer',
            'order.destination',
            'truck',
            'driver',
            'shipmentRoutes.location',
            'shipmentItems.barang',
            'shipmentCosts.komponen'
        ])->findOrFail($id);

        return view(
            'operator.shipments.show',
            compact('shipment')
        );
    }

    public function suratJalan($id)
    {
        $shipment = Shipment::with([
            'order.customer',
            'truck',
            'driver',
            'shipmentRoutes.location'
        ])->findOrFail($id);

        $pdf = Pdf::loadView(
            'operator.shipments.surat_jalan',
            compact('shipment')
        );

        return $pdf->download(
            'surat_jalan_' . $shipment->idshipment . '.pdf'
        );
    }

    public function calculateEta(Request $request, DijkstraService $dijkstra)
    {
        // Validasi destination harus diisi
        $request->validate([
            'destination_id' => 'required',
        ]);

        // Ambil lokasi asal (Surabaya)
        $sourceLocation = Location::where('name', 'Surabaya')
            ->orWhere('name', 'Gudang Surabaya')
            ->first();

        if (!$sourceLocation) {
            return response()->json([
                'error' => 'Lokasi asal (Surabaya) tidak ditemukan'
            ], 404);
        }


        // Jalankan Dijkstra
        $result = $dijkstra->shortestPath(
            $sourceLocation->idlocations,
            $request->destination_id
        );

        $distance = $result['distance'] ?? 0;

        if ($distance == 0) {
            return response()->json([
                'error' => 'Rute tidak ditemukan'
            ], 404);
        }


        // Parameter Operasional
        $kecepatanRataRata = 50; // Kecepatan rata-rata truk bermuatan berat (50km/jam)
        $waktuBongkarMuat = 3; // Waktu bongkar muat (3 jam)
        $jamKerjaPerHari = 10; // Maksimal jam kerja supir per hari (10 jam)


        // Hitung Waktu Perjalanan

        // Total jam perjalanan murni di jalan
        $totalJamPerjalanan =
            $distance / $kecepatanRataRata;

        // Total jam operasional
        // = waktu perjalanan + waktu bongkar muat
        $totalJamOperasional =
            $totalJamPerjalanan + $waktuBongkarMuat;

        // Berapa hari penuh yang diperlukan
        $hariTambahan =
            floor(
                $totalJamOperasional / $jamKerjaPerHari
            );

        // Sisa jam pada hari terakhir
        $sisaJam =
            fmod(
                $totalJamOperasional,
                $jamKerjaPerHari
            );

        // Tentukan Tanggal Awal
        if (!empty($request->tanggal_estimasi_kirim)) {
            $tanggalAwal = \Carbon\Carbon::parse($request->tanggal_estimasi_kirim);
        } else {
            $tanggalAwal = now();
        }

        // Hitung Estimasi Tiba
        $estimasiTiba =
            $tanggalAwal->copy()
            ->addDays($hariTambahan);

        $estimasiTiba
            ->setTime(8, 0, 0) // Keberangkatan dimulai pukul 08:00
            ->addMinutes(
                round($sisaJam * 60) // kemudian ditambahkan sisa jam perjalanan
            );


        // Format tanggal untuk input datetime-local
        $estimasiTibaFormatted =
            $estimasiTiba->format(
                'Y-m-d\TH:i'
            );

        // Hitung Jumlah hari untuk ditampilkan     
        $days =
            (int) ceil(
                $totalJamOperasional /
                    $jamKerjaPerHari
            );

        if ($days < 1) {
            $days = 1;
        }


        // Ambil data lokasi tujuan

        $destinationLocation = Location::find(
            $request->destination_id
        );

        // Kalau lokasi tidak ditemukan,
        // biaya dianggap 0
        $biayaTol =
            $destinationLocation?->biaya_tol ?? 0;

        $biayaBongkarMuat =
            $destinationLocation?->biaya_bongkar_muat ?? 0;


        // Ambil semua lokasi dalam path optimal
        $allLocations = Location::all()->keyBy('idlocations');

        $optimalPathNames = [];
        $optimalCoords    = [];

        foreach ($result['path'] as $nodeId) {
            $loc = $allLocations->get($nodeId);
            if (!$loc) continue;

            $optimalPathNames[] = $loc->name;

            if ($loc->lintang !== null && $loc->bujur !== null) {
                $optimalCoords[] = [$loc->lintang, $loc->bujur];
            }
        }

        // Resolve nama node pada setiap langkah Dijkstra
        $dijkstraSteps = [];
        foreach ($result['steps'] as $step) {
            $distTableNamed = [];
            foreach ($step['dist_table'] as $nodeId => $d) {
                $name = $allLocations->get($nodeId)?->name ?? 'Node ' . $nodeId;
                $distTableNamed[$name] = $d;
            }

            $relaxationsNamed = [];
            foreach ($step['relaxations'] as $rel) {
                $relaxationsNamed[] = [
                    'neighbor'    => $allLocations->get($rel['neighbor'])?->name ?? 'Node ' . $rel['neighbor'],
                    'edge_weight' => $rel['edge_weight'],
                    'new_dist'    => $rel['new_dist'],
                    'old_dist'    => $rel['old_dist'],
                    'updated'     => $rel['updated'],
                ];
            }

            $dijkstraSteps[] = [
                'iteration'    => $step['iteration'],
                'current_node' => $allLocations->get($step['current_node'])?->name ?? 'Node ' . $step['current_node'],
                'current_dist' => $step['current_dist'],
                'dist_table'   => $distTableNamed,
                'relaxations'  => $relaxationsNamed,
                'finished'     => $step['finished'],
            ];
        }

        // Kirim Hasil ke JavaScript
        return response()->json([
            'distance'          => $distance,
            'days'              => $days,
            'estimasi_tiba'     => $estimasiTibaFormatted,
            'biaya_tol'         => $biayaTol,
            'biaya_bongkar_muat' => $biayaBongkarMuat,
            'optimal_path_names' => implode(' -> ', $optimalPathNames),
            'optimal_coords'    => $optimalCoords,
            'dijkstra_steps'    => $dijkstraSteps,
        ]);
    }

    // Menampilkan data yang dibutuhkan Halaman/Form Buat Order & Surat Jalan (Terintegrasi)
    public function createIntegrated(Request $request, DijkstraService $dijkstra)
    {
        $customers = Customer::all();
        $locations = Location::all();
        $komponenBiaya = KomponenBiayaOperasional::all();

        $selectedCustomerId = $request->get('customers_id');
        $tanggalEstimasiKirim = $request->get('tanggal_estimasi_kirim');
        $destinationId = $request->get('destination_id');

        // Ambil barang berdasarkan customer
        if ($selectedCustomerId) {
            $barangs = Barang::where(
                'customers_id',
                $selectedCustomerId
            )->get();
        } else {
            $barangs = [];
        }


        // CEK ARMADA
        $availableTrucks = [];
        $availableDrivers = [];
        $fleetChecked = false;

        if ($tanggalEstimasiKirim) {

            $fleetChecked = true;

            // Truck yang sudah digunakan
            $unavailableTruckIds = Shipment::whereHas(
                'order',
                function ($query) use ($tanggalEstimasiKirim) { // use = Bawa variabel $tanggalEstimasiKirim dari luar ke dalam function
                    $query->whereDate( // whereDate = membandingkan tanggal, tanpa memperhatikan jam
                        'tanggal_estimasi_kirim',
                        $tanggalEstimasiKirim
                    );
                }
            )
                // Ambil data yang status_pengiriman-nya BUKAN selesai, batal, dan retur            
                ->whereNotIn('status_pengiriman', [
                    'selesai',
                    'batal',
                    'retur'
                ])
                ->pluck('truck_idtruck') // pluck = mengambil satu kolom tertentu saja dari hasil query
                ->toArray(); //Ubah hasil Collection menjadi array PHP

            // Driver yang sudah digunakan
            $unavailableDriverIds = Shipment::whereHas(
                'order',
                function ($query) use ($tanggalEstimasiKirim) {
                    $query->whereDate(
                        'tanggal_estimasi_kirim',
                        $tanggalEstimasiKirim
                    );
                }
            )
                ->whereNotIn('status_pengiriman', [
                    'selesai',
                    'batal',
                    'retur'
                ])
                ->pluck('driver_iddrivers')
                ->toArray();

            // Truck yang tersedia
            $availableTrucks = Truck::where(
                'status',
                'tersedia'
            )
                ->whereNotIn(
                    'idtruck',
                    $unavailableTruckIds
                )
                ->get();

            // Driver yang tersedia
            $availableDrivers = Driver::where(
                'status',
                'tersedia'
            )
                ->whereNotIn(
                    'iddrivers',
                    $unavailableDriverIds
                )
                ->get();
        }


        // KALKULASI ETA & DIJKSTRA
        $etaData = null;

        if ($destinationId && $tanggalEstimasiKirim) {

            // Lokasi asal (Surabaya)
            $sourceLocation = Location::where('name', 'Surabaya')
                ->orWhere('name', 'Gudang Surabaya')
                ->first();

            if ($sourceLocation) {

                // Jalankan Dijkstra
                $result = $dijkstra->shortestPath(
                    $sourceLocation->idlocations,
                    $destinationId
                );

                $distance = $result['distance'] ?? 0;

                if ($distance > 0) {

                    // Parameter pengiriman
                    $kecepatanRataRata = 50;
                    $waktuBongkarMuat = 3;
                    $jamKerjaPerHari = 10;

                    // Hitung waktu perjalanan
                    $totalJamPerjalanan =
                        $distance / $kecepatanRataRata;

                    $totalJamOperasional =
                        $totalJamPerjalanan +
                        $waktuBongkarMuat;

                    $hariTambahan =
                        floor(
                            $totalJamOperasional /
                                $jamKerjaPerHari
                        );

                    $sisaJam =
                        fmod(
                            $totalJamOperasional,
                            $jamKerjaPerHari
                        );

                    // Hitung estimasi tiba
                    $tanggalAwal =
                        \Carbon\Carbon::parse(
                            $tanggalEstimasiKirim
                        );

                    $estimasiTiba =
                        $tanggalAwal
                        ->copy()
                        ->addDays($hariTambahan);

                    $estimasiTiba
                        ->setTime(8, 0, 0)
                        ->addMinutes(
                            round($sisaJam * 60)
                        );

                    $days =
                        (int) ceil(
                            $totalJamOperasional /
                                $jamKerjaPerHari
                        );

                    if ($days < 1) {
                        $days = 1;
                    }

                    // Biaya tujuan
                    $destinationLocation = Location::find(
                        $destinationId
                    );

                    $biayaTol =
                        $destinationLocation?->biaya_tol ?? 0;

                    $biayaBongkarMuat =
                        $destinationLocation?->biaya_bongkar_muat ?? 0;


                    // Rute Optimal & Langkah Dijkstra
                    $allLocationsById = Location::all()->keyBy('idlocations');

                    $optimalPathNames = [];
                    $optimalCoords    = [];

                    foreach ($result['path'] as $nodeId) {
                        $loc = $allLocationsById->get($nodeId);
                        if (!$loc) continue;

                        $optimalPathNames[] = $loc->name;

                        if ($loc->lintang !== null && $loc->bujur !== null) {
                            $optimalCoords[] = [$loc->lintang, $loc->bujur];
                        }
                    }

                    // Resolve nama node pada setiap langkah Dijkstra
                    $dijkstraSteps = [];
                    foreach ($result['steps'] as $step) {
                        $distTableNamed = [];
                        foreach ($step['dist_table'] as $nodeId => $d) {
                            $name = $allLocationsById->get($nodeId)?->name ?? 'Node ' . $nodeId;
                            $distTableNamed[$name] = $d;
                        }
                        $relaxationsNamed = [];
                        foreach ($step['relaxations'] as $rel) {
                            $relaxationsNamed[] = [
                                'neighbor'    => $allLocationsById->get($rel['neighbor'])?->name ?? 'Node ' . $rel['neighbor'],
                                'edge_weight' => $rel['edge_weight'],
                                'new_dist'    => $rel['new_dist'],
                                'old_dist'    => $rel['old_dist'],
                                'updated'     => $rel['updated'],
                            ];
                        }
                        $dijkstraSteps[] = [
                            'iteration'    => $step['iteration'],
                            'current_node' => $allLocationsById->get($step['current_node'])?->name ?? 'Node ' . $step['current_node'],
                            'current_dist' => $step['current_dist'],
                            'dist_table'   => $distTableNamed,
                            'relaxations'  => $relaxationsNamed,
                            'finished'     => $step['finished'],
                        ];
                    }

                    // Data ETA
                    $etaData = [
                        'distance'           => $distance,
                        'days'               => $days,
                        'estimasi_tiba'      => $estimasiTiba->format('Y-m-d\TH:i'),
                        'biaya_tol'          => $biayaTol,
                        'biaya_bongkar_muat' => $biayaBongkarMuat,
                        'optimal_path_names' => implode(' -> ', $optimalPathNames),
                        'optimal_coords'     => $optimalCoords,
                        'dijkstra_steps'     => $dijkstraSteps,
                    ];
                }
            }
        }

        $routes = Route::with(['source', 'destination'])->get();

        return view(
            'operator.shipments.create_integrated',
            compact(
                'customers',
                'locations',
                'routes',
                'barangs',
                'komponenBiaya',
                'selectedCustomerId',
                'tanggalEstimasiKirim',
                'destinationId',
                'availableTrucks',
                'availableDrivers',
                'fleetChecked',
                'etaData'
            )
        );
    }


    public function checkFleetAvailability(Request $request)
    {
        $request->validate([
            'tanggal_estimasi_kirim' => 'required|date'
        ]);

        $tanggal = $request->tanggal_estimasi_kirim;

        // Cari truck yang sudah digunakan
        $unavailableTruckIds = Shipment::whereHas(
            'order',
            function ($query) use ($tanggal) {
                $query->whereDate(
                    'tanggal_estimasi_kirim',
                    $tanggal
                );
            }
        )
            ->whereDate(
                'tanggal_estimasi_kirim',
                $tanggal
            )
            ->whereNotIn('status_pengiriman', [
                'selesai',
                'batal',
                'retur'
            ])
            ->pluck('truck_idtruck');

        // Cari truck yang tersedia
        $availableTrucks = Truck::where(
            'status',
            'tersedia'
        )
            ->whereNotIn(
                'idtruck',
                $unavailableTruckIds
            )
            ->get();


        // Cari driver yang sudah digunakan
        $unavailableDriverIds = Shipment::whereHas(
            'order',
            function ($query) use ($tanggal) {
                $query->whereDate(
                    'tanggal_estimasi_kirim',
                    $tanggal
                );
            }
        )
            ->whereNotIn('status_pengiriman', [
                'selesai',
                'batal',
                'retur'
            ])
            ->pluck('driver_iddrivers');

        // Cari driver yang tersedia
        $availableDrivers = Driver::where(
            'status',
            'tersedia'
        )
            ->whereNotIn(
                'iddrivers',
                $unavailableDriverIds
            )
            ->get();


        return response()->json([
            'trucks' => $availableTrucks,
            'drivers' => $availableDrivers
        ]);
    }

    // Memproses Form dan Menyimpan data dari Halaman Buat Order & Surat Jalan (Terintegrasi)
    public function storeIntegrated(Request $request, DijkstraService $dijkstra)
    {
        $request->validate([
            'truck_idtruck' => 'required',
            'driver_iddrivers' => 'required',
        ], [
            'truck_idtruck.required' =>
            'Silakan Cek Ketersediaan Armada dan pilih Truk terlebih dahulu.',

            'driver_iddrivers.required' =>
            'Silakan Cek Ketersediaan Armada dan pilih Supir terlebih dahulu.',
        ]);

        DB::beginTransaction();

        try {


            // Ambil lokasi asal (Surabaya)
            $sourceLocation = Location::where('name', 'Surabaya')
                ->orWhere('name', 'Gudang Surabaya')
                ->first();

            if (!$sourceLocation) {
                throw new \Exception(
                    'Lokasi asal (Surabaya) tidak ditemukan'
                );
            }

            // Jalankan Dijkstra
            $result = $dijkstra->shortestPath(
                $sourceLocation->idlocations,
                $request->destination_id
            );

            if (empty($result['path'])) {
                throw new \Exception(
                    'Jalur pengiriman tidak ditemukan'
                );
            }

            $jarak = $result['distance'];
            $path = $result['path'];

            // Tentukan Estimasi Tiba (gunakan input dari form atau hitung otomatis jika kosong)
            $estimasiTiba = $request->estimasi_tiba;
            if (!$estimasiTiba) {
                $totalJamOperasional = ($jarak / 50) + 3;
                $hariTambahan = floor($totalJamOperasional / 10);
                $sisaJam = fmod($totalJamOperasional, 10);
                $tanggalAwal = \Carbon\Carbon::parse($request->tanggal_estimasi_kirim ?? now());
                $estimasiTibaCarbon = $tanggalAwal->copy()->addDays($hariTambahan);
                $estimasiTibaCarbon->setTime(8, 0, 0)->addMinutes(round($sisaJam * 60));
                $estimasiTiba = $estimasiTibaCarbon->format('Y-m-d\TH:i');
            }

            // 1. SIMPAN ORDER
            $order = Order::create([
                'tanggal_pesanan' =>
                $request->tanggal_pesanan,

                'tanggal_estimasi_kirim' =>
                $request->tanggal_estimasi_kirim,

                'estimasi_tiba' =>
                $estimasiTiba,

                'status_order' =>
                'pending',

                'customers_id' =>
                $request->customers_id,

                'destination_id' =>
                $request->destination_id,
            ]);

            // ID order yang baru dibuat
            $orderId = $order->idorders;

            // 2. SIMPAN ORDER ITEMS
            $totalBerat = 0;

            if ($request->has('items')) {

                foreach ($request->items as $item) {

                    OrderItem::create([
                        'orders_idorders' =>
                        $orderId,

                        'barang_idbarang' =>
                        $item['barang_idbarang'],

                        'jumlah' =>
                        $item['jumlah'],
                    ]);

                    // Ambil data barang
                    $barang = Barang::find(
                        $item['barang_idbarang']
                    );

                    // Hitung total berat
                    if ($barang) {

                        $totalBerat +=
                            $barang->berat *
                            $item['jumlah'];
                    }
                }
            }

            // 4. AMBIL DATA TRUCK
            $truck = Truck::find(
                $request->truck_idtruck
            );


            if (!$truck) {
                throw new \Exception(
                    'Data truck tidak ditemukan'
                );
            }

            // 5. AMBIL DATA DRIVER
            $driver = Driver::find(
                $request->driver_iddrivers
            );

            if (!$driver) {
                throw new \Exception(
                    'Data driver tidak ditemukan'
                );
            }

            // 6. CEK KAPASITAS TRUCK
            if ($totalBerat > $truck->kapasitas_berat) {

                throw new \Exception(
                    'Total berat barang melebihi kapasitas maksimal truck.'
                );
            }

            // 7. AMBIL DATA LOKASI TUJUAN
            $destinationLocation = Location::find(
                $request->destination_id
            );

            $biayaTol =
                $destinationLocation?->biaya_tol ?? 0;

            $biayaBongkarMuat =
                $destinationLocation?->biaya_bongkar_muat ?? 0;


            // 8. HITUNG SURCHARGE
            $totalSurcharge = 0;

            $selectedKomponen = [];


            if ($request->has('komponen_tambahan')) {

                foreach (
                    $request->komponen_tambahan
                    as $idKomponen
                ) {

                    $komponen =
                        KomponenBiayaOperasional::find(
                            $idKomponen
                        );


                    if ($komponen) {

                        $totalSurcharge +=
                            $komponen->cost_value;


                        $selectedKomponen[] = [
                            'komponen_id' =>
                            $komponen
                                ->idkomponen_biaya_operasional,

                            'cost_value' =>
                            $komponen->cost_value,
                        ];
                    }
                }
            }

            // 9. HITUNG TOTAL BIAYA
            $totalBiaya =
                ($jarak * $truck->tarif_per_km)
                + $driver->tarif_harian
                + $biayaTol
                + $biayaBongkarMuat
                + $totalSurcharge;



            // 10. SIMPAN SHIPMENT
            $shipment = Shipment::create([
                'orders_idorders' =>
                $orderId,

                'truck_idtruck' =>
                $truck->idtruck,

                'driver_iddrivers' =>
                $driver->iddrivers,

                'jarak_km' =>
                $jarak,

                'total_berat' =>
                $totalBerat,

                'total_biaya' =>
                $totalBiaya,

                'tanggal_kirim_riil' =>
                null,

                'estimasi_tiba' =>
                $estimasiTiba,

                'status_pengiriman' =>
                'menunggu',

                'users_id' =>
                Auth::id(),


            ]);


            // ID shipment yang baru dibuat
            $shipmentId =
                $shipment->idshipment;


            // 11. SIMPAN SHIPMENT ROUTES

            foreach (
                $path as $index => $locationId
            ) {

                ShipmentRoute::create([
                    'urutan' =>
                    $index + 1,

                    'shipments_idshipments' =>
                    $shipmentId,

                    'locations_idlocations' =>
                    $locationId,
                ]);
            }

            // 12. SIMPAN SHIPMENT ITEMS

            if ($request->has('items')) {

                foreach ($request->items as $item) {

                    ShipmentItem::create([
                        'shipments_idshipments' =>
                        $shipmentId,

                        'barang_idbarang' =>
                        $item['barang_idbarang'],

                        'jumlah' =>
                        $item['jumlah'],
                    ]);
                }
            }



            // 13. SIMPAN SHIPMENT COST

            foreach ($selectedKomponen as $komp) {

                ShipmentCost::create([
                    'shipment_id' =>
                    $shipmentId,

                    'komponen_id' =>
                    $komp['komponen_id'],

                    'cost_value' =>
                    $komp['cost_value'],
                ]);
            }

            // 14. COMMIT
            DB::commit();


            return redirect()
                ->route('shipments.index')
                ->with(
                    'success',
                    'Order dan Shipment berhasil dibuat terintegrasi.'
                );
        } catch (\Exception $e) {

            // Batalkan seluruh proses jika terjadi error
            DB::rollBack();

            return back()
                ->withErrors([
                    'error' => $e->getMessage()
                ])
                ->withInput();
        }
    }
}
