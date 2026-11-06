<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- BRAND -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">

        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-truck"></i>
        </div>

        <div class="sidebar-brand-text mx-3">
            Logistik App
        </div>

    </a>

    <hr class="sidebar-divider">

    <!-- ================= ADMIN ================= -->
    @if (Auth::user()->role == 'admin')
        <li class="nav-item">
            <a class="nav-link" href="/dashboard">
                <i class="fas fa-fw fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item"><a class="nav-link" href="/customers"><span>Customer</span></a></li>
        <li class="nav-item"><a class="nav-link" href="/barang"><span>Barang</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('locations.index') }}"><span>Lokasi</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('routes.index') }}"><span>Routes</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('truck.index') }}"><span>Truck</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('driver.index') }}"><span>Driver</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('komponen-biaya.index') }}"><span>Komponen
                    Biaya</span></a></li>
    @endif

    <!-- ================= OPERATOR ================= -->
    @if (Auth::user()->role == 'operator')
        <li class="nav-item"><a class="nav-link" href="/operator/dashboard"><span>Dashboard</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('shipments.index') }}"><span>Shipment</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('orders.index') }}"><span>Order</span></a></li>
        {{-- <li class="nav-item"><a class="nav-link" href="{{ route('orders.by_customer') }}"><span>Cek Order
                    Customer</span></a></li> --}}
        <li class="nav-item"><a class="nav-link" href="{{ route('shipment-routes.index') }}"><span>Shipment
                    Routes</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('monitoring.shipment') }}"><span>Monitoring
                    Shipment</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('map.list') }}"><span>Visualisasi Map</span></a></li>
    @endif

    <!-- ================= MANAGER ================= -->
    @if (Auth::user()->role == 'manager')
        <li class="nav-item"><a class="nav-link" href="{{ route('manager.dashboard') }}"><span>Dashboard</span></a>
        </li>
        <li class="nav-item"><a class="nav-link" href="{{ route('manager.order.report') }}"><span>Laporan
                    Order</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('manager.shipment.report') }}"><span>Laporan
                    Shipment</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('manager.biaya.report') }}"><span>Laporan
                    Biaya</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('manager.driver.performance') }}"><span>Driver
                    Performance</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('manager.truck.usage') }}"><span>Truck Usage</span></a>
        </li>
    @endif

    <hr class="sidebar-divider">

    <!-- LOGOUT -->
    <li class="nav-item">

        <a class="nav-link" href="/logout">

            <i class="fas fa-sign-out-alt"></i>

            <span>Logout</span>

        </a>

    </li>

</ul>
