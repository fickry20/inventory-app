@extends('layouts.admin')

@section('title', 'Daftar Suku Cadang - Inventory App')

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Master Suku Cadang</h1>
        <a href="{{ route('suku-cadang.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Suku Cadang
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
            <h6 class="m-0 font-weight-bold text-primary mb-2 mb-sm-0">Daftar Inventaris Spare Part / Suku Cadang</h6>
            
            <!-- Search Form -->
            <form action="{{ route('suku-cadang.index') }}" method="GET" class="form-inline my-2 my-lg-0">
                <div class="input-group">
                    <input type="text" name="search" class="form-control bg-light border-0 small" placeholder="Cari kode, nama, kategori, supplier..."
                        value="{{ $search ?? '' }}" aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                        @if($search)
                            <a href="{{ route('suku-cadang.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times fa-sm"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="sukuCadangTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th class="text-right">Stok Total</th>
                            <th class="text-right">ROP</th>
                            <th class="text-right">Stok Min</th>
                            <th>Supplier</th>
                            <th width="12%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sukuCadangs as $index => $item)
                            <tr>
                                <td>{{ $sukuCadangs->firstItem() + $index }}</td>
                                <td class="font-weight-bold text-gray-900">{{ $item->suku_cadang_kode }}</td>
                                <td>{{ $item->suku_cadang_nama }}</td>
                                <td><span class="badge badge-light px-2 py-1">{{ $item->suku_cadang_kategori }}</span></td>
                                <td>{{ $item->suku_cadang_satuan }}</td>
                                <td class="text-right font-weight-bold">
                                    @if($item->suku_cadang_stok_total <= $item->suku_cadang_stok_minimum)
                                        <span class="badge badge-danger px-2 py-1" title="Stok menyentuh/di bawah minimum!">{{ $item->suku_cadang_stok_total }}</span>
                                    @elseif($item->suku_cadang_stok_total <= $item->suku_cadang_reorder_point)
                                        <span class="badge badge-warning text-dark px-2 py-1" title="Stok menyentuh/di bawah Reorder Point (ROP)">{{ $item->suku_cadang_stok_total }}</span>
                                    @else
                                        <span class="badge badge-success px-2 py-1">{{ $item->suku_cadang_stok_total }}</span>
                                    @endif
                                </td>
                                <td class="text-right text-muted">{{ $item->suku_cadang_reorder_point }}</td>
                                <td class="text-right text-muted">{{ $item->suku_cadang_stok_minimum }}</td>
                                <td>
                                    @if($item->supplier)
                                        <a href="{{ route('supplier.edit', $item->suku_cadang_supplier_id) }}" class="text-decoration-none">
                                            {{ $item->supplier->supplier_nama }}
                                        </a>
                                    @else
                                        <span class="text-danger small">Tanpa Supplier</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('suku-cadang.edit', $item->suku_cadang_id) }}" class="btn btn-sm btn-info btn-circle shadow-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('suku-cadang.destroy', $item->suku_cadang_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data suku cadang ini?');">
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
                                        <i class="fas fa-cubes fa-2x mb-2 d-block"></i>
                                        Belum ada data suku cadang.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-end">
                {{ $sukuCadangs->links() }}
            </div>
        </div>
    </div>

@endsection
