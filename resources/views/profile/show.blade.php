@extends('layouts.admin')

@section('title', 'Profil Pengguna - Inventory App')

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Profil Saya</h1>
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

    <div class="row">
        <!-- Panel Kiri: User Card -->
        <div class="col-xl-4 col-md-5 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <img class="img-profile rounded-circle img-thumbnail mb-3" 
                        src="{{ asset('assets/img/undraw_profile.svg') }}" 
                        style="width: 150px; height: 150px; object-fit: cover;">
                    
                    <h4 class="font-weight-bold text-gray-900 mb-1">{{ $user->users_username }}</h4>
                    <p class="text-muted mb-3">{{ $user->users_jabatan }}</p>
                    
                    @if($user->users_role == 'admin_gudang')
                        <span class="badge badge-primary px-3 py-2 text-md"><i class="fas fa-warehouse mr-1"></i> Admin Gudang</span>
                    @elseif($user->users_role == 'staf_inventory')
                        <span class="badge badge-info px-3 py-2 text-md"><i class="fas fa-boxes mr-1"></i> Staf Inventory</span>
                    @elseif($user->users_role == 'spv')
                        <span class="badge badge-success px-3 py-2 text-md"><i class="fas fa-user-shield mr-1"></i> Supervisor</span>
                    @else
                        <span class="badge badge-secondary px-3 py-2 text-md">{{ $user->users_role }}</span>
                    @endif

                    <hr class="my-4">

                    <div class="text-left text-gray-800 small">
                        <div class="row mb-2">
                            <div class="col-5 font-weight-bold">NIK:</div>
                            <div class="col-7 text-muted">{{ $user->users_nik }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 font-weight-bold">Email:</div>
                            <div class="col-7 text-muted">{{ $user->users_email }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 font-weight-bold">Telepon:</div>
                            <div class="col-7 text-muted">{{ $user->users_nomor_telepon }}</div>
                        </div>
                        <div class="row">
                            <div class="col-5 font-weight-bold">Terdaftar:</div>
                            <div class="col-7 text-muted">{{ $user->users_created_at ? $user->users_created_at->format('d M Y') : '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Kanan: Edit Form -->
        <div class="col-xl-8 col-md-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary">
                    <h6 class="m-0 font-weight-bold text-white">Edit Informasi Akun & Keamanan</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h5 class="text-primary font-weight-bold mb-3 border-bottom pb-2">Informasi Profil</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="users_nik" class="font-weight-bold text-gray-900">NIK (Nomor Induk Karyawan) <span class="text-danger">*</span></label>
                                    <input type="text" name="users_nik" id="users_nik" 
                                        class="form-control @error('users_nik') is-invalid @enderror" 
                                        value="{{ old('users_nik', $user->users_nik) }}" required>
                                    @error('users_nik')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="users_username" class="font-weight-bold text-gray-900">Username <span class="text-danger">*</span></label>
                                    <input type="text" name="users_username" id="users_username" 
                                        class="form-control @error('users_username') is-invalid @enderror" 
                                        value="{{ old('users_username', $user->users_username) }}" required>
                                    @error('users_username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="users_email" class="font-weight-bold text-gray-900">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="users_email" id="users_email" 
                                        class="form-control @error('users_email') is-invalid @enderror" 
                                        value="{{ old('users_email', $user->users_email) }}" required>
                                    @error('users_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="users_nomor_telepon" class="font-weight-bold text-gray-900">Nomor Telepon/HP <span class="text-danger">*</span></label>
                                    <input type="text" name="users_nomor_telepon" id="users_nomor_telepon" 
                                        class="form-control @error('users_nomor_telepon') is-invalid @enderror" 
                                        value="{{ old('users_nomor_telepon', $user->users_nomor_telepon) }}" required>
                                    @error('users_nomor_telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-gray-700">Jabatan <span class="text-muted font-weight-normal">(Locked)</span></label>
                                    <input type="text" class="form-control bg-light" value="{{ $user->users_jabatan }}" readonly disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-gray-700">Hak Akses / Role <span class="text-muted font-weight-normal">(Locked)</span></label>
                                    <input type="text" class="form-control bg-light" value="{{ strtoupper($user->users_role) }}" readonly disabled>
                                </div>
                            </div>
                        </div>

                        <h5 class="text-primary font-weight-bold mt-4 mb-3 border-bottom pb-2">Ubah Password Login</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="font-weight-bold text-gray-900">Password Baru</label>
                                    <input type="password" name="password" id="password" 
                                        class="form-control @error('password') is-invalid @enderror" 
                                        placeholder="Kosongkan jika tidak diubah">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation" class="font-weight-bold text-gray-900">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" 
                                        class="form-control" 
                                        placeholder="Ulangi password baru">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="fas fa-save mr-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
