@extends('layouts.admin')

@section('title', 'Tambah Perusahaan Tujuan - Inventory App')

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Perusahaan Tujuan</h1>
        <a href="{{ route('perusahaan-tujuan.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Form Input Perusahaan Tujuan</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('perusahaan-tujuan.store') }}" method="POST">
                @csrf

                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama" class="font-weight-bold text-gray-900">Nama Perusahaan <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="nama" 
                                class="form-control @error('nama') is-invalid @enderror" 
                                placeholder="Masukkan nama perusahaan penerima" 
                                value="{{ old('nama') }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="kontak" class="font-weight-bold text-gray-900">Kontak / No. Telepon</label>
                            <input type="text" name="kontak" id="kontak" 
                                class="form-control @error('kontak') is-invalid @enderror" 
                                placeholder="Masukkan nomor telepon perusahaan (opsional)" 
                                value="{{ old('kontak') }}">
                            @error('kontak')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="alamat" class="font-weight-bold text-gray-900">Alamat Lengkap</label>
                            <textarea name="alamat" id="alamat" rows="4" 
                                class="form-control @error('alamat') is-invalid @enderror" 
                                placeholder="Masukkan alamat lengkap perusahaan tujuan (opsional)...">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end">
                    <a href="{{ route('perusahaan-tujuan.index') }}" class="btn btn-secondary mr-2">Batal</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save mr-1"></i> Simpan Perusahaan
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
