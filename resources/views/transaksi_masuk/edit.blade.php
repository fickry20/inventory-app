@extends('layouts.admin')

@section('title', 'Edit Barang Masuk - Inventory App')

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Transaksi Barang Masuk</h1>
        <a href="{{ route('transaksi-masuk.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
        </a>
    </div>

    <!-- Alert Warning jika Batch sudah digunakan -->
    @if($hasBeenUsed)
        <div class="alert alert-warning shadow-sm border-left-warning mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle mr-3 fa-2x text-warning"></i>
                <div>
                    <h5 class="alert-heading font-weight-bold mb-1">Peringatan: Batch Terpakai!</h5>
                    <p class="mb-0 small">Kuantitas dan Suku Cadang transaksi ini telah dikunci (readonly) karena sebagian atau seluruh stok dari batch ini sudah didistribusikan / terpakai oleh transaksi barang keluar. Anda hanya dapat mengubah informasi dokumen, supplier, armada, dan keterangan.</p>
                </div>
            </div>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-info">
            <h6 class="m-0 font-weight-bold text-white">Form Edit Transaksi Barang Masuk: {{ $transaksiMasuk->transaksi_masuk_no_dokumen }}</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('transaksi-masuk.update', $transaksiMasuk->transaksi_masuk_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="transaksi_masuk_suku_cadang_id" class="font-weight-bold text-gray-900">Suku Cadang / Barang <span class="text-danger">*</span></label>
                            @if($hasBeenUsed)
                                <select id="transaksi_masuk_suku_cadang_id" class="form-control" disabled>
                                    <option value="{{ $transaksiMasuk->transaksi_masuk_suku_cadang_id }}">
                                        [{{ $transaksiMasuk->sukuCadang->suku_cadang_kode ?? '' }}] {{ $transaksiMasuk->sukuCadang->suku_cadang_nama ?? 'Barang Dihapus' }}
                                    </option>
                                </select>
                                <input type="hidden" name="transaksi_masuk_suku_cadang_id" value="{{ $transaksiMasuk->transaksi_masuk_suku_cadang_id }}">
                            @else
                                <select name="transaksi_masuk_suku_cadang_id" id="transaksi_masuk_suku_cadang_id" 
                                    class="form-control @error('transaksi_masuk_suku_cadang_id') is-invalid @enderror" required>
                                    @foreach($sukuCadangs as $item)
                                        <option value="{{ $item->suku_cadang_id }}" {{ old('transaksi_masuk_suku_cadang_id', $transaksiMasuk->transaksi_masuk_suku_cadang_id) == $item->suku_cadang_id ? 'selected' : '' }}>
                                            [{{ $item->suku_cadang_kode }}] {{ $item->suku_cadang_nama }} (Stok saat ini: {{ $item->suku_cadang_stok_total }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('transaksi_masuk_suku_cadang_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="transaksi_masuk_supplier_id" class="font-weight-bold text-gray-900">Supplier Pengirim <span class="text-danger">*</span></label>
                            <select name="transaksi_masuk_supplier_id" id="transaksi_masuk_supplier_id" 
                                class="form-control @error('transaksi_masuk_supplier_id') is-invalid @enderror" required>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->supplier_id }}" 
                                        data-plat="{{ $supplier->supplier_plat_kendaraan }}" 
                                        data-driver="{{ $supplier->supplier_nama_driver }}"
                                        {{ old('transaksi_masuk_supplier_id', $transaksiMasuk->transaksi_masuk_supplier_id) == $supplier->supplier_id ? 'selected' : '' }}>
                                        {{ $supplier->supplier_nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('transaksi_masuk_supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold text-gray-700">Armada Pengantar / Driver</label>
                            <input type="text" id="supplier_info" class="form-control bg-light text-gray-900 font-weight-bold" placeholder="Pilih supplier untuk memuat data pengiriman..." readonly disabled>
                            <small class="text-muted">Informasi plat kendaraan dan driver dimuat otomatis dari profil supplier.</small>
                        </div>

                        <div class="form-group">
                            <label for="transaksi_masuk_jumlah" class="font-weight-bold text-gray-900">Kuantitas Masuk <span class="text-danger">*</span></label>
                            @if($hasBeenUsed)
                                <input type="number" id="transaksi_masuk_jumlah" class="form-control" value="{{ $transaksiMasuk->transaksi_masuk_jumlah }}" disabled>
                                <input type="hidden" name="transaksi_masuk_jumlah" value="{{ $transaksiMasuk->transaksi_masuk_jumlah }}">
                            @else
                                <input type="number" name="transaksi_masuk_jumlah" id="transaksi_masuk_jumlah" 
                                    class="form-control @error('transaksi_masuk_jumlah') is-invalid @enderror" 
                                    placeholder="Masukkan jumlah barang yang masuk" 
                                    value="{{ old('transaksi_masuk_jumlah', $transaksiMasuk->transaksi_masuk_jumlah) }}" min="1" required>
                                @error('transaksi_masuk_jumlah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="transaksi_masuk_no_dokumen" class="font-weight-bold text-gray-900">No. Dokumen Penerimaan <span class="text-danger">*</span></label>
                            <input type="text" name="transaksi_masuk_no_dokumen" id="transaksi_masuk_no_dokumen" 
                                class="form-control @error('transaksi_masuk_no_dokumen') is-invalid @enderror" 
                                placeholder="Masukkan nomor dokumen" 
                                value="{{ old('transaksi_masuk_no_dokumen', $transaksiMasuk->transaksi_masuk_no_dokumen) }}" required>
                            @error('transaksi_masuk_no_dokumen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="transaksi_masuk_no_surat_jalan" class="font-weight-bold text-gray-900">No. Surat Jalan Pengantar <span class="text-danger">*</span></label>
                            <input type="text" name="transaksi_masuk_no_surat_jalan" id="transaksi_masuk_no_surat_jalan" 
                                class="form-control @error('transaksi_masuk_no_surat_jalan') is-invalid @enderror" 
                                placeholder="Masukkan nomor surat jalan" 
                                value="{{ old('transaksi_masuk_no_surat_jalan', $transaksiMasuk->transaksi_masuk_no_surat_jalan) }}" required>
                            @error('transaksi_masuk_no_surat_jalan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="transaksi_masuk_keterangan" class="font-weight-bold text-gray-900">Keterangan / Catatan Tambahan</label>
                            <textarea name="transaksi_masuk_keterangan" id="transaksi_masuk_keterangan" rows="4" 
                                class="form-control @error('transaksi_masuk_keterangan') is-invalid @enderror" 
                                placeholder="Catatan tambahan (opsional)...">{{ old('transaksi_masuk_keterangan', $transaksiMasuk->transaksi_masuk_keterangan) }}</textarea>
                            @error('transaksi_masuk_keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end">
                    <a href="{{ route('transaksi-masuk.index') }}" class="btn btn-secondary mr-2">Batal</a>
                    <button type="submit" class="btn btn-info px-4">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#transaksi_masuk_supplier_id').change(function() {
                var selected = $(this).find('option:selected');
                var plat = selected.data('plat');
                var driver = selected.data('driver');
                if (plat && driver) {
                    $('#supplier_info').val(plat + ' (Driver: ' + driver + ')');
                } else {
                    $('#supplier_info').val('');
                }
            }).trigger('change');
        });
    </script>
@endpush
