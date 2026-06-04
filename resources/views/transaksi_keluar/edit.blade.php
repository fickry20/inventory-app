@extends('layouts.admin')

@section('title', 'Edit Barang Keluar - Inventory App')

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Transaksi Barang Keluar</h1>
        <a href="{{ route('transaksi-keluar.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
        </a>
    </div>

    <div class="alert alert-info shadow-sm border-left-info mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-info-circle mr-3 fa-2x text-info"></i>
            <div>
                <h5 class="alert-heading font-weight-bold mb-1">Catatan Pembaruan FIFO:</h5>
                <p class="mb-0 small">Menyimpan perubahan pada kuantitas atau suku cadang akan memicu **Rollback Stok** ke batch awal, lalu mengalokasikan ulang stok menggunakan metode FIFO berdasarkan data baru Anda secara otomatis.</p>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-info">
            <h6 class="m-0 font-weight-bold text-white">Form Edit Transaksi Barang Keluar: {{ $transaksiKeluar->no_surat_jalan }}</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('transaksi-keluar.update', $transaksiKeluar->transaksi_keluar_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="suku_cadang_id" class="font-weight-bold text-gray-900">Suku Cadang / Barang <span class="text-danger">*</span></label>
                            <select name="suku_cadang_id" id="suku_cadang_id" 
                                class="form-control @error('suku_cadang_id') is-invalid @enderror" required>
                                @foreach($sukuCadangs as $item)
                                    <option value="{{ $item->suku_cadang_id }}" {{ old('suku_cadang_id', $transaksiKeluar->suku_cadang_id) == $item->suku_cadang_id ? 'selected' : '' }}>
                                        [{{ $item->suku_cadang_kode }}] {{ $item->suku_cadang_nama }} (Stok saat ini: {{ $item->suku_cadang_stok_total }} {{ $item->suku_cadang_satuan }})
                                    </option>
                                @endforeach
                            </select>
                            @error('suku_cadang_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="kendaraan_id" class="font-weight-bold text-gray-900">Armada Pengantar / Driver <span class="text-danger">*</span></label>
                            <select name="kendaraan_id" id="kendaraan_id" 
                                class="form-control @error('kendaraan_id') is-invalid @enderror" required>
                                @foreach($kendaraans as $kendaraan)
                                    <option value="{{ $kendaraan->kendaraan_id }}" {{ old('kendaraan_id', $transaksiKeluar->kendaraan_id) == $kendaraan->kendaraan_id ? 'selected' : '' }}>
                                        [{{ strtoupper($kendaraan->kendaraan_jenis) }}] {{ $kendaraan->kendaraan_plat }} (Driver: {{ $kendaraan->kendaraan_nama_driver }})
                                    </option>
                                @endforeach
                            </select>
                            @error('kendaraan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="jumlah_diminta" class="font-weight-bold text-gray-900">Jumlah Barang Diminta <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_diminta" id="jumlah_diminta" 
                                class="form-control @error('jumlah_diminta') is-invalid @enderror" 
                                placeholder="Masukkan jumlah barang yang dikeluarkan" 
                                value="{{ old('jumlah_diminta', $transaksiKeluar->jumlah_diminta) }}" min="1" required>
                            @error('jumlah_diminta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_surat_jalan" class="font-weight-bold text-gray-900">No. Surat Jalan Pengeluaran <span class="text-danger">*</span></label>
                            <input type="text" name="no_surat_jalan" id="no_surat_jalan" 
                                class="form-control @error('no_surat_jalan') is-invalid @enderror" 
                                placeholder="Masukkan nomor surat jalan" 
                                value="{{ old('no_surat_jalan', $transaksiKeluar->no_surat_jalan) }}" required>
                            @error('no_surat_jalan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="tujuan_pt_id" class="font-weight-bold text-gray-900">Tujuan PT / Perusahaan Penerima <span class="text-danger">*</span></label>
                            <select name="tujuan_pt_id" id="tujuan_pt_id" 
                                class="form-control @error('tujuan_pt_id') is-invalid @enderror" required>
                                @foreach($perusahaans as $perusahaan)
                                    <option value="{{ $perusahaan->id }}" {{ old('tujuan_pt_id', $transaksiKeluar->tujuan_pt_id) == $perusahaan->id ? 'selected' : '' }}>
                                        {{ $perusahaan->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tujuan_pt_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="keterangan" class="font-weight-bold text-gray-900">Keterangan / Catatan Tambahan</label>
                            <textarea name="keterangan" id="keterangan" rows="4" 
                                class="form-control @error('keterangan') is-invalid @enderror" 
                                placeholder="Catatan tambahan (opsional)...">{{ old('keterangan', $transaksiKeluar->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end">
                    <a href="{{ route('transaksi-keluar.index') }}" class="btn btn-secondary mr-2">Batal</a>
                    <button type="submit" class="btn btn-info px-4">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
