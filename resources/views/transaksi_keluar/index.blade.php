@extends('layouts.admin')

@section('title', 'Log Barang Keluar - Inventory App')

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Transaksi Barang Keluar</h1>
        <a href="{{ route('transaksi-keluar.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Catat Barang Keluar
        </a>
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-left-danger" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle mr-2 fa-lg"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
            <h6 class="m-0 font-weight-bold text-primary mb-2 mb-sm-0">Log Riwayat Pengeluaran Barang</h6>
            
            <!-- Search Form -->
            <form action="{{ route('transaksi-keluar.index') }}" method="GET" class="form-inline my-2 my-lg-0">
                <div class="input-group">
                    <input type="text" name="search" class="form-control bg-light border-0 small" placeholder="Cari dokumen, barang, operator..."
                        value="{{ $search ?? '' }}" aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                        @if($search)
                            <a href="{{ route('transaksi-keluar.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times fa-sm"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="transaksiKeluarTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>No. Surat Jalan</th>
                            <th>Tujuan PT</th>
                            <th>Suku Cadang</th>
                            <th class="text-right" width="8%">Diminta</th>
                            <th class="text-right" width="8%">Terpenuhi</th>
                            <th class="text-center" width="10%">Status</th>
                            <th>Kendaraan Penerima</th>
                            <th>Operator</th>
                            <th>Tanggal Keluar</th>
                            <th width="12%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksiKeluars as $index => $transaksi)
                            <tr>
                                <td>{{ $transaksiKeluars->firstItem() + $index }}</td>
                                <td>{{ $transaksi->no_surat_jalan }}</td>
                                <td class="font-weight-bold text-gray-900">{{ $transaksi->perusahaanTujuan->nama ?? 'N/A' }}</td>
                                <td>
                                    @if($transaksi->sukuCadang)
                                        <div class="font-weight-bold">{{ $transaksi->sukuCadang->suku_cadang_nama }}</div>
                                        <small class="text-muted">{{ $transaksi->sukuCadang->suku_cadang_kode }}</small>
                                    @else
                                        <span class="text-danger small">Barang Dihapus</span>
                                    @endif
                                </td>
                                <td class="text-right text-muted">{{ $transaksi->jumlah_diminta }}</td>
                                <td class="text-right font-weight-bold text-primary">{{ $transaksi->jumlah_terpenuhi }}</td>
                                <td class="text-center">
                                    @if($transaksi->status == 'terpenuhi')
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Terpenuhi</span>
                                    @elseif($transaksi->status == 'sebagian')
                                        <span class="badge badge-warning text-dark px-2 py-1"><i class="fas fa-exclamation-circle mr-1"></i> Sebagian</span>
                                    @elseif($transaksi->status == 'ditolak')
                                        <span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle mr-1"></i> Ditolak</span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1">{{ $transaksi->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($transaksi->kendaraan)
                                        <span class="badge badge-secondary px-2 py-1 text-uppercase">{{ $transaksi->kendaraan->kendaraan_plat }}</span>
                                        <div class="small text-muted">{{ $transaksi->kendaraan->kendaraan_nama_driver }}</div>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="small">
                                    @if($transaksi->user)
                                        {{ $transaksi->user->users_username }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $transaksi->created_at ? $transaksi->created_at->format('d M Y H:i') : '-' }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('transaksi-keluar.cetak-sj', $transaksi->transaksi_keluar_id) }}" target="_blank" class="btn btn-sm btn-secondary btn-circle shadow-sm" title="Cetak SJ">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <a href="{{ route('transaksi-keluar.edit', $transaksi->transaksi_keluar_id) }}" class="btn btn-sm btn-info btn-circle shadow-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('transaksi-keluar.destroy', $transaksi->transaksi_keluar_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi pengeluaran ini? Penghapusan akan mengembalikan alokasi stok.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger btn-circle shadow-sm" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="text-gray-500">
                                        <i class="fas fa-dolly fa-2x mb-2 d-block"></i>
                                        Belum ada riwayat transaksi barang keluar.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-end">
                {{ $transaksiKeluars->links() }}
            </div>
        </div>
    </div>

@endsection
