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
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle mr-1"></i> Informasi</h5>
                            <p class="mb-0 small">Pastikan data supplier tetap up-to-date. Daftar driver dan armada pengantar dapat dikelola langsung pada tabel di bawah ini.</p>
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

    <!-- Drivers Sub-Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-secondary d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-truck mr-1"></i> Manajemen Driver & Armada</h6>
            <button type="button" class="btn btn-sm btn-light font-weight-bold shadow-sm" data-toggle="modal" data-target="#addDriverModal">
                <i class="fas fa-plus fa-sm text-gray-800 mr-1"></i> Tambah Driver
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Driver</th>
                            <th>Plat Kendaraan</th>
                            <th>No. Surat Jalan</th>
                            <th class="text-center">Foto Surat Jalan</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($supplier->drivers as $index => $driver)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="font-weight-bold text-gray-900">{{ $driver->nama_driver }}</td>
                                <td><span class="badge badge-secondary px-2 py-1 text-uppercase">{{ $driver->plat_kendaraan }}</span></td>
                                <td>{{ $driver->no_surat_jalan }}</td>
                                <td class="text-center">
                                    @if($driver->foto_sj)
                                        <a href="{{ asset('storage/' . $driver->foto_sj) }}" target="_blank" class="btn btn-sm btn-light border shadow-sm">
                                            <i class="fas fa-image text-primary mr-1"></i> Lihat Foto SJ
                                        </a>
                                    @else
                                        <span class="text-muted small">Tidak ada foto</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-info btn-circle edit-driver-btn"
                                        data-id="{{ $driver->id }}"
                                        data-nama="{{ $driver->nama_driver }}"
                                        data-plat="{{ $driver->plat_kendaraan }}"
                                        data-sj="{{ $driver->no_surat_jalan }}"
                                        data-action="{{ route('drivers.update', $driver->id) }}"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('drivers.destroy', $driver->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus driver ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger btn-circle" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Belum ada data driver untuk supplier ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Driver Modal -->
    <div class="modal fade" id="addDriverModal" tabindex="-1" role="dialog" aria-labelledby="addDriverModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('drivers.store', $supplier->supplier_id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="addDriverModalLabel"><i class="fas fa-plus mr-1"></i> Tambah Driver Baru</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_driver" class="font-weight-bold text-gray-900">Nama Driver <span class="text-danger">*</span></label>
                            <input type="text" name="nama_driver" id="nama_driver" class="form-control" placeholder="Nama lengkap driver" required>
                        </div>
                        <div class="form-group">
                            <label for="plat_kendaraan" class="font-weight-bold text-gray-900">Plat Kendaraan <span class="text-danger">*</span></label>
                            <input type="text" name="plat_kendaraan" id="plat_kendaraan" class="form-control" placeholder="Contoh: B 1234 CD" required>
                        </div>
                        <div class="form-group">
                            <label for="no_surat_jalan" class="font-weight-bold text-gray-900">No. Surat Jalan <span class="text-danger">*</span></label>
                            <input type="text" name="no_surat_jalan" id="no_surat_jalan" class="form-control" placeholder="Contoh: SJ-SUP-123" required>
                        </div>
                        <div class="form-group">
                            <label for="foto_sj" class="font-weight-bold text-gray-900">Upload Foto Surat Jalan (SJ)</label>
                            <input type="file" name="foto_sj" id="foto_sj" class="form-control-file">
                            <small class="text-muted">Format gambar: JPG, JPEG, PNG (Maks 4MB)</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Driver</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Driver Modal -->
    <div class="modal fade" id="editDriverModal" tabindex="-1" role="dialog" aria-labelledby="editDriverModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editDriverForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="editDriverModalLabel"><i class="fas fa-edit mr-1"></i> Edit Data Driver</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_nama_driver" class="font-weight-bold text-gray-900">Nama Driver <span class="text-danger">*</span></label>
                            <input type="text" name="nama_driver" id="edit_nama_driver" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_plat_kendaraan" class="font-weight-bold text-gray-900">Plat Kendaraan <span class="text-danger">*</span></label>
                            <input type="text" name="plat_kendaraan" id="edit_plat_kendaraan" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_no_surat_jalan" class="font-weight-bold text-gray-900">No. Surat Jalan <span class="text-danger">*</span></label>
                            <input type="text" name="no_surat_jalan" id="edit_no_surat_jalan" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_foto_sj" class="font-weight-bold text-gray-900">Ubah Foto Surat Jalan (SJ)</label>
                            <input type="file" name="foto_sj" id="edit_foto_sj" class="form-control-file">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto. Format: JPG, JPEG, PNG (Maks 4MB)</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.edit-driver-btn').click(function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');
                var plat = $(this).data('plat');
                var sj = $(this).data('sj');
                var action = $(this).data('action');

                $('#editDriverForm').attr('action', action);
                $('#edit_nama_driver').val(nama);
                $('#edit_plat_kendaraan').val(plat);
                $('#edit_no_surat_jalan').val(sj);

                $('#editDriverModal').modal('show');
            });
        });
    </script>
@endpush
