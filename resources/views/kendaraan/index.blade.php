@extends('layouts.admin')

@section('title', 'Daftar Kendaraan - Inventory App')

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Master Kendaraan</h1>
        <a href="{{ route('kendaraan.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Kendaraan
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
            <h6 class="m-0 font-weight-bold text-primary mb-2 mb-sm-0">Daftar Kendaraan Internal/Pengirim</h6>
            
            <!-- Search Form -->
            <form action="{{ route('kendaraan.index') }}" method="GET" class="form-inline my-2 my-lg-0">
                <div class="input-group">
                    <input type="text" name="search" class="form-control bg-light border-0 small" placeholder="Cari kendaraan..."
                        value="{{ $search ?? '' }}" aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                        @if($search)
                            <a href="{{ route('kendaraan.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times fa-sm"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="kendaraanTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Plat Kendaraan</th>
                            <th>Jenis Kendaraan</th>
                            <th>Nama Driver</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kendaraans as $index => $kendaraan)
                            <tr>
                                <td>{{ $kendaraans->firstItem() + $index }}</td>
                                <td class="font-weight-bold text-gray-900">
                                    <span class="badge badge-dark px-2 py-1 text-uppercase" style="letter-spacing: 1px;">{{ $kendaraan->kendaraan_plat }}</span>
                                </td>
                                <td>
                                    @if($kendaraan->kendaraan_jenis == 'box')
                                        <span class="badge badge-info px-2 py-1"><i class="fas fa-box mr-1"></i> Box</span>
                                    @elseif($kendaraan->kendaraan_jenis == 'engkel')
                                        <span class="badge badge-warning text-dark px-2 py-1"><i class="fas fa-truck mr-1"></i> Engkel</span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1">{{ $kendaraan->kendaraan_jenis }}</span>
                                    @endif
                                </td>
                                <td>{{ $kendaraan->kendaraan_nama_driver }}</td>
                                <td class="text-center">
                                    <a href="{{ route('kendaraan.edit', $kendaraan->kendaraan_id) }}" class="btn btn-sm btn-info btn-circle shadow-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('kendaraan.destroy', $kendaraan->kendaraan_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kendaraan ini?');">
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
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-gray-500">
                                        <i class="fas fa-truck-moving fa-2x mb-2 d-block"></i>
                                        Belum ada data kendaraan.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-end">
                {{ $kendaraans->links() }}
            </div>
        </div>
    </div>

@endsection
