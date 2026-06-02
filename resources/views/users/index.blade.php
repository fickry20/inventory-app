@extends('layouts.admin')

@section('title', 'Manajemen User - Inventory App')

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen User</h1>
        <a href="{{ route('users.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Buat User Baru
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
                <i class="fas fa-exclamation-triangle mr-2 fa-lg"></i>
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
            <h6 class="m-0 font-weight-bold text-primary mb-2 mb-sm-0">Daftar Akun Operator Gudang & Supervisor</h6>
            
            <!-- Search Form -->
            <form action="{{ route('users.index') }}" method="GET" class="form-inline my-2 my-lg-0">
                <div class="input-group">
                    <input type="text" name="search" class="form-control bg-light border-0 small" placeholder="Cari user (NIK, nama, email)..."
                        value="{{ $search ?? '' }}" aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                        @if($search)
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times fa-sm"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="userTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>NIK</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Jabatan</th>
                            <th>No. Telepon</th>
                            <th class="text-center">Role</th>
                            <th width="12%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $u)
                            <tr class="{{ $u->users_id == auth()->id() ? 'table-info-custom' : '' }}">
                                <td>{{ $users->firstItem() + $index }}</td>
                                <td class="small">{{ $u->users_nik }}</td>
                                <td class="font-weight-bold text-gray-900">
                                    {{ $u->users_username }}
                                    @if($u->users_id == auth()->id())
                                        <span class="badge badge-primary ml-1 text-xs">Anda</span>
                                    @endif
                                </td>
                                <td>{{ $u->users_email }}</td>
                                <td>{{ $u->users_jabatan }}</td>
                                <td>{{ $u->users_nomor_telepon }}</td>
                                <td class="text-center">
                                    @if($u->users_role == 'admin_gudang')
                                        <span class="badge badge-primary px-2 py-1"><i class="fas fa-warehouse mr-1"></i> Admin Gudang</span>
                                    @elseif($u->users_role == 'staf_inventory')
                                        <span class="badge badge-info px-2 py-1"><i class="fas fa-boxes mr-1"></i> Staf Inventory</span>
                                    @elseif($u->users_role == 'spv')
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-user-shield mr-1"></i> Supervisor</span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1">{{ $u->users_role }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('users.edit', $u->users_id) }}" class="btn btn-sm btn-info btn-circle shadow-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($u->users_id != auth()->id())
                                        <form action="{{ route('users.destroy', $u->users_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan/menghapus akun user ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger btn-circle shadow-sm" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-secondary btn-circle" disabled title="Tidak bisa menghapus akun sendiri">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">Belum ada data user.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Custom Styling for Current User Row -->
            @push('styles')
                <style>
                    .table-info-custom {
                        background-color: rgba(78, 115, 223, 0.05);
                    }
                </style>
            @endpush
            
            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-end">
                {{ $users->links() }}
            </div>
        </div>
    </div>

@endsection
