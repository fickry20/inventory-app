@extends('layouts.admin')

@section('title', 'Buat Akun User - Inventory App')

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Buat User Baru</h1>
        <a href="{{ route('users.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Form Input Registrasi Akun Staf Baru</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="row">
                    <!-- Kolom Kiri: Biodata -->
                    <div class="col-md-6">
                        <h5 class="text-primary font-weight-bold mb-3 border-bottom pb-2">Informasi Profil</h5>
                        
                        <div class="form-group">
                            <label for="users_nik" class="font-weight-bold text-gray-900">NIK (Nomor Induk Karyawan) <span class="text-danger">*</span></label>
                            <input type="text" name="users_nik" id="users_nik" 
                                class="form-control @error('users_nik') is-invalid @enderror" 
                                placeholder="Masukkan NIK lengkap (contoh: 3201010101010004)" 
                                value="{{ old('users_nik') }}" required>
                            @error('users_nik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="users_username" class="font-weight-bold text-gray-900">Username <span class="text-danger">*</span></label>
                            <input type="text" name="users_username" id="users_username" 
                                class="form-control @error('users_username') is-invalid @enderror" 
                                placeholder="Masukkan username login (contoh: budi_gudang)" 
                                value="{{ old('users_username') }}" required>
                            @error('users_username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="users_email" class="font-weight-bold text-gray-900">Email Resmi <span class="text-danger">*</span></label>
                            <input type="email" name="users_email" id="users_email" 
                                class="form-control @error('users_email') is-invalid @enderror" 
                                placeholder="Masukkan email karyawan (contoh: budi@example.com)" 
                                value="{{ old('users_email') }}" required>
                            @error('users_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="users_jabatan" class="font-weight-bold text-gray-900">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" name="users_jabatan" id="users_jabatan" 
                                class="form-control @error('users_jabatan') is-invalid @enderror" 
                                placeholder="Masukkan jabatan resmi (contoh: Operator Shift A)" 
                                value="{{ old('users_jabatan') }}" required>
                            @error('users_jabatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="users_nomor_telepon" class="font-weight-bold text-gray-900">Nomor Telepon/HP <span class="text-danger">*</span></label>
                            <input type="text" name="users_nomor_telepon" id="users_nomor_telepon" 
                                class="form-control @error('users_nomor_telepon') is-invalid @enderror" 
                                placeholder="Masukkan nomor telepon aktif" 
                                value="{{ old('users_nomor_telepon') }}" required>
                            @error('users_nomor_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan: Akses dan Keamanan -->
                    <div class="col-md-6">
                        <h5 class="text-primary font-weight-bold mb-3 border-bottom pb-2">Akses & Keamanan</h5>
                        
                        <div class="form-group">
                            <label for="users_role" class="font-weight-bold text-gray-900">Hak Akses (Role) <span class="text-danger">*</span></label>
                            <select name="users_role" id="users_role" 
                                class="form-control @error('users_role') is-invalid @enderror" required>
                                <option value="" disabled selected>-- Pilih Hak Akses --</option>
                                <option value="admin_gudang" {{ old('users_role') == 'admin_gudang' ? 'selected' : '' }}>Admin Gudang (Mengelola penerimaan logistik)</option>
                                <option value="staf_inventory" {{ old('users_role') == 'staf_inventory' ? 'selected' : '' }}>Staf Inventory (Mengelola stok barang)</option>
                                <option value="spv" {{ old('users_role') == 'spv' ? 'selected' : '' }}>Supervisor (Mengawasi & menyelesaikan alert ROP)</option>
                            </select>
                            @error('users_role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mt-4">
                            <label for="password" class="font-weight-bold text-gray-900">Password Login <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                placeholder="Masukkan password (minimal 6 karakter)" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="font-weight-bold text-gray-900">Konfirmasi Password Login <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                class="form-control" 
                                placeholder="Ulangi password login" required>
                        </div>

                        <div class="alert alert-info mt-4 small">
                            <h5><i class="fas fa-info-circle mr-1"></i> Catatan Hak Akses</h5>
                            <p class="mb-0">Setiap role memegang wewenang yang berbeda. Pastikan Anda memilih jenis role yang tepat agar staf terkait mendapatkan modul navigasi yang sesuai dengan perannya.</p>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary mr-2">Batal</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-user-plus mr-1"></i> Daftarkan User
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
