<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Alerts -->
        @if(auth()->check() && in_array(auth()->user()->users_role, ['spv', 'staf_inventory', 'admin_gudang']))
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                @if($activeRopAlertsCount > 0)
                    <span class="badge badge-danger badge-counter">{{ $activeRopAlertsCount }}</span>
                @endif
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header bg-danger border-danger">
                    Peringatan Reorder Point (ROP)
                </h6>
                @forelse($activeRopAlerts as $alert)
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('notifikasi-rop.index') }}">
                        <div class="mr-3">
                            <div class="icon-circle bg-danger text-white">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                        <div>
                            <div class="small text-gray-500">{{ $alert->rop_created_at ? $alert->rop_created_at->diffForHumans() : '' }}</div>
                            <span class="font-weight-bold text-gray-900">{{ $alert->sukuCadang->suku_cadang_nama ?? 'Suku Cadang' }}</span> kritis!
                            <div class="small text-danger">Stok: {{ $alert->rop_stok_saat_notif }} (Batas ROP: {{ $alert->rop_rop_saat_notif }})</div>
                        </div>
                    </a>
                @empty
                    <div class="dropdown-item text-center text-gray-500 py-3 small">
                        <i class="fas fa-check-circle text-success mr-1"></i> Semua stok dalam batas aman.
                    </div>
                @endforelse
                <a class="dropdown-item text-center small text-gray-500" href="{{ route('notifikasi-rop.index') }}">Lihat Semua Peringatan</a>
            </div>
        </li>
        @endif



        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                    {{ Auth::user()->users_username ?? 'Guest' }}
                </span>
                <img class="img-profile rounded-circle" src="{{ asset('assets/img/undraw_profile.svg') }}">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('profile') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a>
                <a class="dropdown-item" href="{{ route('activity-log.index') }}">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                    Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>
