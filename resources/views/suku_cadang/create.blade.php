@extends('layouts.admin')

@section('title', 'Tambah Suku Cadang - Inventory App')

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Suku Cadang</h1>
        <a href="{{ route('suku-cadang.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Form Input Suku Cadang Baru</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('suku-cadang.store') }}" method="POST">
                @csrf

                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="suku_cadang_kode" class="font-weight-bold text-gray-900">Kode Suku Cadang <span class="text-danger">*</span></label>
                            <input type="text" name="suku_cadang_kode" id="suku_cadang_kode" 
                                class="form-control text-uppercase @error('suku_cadang_kode') is-invalid @enderror" 
                                placeholder="Masukkan kode barang (contoh: SK-001, BRG-XYZ)" 
                                value="{{ old('suku_cadang_kode') }}" required>
                            @error('suku_cadang_kode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Kode barang harus bersifat unik.</small>
                        </div>

                        <div class="form-group">
                            <label for="suku_cadang_nama" class="font-weight-bold text-gray-900">Nama Suku Cadang <span class="text-danger">*</span></label>
                            <input type="text" name="suku_cadang_nama" id="suku_cadang_nama" 
                                class="form-control @error('suku_cadang_nama') is-invalid @enderror" 
                                placeholder="Masukkan nama suku cadang" 
                                value="{{ old('suku_cadang_nama') }}" required>
                            @error('suku_cadang_nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="suku_cadang_supplier_id" class="font-weight-bold text-gray-900">Supplier Utama <span class="text-danger">*</span></label>
                            <select name="suku_cadang_supplier_id" id="suku_cadang_supplier_id" 
                                class="form-control @error('suku_cadang_supplier_id') is-invalid @enderror" required>
                                <option value="" disabled selected>-- Pilih Supplier --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->supplier_id }}" {{ old('suku_cadang_supplier_id') == $supplier->supplier_id ? 'selected' : '' }}>
                                        {{ $supplier->supplier_nama }} (Driver: {{ $supplier->supplier_nama_driver }})
                                    </option>
                                @endforeach
                            </select>
                            @error('suku_cadang_supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="suku_cadang_kategori" class="font-weight-bold text-gray-900">Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="suku_cadang_kategori" id="suku_cadang_kategori" 
                                class="form-control @error('suku_cadang_kategori') is-invalid @enderror" 
                                placeholder="Masukkan kategori (contoh: Mesin, Kelistrikan, Body)" 
                                value="{{ old('suku_cadang_kategori') }}" required>
                            @error('suku_cadang_kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="suku_cadang_satuan" class="font-weight-bold text-gray-900">Satuan Barang <span class="text-danger">*</span></label>
                            <input type="text" name="suku_cadang_satuan" id="suku_cadang_satuan" 
                                class="form-control @error('suku_cadang_satuan') is-invalid @enderror" 
                                placeholder="Masukkan satuan (contoh: pcs, unit, set, liter)" 
                                value="{{ old('suku_cadang_satuan') }}" required>
                            @error('suku_cadang_satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="suku_cadang_stok_total" class="font-weight-bold text-gray-900">Stok Awal <span class="text-danger">*</span></label>
                            <input type="number" name="suku_cadang_stok_total" id="suku_cadang_stok_total" 
                                class="form-control @error('suku_cadang_stok_total') is-invalid @enderror" 
                                placeholder="Masukkan stok awal (contoh: 0 atau 100)" 
                                value="{{ old('suku_cadang_stok_total', 0) }}" min="0" required>
                            @error('suku_cadang_stok_total')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="suku_cadang_reorder_point" class="font-weight-bold text-gray-900">Reorder Point (ROP) <span class="text-danger">*</span></label>
                            <input type="number" name="suku_cadang_reorder_point" id="suku_cadang_reorder_point" 
                                class="form-control @error('suku_cadang_reorder_point') is-invalid @enderror" 
                                placeholder="Tentukan batas minimum pemesanan kembali" 
                                value="{{ old('suku_cadang_reorder_point') }}" min="0" required>
                            @error('suku_cadang_reorder_point')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Sistem akan memberi peringatan jika stok menyentuh angka ini.</small>
                        </div>

                        <div class="form-group">
                            <label for="suku_cadang_stok_minimum" class="font-weight-bold text-gray-900">Stok Minimum Keamanan <span class="text-danger">*</span></label>
                            <input type="number" name="suku_cadang_stok_minimum" id="suku_cadang_stok_minimum" 
                                class="form-control @error('suku_cadang_stok_minimum') is-invalid @enderror" 
                                placeholder="Tentukan stok minimum absolut" 
                                value="{{ old('suku_cadang_stok_minimum') }}" min="0" required>
                            @error('suku_cadang_stok_minimum')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Batas kritis stok agar gudang tidak kehabisan barang.</small>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end">
                    <a href="{{ route('suku-cadang.index') }}" class="btn btn-secondary mr-2">Batal</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
