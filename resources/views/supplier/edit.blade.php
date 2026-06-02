@extends('layouts.admin')

@section('title', 'Edit Supplier - Inventory App')

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Supplier</h1>
        <a href="{{ route('supplier.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-info">
            <h6 class="m-0 font-weight-bold text-white">Form Edit Supplier: {{ $supplier->supplier_nama }}</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('supplier.update', $supplier->supplier_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="supplier_nama" class="font-weight-bold text-gray-900">Nama Supplier <span class="text-danger">*</span></label>
                            <input type="text" name="supplier_nama" id="supplier_nama" 
                                class="form-control @error('supplier_nama') is-invalid @enderror" 
                                placeholder="Masukkan nama supplier" 
                                value="{{ old('supplier_nama', $supplier->supplier_nama) }}" required>
                            @error('supplier_nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="supplier_kontak" class="font-weight-bold text-gray-900">Kontak (Telepon/HP) <span class="text-danger">*</span></label>
                            <input type="text" name="supplier_kontak" id="supplier_kontak" 
                                class="form-control @error('supplier_kontak') is-invalid @enderror" 
                                placeholder="Masukkan nomor telepon kontak" 
                                value="{{ old('supplier_kontak', $supplier->supplier_kontak) }}" required>
                            @error('supplier_kontak')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="supplier_alamat" class="font-weight-bold text-gray-900">Alamat Supplier <span class="text-danger">*</span></label>
                            <textarea name="supplier_alamat" id="supplier_alamat" rows="4" 
                                class="form-control @error('supplier_alamat') is-invalid @enderror" 
                                placeholder="Masukkan alamat lengkap supplier..." required>{{ old('supplier_alamat', $supplier->supplier_alamat) }}</textarea>
                            @error('supplier_alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="supplier_plat_kendaraan" class="font-weight-bold text-gray-900">Plat Kendaraan Pengantar <span class="text-danger">*</span></label>
                            <input type="text" name="supplier_plat_kendaraan" id="supplier_plat_kendaraan" 
                                class="form-control @error('supplier_plat_kendaraan') is-invalid @enderror" 
                                placeholder="Masukkan plat kendaraan" 
                                value="{{ old('supplier_plat_kendaraan', $supplier->supplier_plat_kendaraan) }}" required>
                            @error('supplier_plat_kendaraan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="supplier_nama_driver" class="font-weight-bold text-gray-900">Nama Driver Pengantar <span class="text-danger">*</span></label>
                            <input type="text" name="supplier_nama_driver" id="supplier_nama_driver" 
                                class="form-control @error('supplier_nama_driver') is-invalid @enderror" 
                                placeholder="Masukkan nama lengkap driver pengantar" 
                                value="{{ old('supplier_nama_driver', $supplier->supplier_nama_driver) }}" required>
                            @error('supplier_nama_driver')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="alert alert-info mt-4">
                            <h5><i class="fas fa-info-circle mr-1"></i> Informasi</h5>
                            <p class="mb-0 small">Pastikan data supplier tetap up-to-date untuk kelancaran transaksi masuk gudang.</p>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end">
                    <a href="{{ route('supplier.index') }}" class="btn btn-secondary mr-2">Batal</a>
                    <button type="submit" class="btn btn-info px-4">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
