@extends('layouts.admin')

@section('title', 'Edit Akun User - Inventory App')

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit User</h1>
        <a href="{{ route('users.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
        </a>
    </div>

    <!-- Info Banner jika mengedit diri sendiri -->
    @if($user->users_id == auth()->id())
        <div class="alert alert-info shadow-sm border-left-info mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-user-circle mr-3 fa-2x text-info"></i>
                <div>
                    <h5 class="alert-heading font-weight-bold mb-1">Mengedit Akun Aktif Anda</h5>
                    <p class="mb-0 small">Anda sedang mengedit akun Anda sendiri. Modifikasi email atau password akan langsung berpengaruh pada sesi login Anda saat ini.</p>
                </div>
            </div>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-info">
            <h6 class="m-0 font-weight-bold text-white">Form Edit Akun User: {{ $user->users_username }}</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('users.update', $user->users_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Kolom Kiri: Biodata -->
                    <div class="col-md-6">
                        <h5 class="text-info font-weight-bold mb-3 border-bottom pb-2">Informasi Profil</h5>
                        
                        <div class="form-group">
                            <label for="users_nik" class="font-weight-bold text-gray-900">NIK (Nomor Induk Karyawan) <span class="text-danger">*</span></label>
                            <input type="text" name="users_nik" id="users_nik" 
                                class="form-control @error('users_nik') is-invalid @enderror" 
                                placeholder="Masukkan NIK lengkap" 
                                value="{{ old('users_nik', $user->users_nik) }}" required>
                            @error('users_nik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="users_username" class="font-weight-bold text-gray-900">Username <span class="text-danger">*</span></label>
                            <input type="text" name="users_username" id="users_username" 
                                class="form-control @error('users_username') is-invalid @enderror" 
                                placeholder="Masukkan username login" 
                                value="{{ old('users_username', $user->users_username) }}" required>
                            @error('users_username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="users_email" class="font-weight-bold text-gray-900">Email Resmi <span class="text-danger">*</span></label>
                            <input type="email" name="users_email" id="users_email" 
                                class="form-control @error('users_email') is-invalid @enderror" 
                                placeholder="Masukkan email karyawan" 
                                value="{{ old('users_email', $user->users_email) }}" required>
                            @error('users_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="users_jabatan" class="font-weight-bold text-gray-900">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" name="users_jabatan" id="users_jabatan" 
                                class="form-control @error('users_jabatan') is-invalid @enderror" 
                                placeholder="Masukkan jabatan resmi" 
                                value="{{ old('users_jabatan', $user->users_jabatan) }}" required>
                            @error('users_jabatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="users_nomor_telepon" class="font-weight-bold text-gray-900">Nomor Telepon/HP <span class="text-danger">*</span></label>
                            <input type="text" name="users_nomor_telepon" id="users_nomor_telepon" 
                                class="form-control @error('users_nomor_telepon') is-invalid @enderror" 
                                placeholder="Masukkan nomor telepon aktif" 
                                value="{{ old('users_nomor_telepon', $user->users_nomor_telepon) }}" required>
                            @error('users_nomor_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan: Akses dan Keamanan -->
                    <div class="col-md-6">
                        <h5 class="text-info font-weight-bold mb-3 border-bottom pb-2">Akses & Keamanan</h5>
                        
                        <div class="form-group">
                            <label for="users_role" class="font-weight-bold text-gray-900">Hak Akses (Role) <span class="text-danger">*</span></label>
                            <select name="users_role" id="users_role" 
                                class="form-control @error('users_role') is-invalid @enderror" required>
                                <option value="" disabled>-- Pilih Hak Akses --</option>
                                <option value="admin_gudang" {{ old('users_role', $user->users_role) == 'admin_gudang' ? 'selected' : '' }}>Admin Gudang</option>
                                <option value="staf_inventory" {{ old('users_role', $user->users_role) == 'staf_inventory' ? 'selected' : '' }}>Staf Inventory</option>
                                <option value="spv" {{ old('users_role', $user->users_role) == 'spv' ? 'selected' : '' }}>Supervisor</option>
                            </select>
                            @error('users_role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mt-4">
                            <label for="password" class="font-weight-bold text-gray-900">Password Login Baru</label>
                            <input type="password" name="password" id="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                placeholder="Kosongkan jika tidak ingin mengubah password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="font-weight-bold text-gray-900">Konfirmasi Password Login Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                class="form-control" 
                                placeholder="Ulangi password login baru">
                        </div>

                        <div class="alert alert-warning mt-4 small">
                            <h5><i class="fas fa-exclamation-triangle mr-1"></i> Keamanan Password</h5>
                            <p class="mb-0">Demi alasan privasi dan keamanan sistem, password dienkripsi secara satu arah. Kosongkan kolom password di atas jika user tidak mengajukan permohonan reset password.</p>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary mr-2">Batal</a>
                    <button type="submit" class="btn btn-info px-4">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
