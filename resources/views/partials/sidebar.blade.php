<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-cubes"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Inventory App</div>
    </a>

    @if(auth()->check() && auth()->user()->users_role === 'spv')
        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item {{ Request::routeIs('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Laporan & Analitik
        </div>

        <!-- Nav Item - Laporan Persediaan -->
        <li class="nav-item {{ Request::routeIs('laporan.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('laporan.index') }}">
                <i class="fas fa-fw fa-file-invoice"></i>
                <span>Laporan Persediaan</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Pengaturan
        </div>

        <!-- Nav Item - Perusahaan Tujuan -->
        <li class="nav-item {{ Request::routeIs('perusahaan-tujuan.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('perusahaan-tujuan.index') }}">
                <i class="fas fa-fw fa-building"></i>
                <span>Perusahaan Tujuan</span>
            </a>
        </li>

        <!-- Nav Item - Manajemen User -->
        <li class="nav-item {{ Request::routeIs('users.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('users.index') }}">
                <i class="fas fa-fw fa-users-cog"></i>
                <span>Manajemen User</span>
            </a>
        </li>
    @endif

    @if(auth()->check() && auth()->user()->users_role === 'staf_inventory')
        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Heading -->
        <div class="sidebar-heading mt-3">
            Data Master
        </div>

        <!-- Nav Item - Suku Cadang -->
        <li class="nav-item {{ Request::routeIs('suku-cadang.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('suku-cadang.index') }}">
                <i class="fas fa-fw fa-cubes"></i>
                <span>Suku Cadang</span>
            </a>
        </li>

        <!-- Nav Item - Supplier -->
        <li class="nav-item {{ Request::routeIs('supplier.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('supplier.index') }}">
                <i class="fas fa-fw fa-handshake"></i>
                <span>Supplier</span>
            </a>
        </li>

        <!-- Nav Item - Kendaraan -->
        <li class="nav-item {{ Request::routeIs('kendaraan.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('kendaraan.index') }}">
                <i class="fas fa-fw fa-truck"></i>
                <span>Kendaraan</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Notifikasi
        </div>

        <!-- Nav Item - Peringatan ROP -->
        <li class="nav-item {{ Request::routeIs('notifikasi-rop.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('notifikasi-rop.index') }}">
                <i class="fas fa-fw fa-exclamation-triangle"></i>
                <span>Peringatan ROP</span>
                @if($activeRopAlertsCount > 0)
                    <span class="badge badge-danger ml-1">{{ $activeRopAlertsCount }}</span>
                @endif
            </a>
        </li>
    @endif

    @if(auth()->check() && auth()->user()->users_role === 'admin_gudang')
        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Heading -->
        <div class="sidebar-heading mt-3">
            Transaksi
        </div>

        <!-- Nav Item - Barang Masuk -->
        <li class="nav-item {{ Request::routeIs('transaksi-masuk.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('transaksi-masuk.index') }}">
                <i class="fas fa-fw fa-download"></i>
                <span>Barang Masuk</span>
            </a>
        </li>

        <!-- Nav Item - Barang Keluar -->
        <li class="nav-item {{ Request::routeIs('transaksi-keluar.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('transaksi-keluar.index') }}">
                <i class="fas fa-fw fa-upload"></i>
                <span>Barang Keluar</span>
            </a>
        </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
