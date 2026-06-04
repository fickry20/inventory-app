@extends('layouts.admin')

@section('title', 'Log Barang Masuk - Inventory App')

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Transaksi Barang Masuk</h1>
        @if(auth()->user()->users_role === 'admin_gudang')
        <a href="{{ route('transaksi-masuk.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Catat Barang Masuk
        </a>
        @endif
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
            <h6 class="m-0 font-weight-bold text-primary mb-2 mb-sm-0">Log Riwayat Penerimaan Barang</h6>
            
            <!-- Search Form -->
            <form action="{{ route('transaksi-masuk.index') }}" method="GET" class="form-inline my-2 my-lg-0">
                <div class="input-group">
                    <input type="text" name="search" class="form-control bg-light border-0 small" placeholder="Cari dokumen, kode, supplier..."
                        value="{{ $search ?? '' }}" aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                        @if($search)
                            <a href="{{ route('transaksi-masuk.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times fa-sm"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="transaksiMasukTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>No. Surat Jalan</th>
                            <th>Suku Cadang</th>
                            <th class="text-right" width="8%">Kuantitas</th>
                            <th>Supplier</th>
                            <th>Kendaraan / Driver</th>
                            <th>Operator</th>
                            <th>Tanggal Masuk</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksiMasuks as $index => $transaksi)
                            <tr>
                                <td>{{ $transaksiMasuks->firstItem() + $index }}</td>
                                <td>{{ $transaksi->transaksi_masuk_no_surat_jalan }}</td>
                                <td>
                                    @if($transaksi->sukuCadang)
                                        <div class="font-weight-bold">{{ $transaksi->sukuCadang->suku_cadang_nama }}</div>
                                        <small class="text-muted">{{ $transaksi->sukuCadang->suku_cadang_kode }}</small>
                                    @else
                                        <span class="text-danger small">Barang Dihapus</span>
                                    @endif
                                </td>
                                <td class="text-right font-weight-bold text-primary">{{ $transaksi->transaksi_masuk_jumlah }}</td>
                                <td>
                                    @if($transaksi->supplier)
                                        {{ $transaksi->supplier->supplier_nama }}
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($transaksi->driver)
                                        <span class="badge badge-secondary px-2 py-1 text-uppercase">{{ $transaksi->driver->plat_kendaraan }}</span>
                                        <div class="small text-muted mb-1">{{ $transaksi->driver->nama_driver }}</div>
                                        @if($transaksi->driver->foto_sj)
                                            <a href="{{ asset('storage/' . $transaksi->driver->foto_sj) }}" target="_blank" class="btn btn-xs btn-outline-info font-weight-bold py-0 px-1" style="font-size: 10px;">
                                                <i class="fas fa-image mr-1"></i> Foto SJ
                                            </a>
                                        @endif
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
                                    {{ $transaksi->transaksi_masuk_created_at ? $transaksi->transaksi_masuk_created_at->format('d M Y H:i') : '-' }}
                                </td>
                                <td class="text-center">
                                    @if(auth()->user()->users_role === 'admin_gudang')
                                    <a href="{{ route('transaksi-masuk.edit', $transaksi->transaksi_masuk_id) }}" class="btn btn-sm btn-info btn-circle shadow-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('transaksi-masuk.destroy', $transaksi->transaksi_masuk_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi masuk ini? Penghapusan akan memotong stok terkait.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger btn-circle shadow-sm" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @else
                                    <span class="text-muted small">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="text-gray-500">
                                        <i class="fas fa-receipt fa-2x mb-2 d-block"></i>
                                        Belum ada riwayat transaksi barang masuk.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-end">
                {{ $transaksiMasuks->links() }}
            </div>
        </div>
    </div>

@endsection
