@extends('layouts.admin')

@section('title', 'Tambah Kendaraan - Inventory App')

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Kendaraan</h1>
        <a href="{{ route('kendaraan.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Form Input Kendaraan Baru</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('kendaraan.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <div class="form-group">
                            <label for="kendaraan_plat" class="font-weight-bold text-gray-900">Plat Nomor Kendaraan <span class="text-danger">*</span></label>
                            <input type="text" name="kendaraan_plat" id="kendaraan_plat" 
                                class="form-control text-uppercase @error('kendaraan_plat') is-invalid @enderror" 
                                placeholder="Masukkan nomor plat kendaraan (contoh: B 1234 XY)" 
                                value="{{ old('kendaraan_plat') }}" required>
                            @error('kendaraan_plat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Nomor plat harus unik dan tidak boleh sama dengan kendaraan lain.</small>
                        </div>

                        <div class="form-group">
                            <label for="kendaraan_jenis" class="font-weight-bold text-gray-900">Jenis Kendaraan <span class="text-danger">*</span></label>
                            <select name="kendaraan_jenis" id="kendaraan_jenis" 
                                class="form-control @error('kendaraan_jenis') is-invalid @enderror" required>
                                <option value="" disabled selected>-- Pilih Jenis Kendaraan --</option>
                                <option value="box" {{ old('kendaraan_jenis') == 'box' ? 'selected' : '' }}>Box</option>
                                <option value="engkel" {{ old('kendaraan_jenis') == 'engkel' ? 'selected' : '' }}>Engkel</option>
                            </select>
                            @error('kendaraan_jenis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="kendaraan_nama_driver" class="font-weight-bold text-gray-900">Nama Driver <span class="text-danger">*</span></label>
                            <input type="text" name="kendaraan_nama_driver" id="kendaraan_nama_driver" 
                                class="form-control @error('kendaraan_nama_driver') is-invalid @enderror" 
                                placeholder="Masukkan nama lengkap driver kendaraan" 
                                value="{{ old('kendaraan_nama_driver') }}" required>
                            @error('kendaraan_nama_driver')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="alert alert-info mt-4">
                            <h5><i class="fas fa-info-circle mr-1"></i> Petunjuk Pengisian</h5>
                            <p class="mb-0 small">Kendaraan yang didaftarkan di sini akan digunakan untuk mencatat distribusi logistik baik transaksi barang masuk maupun pengiriman barang keluar.</p>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-md-8 mx-auto d-flex justify-content-end">
                        <a href="{{ route('kendaraan.index') }}" class="btn btn-secondary mr-2">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save mr-1"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
