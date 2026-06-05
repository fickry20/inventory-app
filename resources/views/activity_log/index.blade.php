@extends('layouts.admin')

@section('title', 'Log Aktivitas - Inventory App')

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Log Aktivitas</h1>
        <div class="text-muted small">
            @if(auth()->user()->users_role === 'spv')
                Menampilkan seluruh riwayat aktivitas sistem
            @else
                Menampilkan riwayat aktivitas Anda sendiri
            @endif
        </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter mr-1"></i> Filter & Pencarian</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('activity-log.index') }}" method="GET">
                <div class="row">
                    @if(auth()->user()->users_role === 'spv')
                        <!-- User Filter (SPV Only) -->
                        <div class="col-md-3 mb-3">
                            <label for="user_id" class="small font-weight-bold text-gray-900">User / Operator</label>
                            <select name="user_id" id="user_id" class="form-control form-control-sm">
                                <option value="">-- Semua User --</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->users_id }}" {{ $userFilter == $u->users_id ? 'selected' : '' }}>
                                        {{ $u->users_username }} ({{ $u->users_role }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Action Type Filter -->
                    <div class="col-md-3 mb-3">
                        <label for="action_type" class="small font-weight-bold text-gray-900">Tipe Aksi</label>
                        <select name="action_type" id="action_type" class="form-control form-control-sm">
                            <option value="">-- Semua Aksi --</option>
                            @foreach($actions as $act)
                                <option value="{{ $act }}" {{ $actionFilter == $act ? 'selected' : '' }}>
                                    {{ $act }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Keyword Search -->
                    <div class="col-md-4 mb-3">
                        <label for="search" class="small font-weight-bold text-gray-900">Kata Kunci Deskripsi / IP</label>
                        <input type="text" name="search" id="search" class="form-control form-control-sm" 
                            placeholder="Cari kata kunci deskripsi atau IP..." value="{{ $search ?? '' }}">
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm btn-block shadow-sm">
                            <i class="fas fa-search fa-sm mr-1"></i> Cari
                        </button>
                        @if($search || $actionFilter || $userFilter)
                            <a href="{{ route('activity-log.index') }}" class="btn btn-secondary btn-sm ml-2 shadow-sm" title="Reset">
                                <i class="fas fa-sync-alt fa-sm"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Logs Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-light d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-list mr-1"></i> Daftar Log Aktivitas</h6>
            <span class="badge badge-info px-2 py-1">Total: {{ $logs->total() }} Log</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Waktu</th>
                            @if(auth()->user()->users_role === 'spv')
                                <th width="12%">Username</th>
                                <th width="12%">Role</th>
                            @endif
                            <th width="15%" class="text-center">Tipe Aksi</th>
                            <th>Deskripsi Aktivitas</th>
                            <th width="10%" class="text-center">IP Address</th>
                            <th width="5%" class="text-center">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $index => $log)
                            <tr>
                                <td>{{ $logs->firstItem() + $index }}</td>
                                <td class="small">{{ $log->created_at ? $log->created_at->timezone('Asia/Jakarta')->format('d M Y H:i:s') : '-' }} WIB</td>
                                @if(auth()->user()->users_role === 'spv')
                                    <td class="font-weight-bold text-gray-900">
                                        {{ $log->users_username }}
                                        @if($log->users_id === auth()->id())
                                            <span class="badge badge-primary ml-1 text-xs">Anda</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($log->users_role === 'spv')
                                            <span class="badge badge-success px-2 py-1"><i class="fas fa-user-shield mr-1"></i> Supervisor</span>
                                        @elseif($log->users_role === 'staf_inventory')
                                            <span class="badge badge-info px-2 py-1"><i class="fas fa-boxes mr-1"></i> Staf Inventory</span>
                                        @elseif($log->users_role === 'admin_gudang')
                                            <span class="badge badge-primary px-2 py-1"><i class="fas fa-warehouse mr-1"></i> Admin Gudang</span>
                                        @else
                                            <span class="badge badge-secondary px-2 py-1">{{ $log->users_role ?? 'System' }}</span>
                                        @endif
                                    </td>
                                @endif
                                <td class="text-center">
                                    @if(str_starts_with($log->action, 'CREATE'))
                                        <span class="badge badge-primary px-2 py-1"><i class="fas fa-plus-circle mr-1"></i> CREATE</span>
                                    @elseif(str_starts_with($log->action, 'UPDATE'))
                                        <span class="badge badge-info px-2 py-1"><i class="fas fa-edit mr-1"></i> UPDATE</span>
                                    @elseif(str_starts_with($log->action, 'DELETE'))
                                        <span class="badge badge-danger px-2 py-1"><i class="fas fa-trash-alt mr-1"></i> DELETE</span>
                                    @elseif($log->action === 'LOGIN')
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-sign-in-alt mr-1"></i> LOGIN</span>
                                    @elseif($log->action === 'LOGOUT')
                                        <span class="badge badge-secondary px-2 py-1"><i class="fas fa-sign-out-alt mr-1"></i> LOGOUT</span>
                                    @elseif($log->action === 'RESOLVE_ROP')
                                        <span class="badge badge-warning px-2 py-1 text-dark"><i class="fas fa-check-double mr-1"></i> RESOLVE ROP</span>
                                    @elseif($log->action === 'PRINT_SURAT_JALAN')
                                        <span class="badge badge-dark px-2 py-1"><i class="fas fa-print mr-1"></i> PRINT SJ</span>
                                    @else
                                        <span class="badge badge-light px-2 py-1 text-dark border">{{ $log->action }}</span>
                                    @endif
                                </td>
                                <td class="text-gray-900">{{ $log->description }}</td>
                                <td class="text-center small font-weight-bold">{{ $log->ip_address }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-light btn-circle shadow-sm" data-toggle="modal" data-target="#detailModal{{ $log->id }}" title="Detail User Agent">
                                        <i class="fas fa-info-circle text-primary"></i>
                                    </button>

                                    <!-- Detail Modal -->
                                    <div class="modal fade" id="detailModal{{ $log->id }}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel{{ $log->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content text-left">
                                                <div class="modal-header">
                                                    <h5 class="modal-title font-weight-bold text-primary" id="detailModalLabel{{ $log->id }}">
                                                        <i class="fas fa-info-circle mr-1"></i> Detail Metadata Log #{{ $log->id }}
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body text-gray-900 small">
                                                    <div class="row mb-2">
                                                        <div class="col-sm-4 font-weight-bold">Waktu:</div>
                                                        <div class="col-sm-8">{{ $log->created_at ? $log->created_at->timezone('Asia/Jakarta')->format('d F Y H:i:s') : '-' }} WIB</div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-4 font-weight-bold">Aktor/User:</div>
                                                        <div class="col-sm-8">{{ $log->users_username }} ({{ $log->users_role ?? 'System' }})</div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-4 font-weight-bold">Aksi:</div>
                                                        <div class="col-sm-8"><span class="badge badge-dark">{{ $log->action }}</span></div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-4 font-weight-bold">Deskripsi:</div>
                                                        <div class="col-sm-8">{{ $log->description }}</div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-4 font-weight-bold">IP Address:</div>
                                                        <div class="col-sm-8">{{ $log->ip_address }}</div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-4 font-weight-bold">User Agent:</div>
                                                        <div class="col-sm-8 text-break">{{ $log->user_agent }}</div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->users_role === 'spv' ? 8 : 6 }}" class="text-center py-4 text-muted">Belum ada log aktivitas yang tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-end">
                {{ $logs->links() }}
            </div>
        </div>
    </div>

@endsection
