@extends('layouts.admin')

@section('title', 'Notifikasi Reorder Point - Inventory App')

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pusat Peringatan Reorder Point (ROP)</h1>
    </div>

    <!-- Alert Success/Error -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-left-success" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle mr-2 fa-lg"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-danger">
            <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-exclamation-triangle mr-1"></i> Daftar Suku Cadang Kritis (Di Bawah Batas Reorder Point)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="ropTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Kode Barang</th>
                            <th>Nama Suku Cadang</th>
                            <th class="text-right">Batas ROP</th>
                            <th class="text-right">Stok Saat Peringatan</th>
                            <th class="text-right">Stok Sekarang</th>
                            <th class="text-center" width="15%">Status</th>
                            <th>Tanggal Peringatan</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notifications as $index => $notif)
                            <tr class="{{ !$notif->rop_sudah_ditangani ? 'table-warning-custom' : '' }}">
                                <td>{{ $notifications->firstItem() + $index }}</td>
                                <td class="font-weight-bold text-gray-900">{{ $notif->sukuCadang->suku_cadang_kode ?? '-' }}</td>
                                <td>{{ $notif->sukuCadang->suku_cadang_nama ?? 'Barang Dihapus' }}</td>
                                <td class="text-right text-muted font-weight-bold">{{ $notif->rop_rop_saat_notif }}</td>
                                <td class="text-right text-danger font-weight-bold">{{ $notif->rop_stok_saat_notif }}</td>
                                <td class="text-right font-weight-bold">
                                    @if($notif->sukuCadang)
                                        {{ $notif->sukuCadang->suku_cadang_stok_total }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($notif->rop_sudah_ditangani)
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Selesai Ditangani</span>
                                    @else
                                        <span class="badge badge-danger px-2 py-1"><i class="fas fa-exclamation-triangle mr-1"></i> Butuh Pemesanan</span>
                                    @endif
                                </td>
                                <td>{{ $notif->rop_created_at ? $notif->rop_created_at->format('d M Y H:i') : '-' }}</td>
                                <td class="text-center">
                                    @if(!$notif->rop_sudah_ditangani)
                                        <form action="{{ route('notifikasi-rop.resolve', $notif->notifikasi_rop_id) }}" method="POST" onsubmit="return confirm('Tandai notifikasi suku cadang ini sebagai sudah ditangani (pengadaan telah diproses)?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success btn-icon-split shadow-sm">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-check"></i>
                                                </span>
                                                <span class="text">Tandai Selesai</span>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small"><i class="fas fa-check text-success"></i> Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-gray-500">
                                        <i class="fas fa-shield-alt fa-2x mb-2 text-success d-block"></i>
                                        Semua stok aman. Tidak ada peringatan ROP aktif.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Custom Styling for Warning Row -->
            @push('styles')
                <style>
                    .table-warning-custom {
                        background-color: rgba(246, 194, 62, 0.08);
                    }
                </style>
            @endpush
            
            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-end">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>

@endsection
