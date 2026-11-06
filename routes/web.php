<?php

use Illuminate\Support\Facades\Route;

// ==============================
// Controllers - Authentication
// ==============================
use App\Http\Controllers\AuthController;

// ==============================
// Controllers - Admin
// ==============================
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\TruckController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\KomponenBiayaOperasionalController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\LatihanController;

// ==============================
// Controllers - Operator
// ==============================
use App\Http\Controllers\Operator\OperatorDashboardController;
use App\Http\Controllers\Operator\OrderController;
use App\Http\Controllers\Operator\OrderItemController;
use App\Http\Controllers\Operator\ShipmentController;
use App\Http\Controllers\Operator\ShipmentRouteController;
use App\Http\Controllers\Operator\MonitoringShipmentController;
use App\Http\Controllers\Operator\MapController;

// ==============================
// Controllers - Manager
// ==============================
use App\Http\Controllers\Manager\ManagerDashboardController;
use App\Http\Controllers\Manager\OrderReportController;
use App\Http\Controllers\Manager\ShipmentReportController;
use App\Http\Controllers\Manager\BiayaReportController;
use App\Http\Controllers\Manager\DriverPerformanceController;
use App\Http\Controllers\Manager\TruckUsageController;
use App\Http\Controllers\Manager\ManagementReportController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Login
Route::get('/', [AuthController::class, 'loginForm']);
Route::post('/login', [AuthController::class, 'login']);

// Logout
Route::get('/logout', [AuthController::class, 'logout']);

// Test
Route::get('/test', function () {
    return 'OK';
});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Hak akses: Admin
|
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    // Kegunaan function resource() = membuat otomatis 7 routes untuk CRUD

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    // Customer
    Route::resource('customers', CustomerController::class);

    // Barang
    Route::resource('barang', BarangController::class);

    // Truck
    Route::resource('truck', TruckController::class);

    // Driver
    Route::resource('driver', DriverController::class);

    // Komponen Biaya Operasional
    Route::resource(
        'komponen-biaya',
        KomponenBiayaOperasionalController::class
    );

    // Location
    Route::resource('locations', LocationController::class);

    // Routes
    Route::resource('routes', RouteController::class);

    // Latihan tampilin data barang
    Route::resource('latihan', LatihanController::class);
});


/*
|--------------------------------------------------------------------------
| Operator Routes
|--------------------------------------------------------------------------
|
| Hak akses: Operator
|
*/

Route::middleware(['auth', 'role:operator'])->group(function () {

    // Kegunaan function name() = untuk memberi nama pada sebuah route , agar bisa di panggil pada saat di view sebagai berikut 
    // href = {{route(operator.dashboard)}}

    // Dashboard
    Route::get(
        '/operator/dashboard',
        [OperatorDashboardController::class, 'index']
    )->name('operator.dashboard');


    // ==========================================
    // Order & Shipment
    // ==========================================

    // Integrated Shipment
    Route::get(
        '/shipments-integrated/create',
        [ShipmentController::class, 'createIntegrated']
    )->name('shipments.create_integrated');

    Route::post(
        '/shipments-integrated/store',
        [ShipmentController::class, 'storeIntegrated']
    )->name('shipments.store_integrated');

    Route::get(
        '/shipments-check-fleet',
        [ShipmentController::class, 'checkFleetAvailability']
    )->name('shipments.check_fleet');


    // Order
    Route::resource('orders', OrderController::class);

    Route::get(
        '/orders-by-customer',
        [OrderController::class, 'orderByCustomer']
    )->name('orders.by_customer');


    // Order Items
    Route::resource('order-items', OrderItemController::class);


    // Shipment
    Route::resource('shipments', ShipmentController::class);

    Route::get(
        '/shipments-eta',
        [ShipmentController::class, 'calculateEta']
    )->name('shipments.calculateEta');


    // Shipment Routes
    Route::get(
        '/shipment-routes',
        [ShipmentRouteController::class, 'index']
    )->name('shipment-routes.index');


    // Surat Jalan
    Route::get(
        '/shipment/{id}/surat-jalan',
        [ShipmentController::class, 'suratJalan']
    )->name('shipment.suratjalan');


    // ==========================================
    // Monitoring & Map
    // ==========================================

    // Monitoring Shipment
    Route::get(
        '/monitoring-shipment',
        [MonitoringShipmentController::class, 'index']
    )->name('monitoring.shipment');


    // Visualisasi Map
    Route::get(
        '/visualisasi-map',
        [MapController::class, 'index']
    )->name('map.list');

    Route::post(
        '/visualisasi-map',
        [MapController::class, 'showRoute']
    )->name('map.route');

    Route::get(
        '/shipment-map/{id}',
        [MapController::class, 'shipment']
    )->name('shipment.map');
});


/*
|--------------------------------------------------------------------------
| Manager Routes
|--------------------------------------------------------------------------
|
| Hak akses: Manager
|
*/

Route::middleware(['auth', 'role:manager'])->group(function () {

    // Dashboard
    Route::get(
        '/manager/dashboard',
        [ManagerDashboardController::class, 'index']
    )->name('manager.dashboard');


    // ==========================================
    // Reports
    // ==========================================

    // Order Report
    Route::get(
        '/manager/order-report',
        [OrderReportController::class, 'index']
    )->name('manager.order.report');


    // Shipment Report
    Route::get(
        '/manager/shipment-report',
        [ShipmentReportController::class, 'index']
    )->name('manager.shipment.report');


    // Biaya Report
    Route::get(
        '/manager/biaya-report',
        [BiayaReportController::class, 'index']
    )->name('manager.biaya.report');


    // Driver Performance
    Route::get(
        '/manager/driver-performance',
        [DriverPerformanceController::class, 'index']
    )->name('manager.driver.performance');


    // Truck Usage
    Route::get(
        '/manager/truck-usage',
        [TruckUsageController::class, 'index']
    )->name('manager.truck.usage');


    // ==========================================
    // Export
    // ==========================================

    // Management Report - PDF
    Route::get(
        '/manager/management-report/pdf',
        [ManagementReportController::class, 'exportPdf']
    )->name('manager.management.pdf');
});
