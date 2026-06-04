@extends('layouts.admin')

@section('title', 'Laporan Persediaan - Inventory App')

@section('content')

    <!-- Page Heading (Screen Only) -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 no-print">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-file-invoice mr-2 text-primary"></i>Laporan Persediaan</h1>
        <button onclick="window.print()" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-print fa-sm text-white-50 mr-2"></i> Cetak Laporan
        </button>
    </div>

    <!-- Filter Card (Screen Only) -->
    <div class="card shadow mb-4 no-print">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter mr-1"></i> Filter Laporan</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('laporan.index') }}" method="GET" class="row align-items-end">
                <div class="col-md-3 form-group">
                    <label for="start_date" class="font-weight-bold text-xs text-uppercase">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}" required>
                </div>
                <div class="col-md-3 form-group">
                    <label for="end_date" class="font-weight-bold text-xs text-uppercase">Tanggal Selesai</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}" required>
                </div>
                <div class="col-md-3 form-group">
                    <label for="type" class="font-weight-bold text-xs text-uppercase">Tipe Transaksi</label>
                    <select name="type" id="type" class="form-control">
                        <option value="semua" {{ $type === 'semua' ? 'selected' : '' }}>Semua Transaksi</option>
                        <option value="masuk" {{ $type === 'masuk' ? 'selected' : '' }}>Barang Masuk Only</option>
                        <option value="keluar" {{ $type === 'keluar' ? 'selected' : '' }}>Barang Keluar Only</option>
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search mr-1"></i> Filter Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Header for Print (Hidden on Screen) -->
    <div class="print-header text-center mb-4">
        <h2>LAPORAN PERSEDIAAN SUKU CADANG</h2>
        <h5>Sistem Manajemen Inventory</h5>
        <p class="mb-0 text-muted">Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d F Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</strong></p>
        <hr style="border: 1px solid #333; margin-top: 10px; margin-bottom: 20px;">
    </div>

    <!-- Barang Masuk Section -->
    @if($type === 'semua' || $type === 'masuk')
        <div class="card shadow mb-4 print-card">
            <div class="card-header py-3 bg-light d-flex justify-content-between align-items-center print-card-header">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-arrow-down mr-1"></i> Transaksi Barang Masuk</h6>
                <span class="badge badge-primary print-badge">{{ $transaksiMasuk->count() }} Transaksi</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover table-sm" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Tanggal</th>
                                <th>Suku Cadang</th>
                                <th>Supplier</th>
                                <th class="text-right">Jumlah</th>
                                <th>Petugas</th>
                                <th>Kendaraan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksiMasuk as $index => $masuk)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $masuk->transaksi_masuk_created_at ? $masuk->transaksi_masuk_created_at->format('d-m-Y H:i') : '-' }}</td>
                                    <td>{{ $masuk->sukuCadang->suku_cadang_nama ?? 'N/A' }} ({{ $masuk->sukuCadang->suku_cadang_kode ?? 'N/A' }})</td>
                                    <td>{{ $masuk->supplier->supplier_nama ?? 'N/A' }}</td>
                                    <td class="text-right text-success font-weight-bold">+{{ $masuk->transaksi_masuk_jumlah }}</td>
                                    <td>{{ $masuk->user->users_username ?? 'N/A' }}</td>
                                    <td>{{ $masuk->driver->plat_kendaraan ?? 'N/A' }} (Driver: {{ $masuk->driver->nama_driver ?? 'N/A' }})</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-3 text-muted">Tidak ada transaksi barang masuk pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Barang Keluar Section -->
    @if($type === 'semua' || $type === 'keluar')
        <div class="card shadow mb-4 print-card">
            <div class="card-header py-3 bg-light d-flex justify-content-between align-items-center print-card-header">
                <h6 class="m-0 font-weight-bold text-danger"><i class="fas fa-arrow-up mr-1"></i> Transaksi Barang Keluar</h6>
                <span class="badge badge-danger print-badge">{{ $transaksiKeluar->count() }} Transaksi</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover table-sm" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Tanggal</th>
                                <th>Tujuan PT</th>
                                <th>Suku Cadang</th>
                                <th class="text-right">Diminta</th>
                                <th class="text-right">Terpenuhi</th>
                                <th>Status</th>
                                <th>Petugas</th>
                                <th>Kendaraan</th>
                                <th class="text-center no-print" style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksiKeluar as $index => $keluar)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $keluar->created_at ? $keluar->created_at->format('d-m-Y H:i') : '-' }}</td>
                                    <td class="font-weight-bold text-gray-900">{{ $keluar->perusahaanTujuan->nama ?? 'N/A' }}</td>
                                    <td>{{ $keluar->sukuCadang->suku_cadang_nama ?? 'N/A' }} ({{ $keluar->sukuCadang->suku_cadang_kode ?? 'N/A' }})</td>
                                    <td class="text-right font-weight-bold">{{ $keluar->jumlah_diminta }}</td>
                                    <td class="text-right text-danger font-weight-bold">-{{ $keluar->jumlah_terpenuhi }}</td>
                                    <td>
                                        @if($keluar->status === 'success')
                                            <span class="badge badge-success print-text-status">Success</span>
                                        @else
                                            <span class="badge badge-warning print-text-status">{{ $keluar->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $keluar->user->users_username ?? 'N/A' }}</td>
                                    <td>{{ $keluar->kendaraan->kendaraan_plat ?? 'N/A' }} (Driver: {{ $keluar->kendaraan->kendaraan_nama_driver ?? 'N/A' }})</td>
                                    <td class="text-center no-print">
                                        <a href="{{ route('transaksi-keluar.cetak-sj', $keluar->transaksi_keluar_id) }}" target="_blank" class="btn btn-xs btn-outline-secondary py-0 px-2 font-weight-bold">
                                            <i class="fas fa-print mr-1"></i> Cetak SJ
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-3 text-muted">Tidak ada transaksi barang keluar pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Signature for Print (Hidden on Screen) -->
    <div class="print-signatures row justify-content-between mt-5 pt-4">
        <div class="col-4 text-center">
            <p class="mb-5">Disiapkan Oleh,</p>
            <br><br>
            <p class="font-weight-bold mb-0"><u>{{ auth()->user()->users_username }}</u></p>
            <p class="text-muted">{{ auth()->user()->users_jabatan }}</p>
        </div>
        <div class="col-4 text-center">
            <p class="mb-5">Mengetahui/Menyetujui,</p>
            <br><br>
            <p class="font-weight-bold mb-0"><u>_______________________</u></p>
            <p class="text-muted">Manager Logistik</p>
        </div>
    </div>

    @push('styles')
        <style>
            /* Design system for print output */
            .print-header {
                display: none;
            }
            .print-signatures {
                display: none;
            }

            @media print {
                /* Hide sidebar, topbar, footer, forms, scroll button, etc. */
                #accordionSidebar,
                .navbar,
                footer,
                .scroll-to-top,
                .no-print,
                .btn,
                form,
                .print-badge {
                    display: none !important;
                }

                /* Expand wrapper to full width */
                #content-wrapper {
                    margin-left: 0 !important;
                    padding: 0 !important;
                    background-color: #fff !important;
                }
                
                #content {
                    background-color: #fff !important;
                }

                .container-fluid {
                    padding: 0 !important;
                }

                /* Show print header and signatures */
                .print-header {
                    display: block !important;
                }
                .print-signatures {
                    display: flex !important;
                }

                /* Clean layout adjustments */
                body {
                    background-color: #fff !important;
                    color: #000 !important;
                    font-size: 12px;
                }

                .card {
                    border: none !important;
                    box-shadow: none !important;
                    margin-bottom: 30px !important;
                    background: transparent !important;
                }

                .card-body {
                    padding: 0 !important;
                }

                .print-card-header {
                    background-color: #f8f9fc !important;
                    border: 1px solid #e3e6f0 !important;
                    padding: 10px !important;
                    margin-bottom: 10px !important;
                }

                .print-card-header h6 {
                    color: #333 !important;
                    font-size: 14px !important;
                }

                /* Make sure tables are printed correctly */
                table {
                    border-collapse: collapse !important;
                    width: 100% !important;
                }

                th, td {
                    border: 1px solid #000 !important;
                    color: #000 !important;
                    padding: 6px !important;
                }

                .thead-light th {
                    background-color: #eaeaea !important;
                    border: 1px solid #000 !important;
                }

                .text-success {
                    color: #000 !important;
                }

                .text-danger {
                    color: #000 !important;
                }

                .print-text-status {
                    font-weight: bold;
                    background: none !important;
                    color: #000 !important;
                    padding: 0 !important;
                }
            }
        </style>
    @endpush

@endsection
