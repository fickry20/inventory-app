@extends('layouts.admin')

@section('title', 'Dashboard - Inventory App')

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Card Suku Cadang -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Suku Cadang</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSukuCadang }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cubes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Supplier -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Supplier</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSupplier }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-handshake fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Transaksi Masuk -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Log Barang Masuk</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTransaksiMasuk }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-download fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card ROP Alerts -->
        <div class="col-xl-3 col-md-6 mb-4">
            @if($activeRopCount > 0)
                <div class="card border-left-danger bg-danger-custom shadow h-100 py-2 animated--shake">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Peringatan ROP Aktif</div>
                                <div class="h5 mb-0 font-weight-bold text-danger">{{ $activeRopCount }} Barang</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card border-left-secondary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                    Peringatan ROP Aktif</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">0 Barang</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shield-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Content Row: Charts -->
    <div class="row">

        <!-- Area Chart (Tren Distribusi Barang) -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tren Aktivitas Distribusi (6 Bulan Terakhir)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart (Sebaran Kategori Suku Cadang) -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Sebaran Kategori Suku Cadang</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        @forelse($pieLabels as $index => $label)
                            @php
                                $colors = ['text-primary', 'text-success', 'text-info', 'text-warning', 'text-danger'];
                                $color = $colors[$index % count($colors)];
                            @endphp
                            <span class="mr-2 d-inline-block mb-1">
                                <i class="fas fa-circle {{ $color }}"></i> {{ $label }} ({{ $pieData[$index] }})
                            </span>
                        @empty
                            <span class="text-muted small">Belum ada data barang.</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row: Additional Analytics -->
    <div class="row">
        <!-- Bar Chart (Top 5 Suku Cadang Terpopuler) -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-light d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-bar mr-1"></i> 5 Suku Cadang Paling Sering Keluar (Pcs)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar" style="position: relative; height: 260px; width: 100%;">
                        <canvas id="myBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- ROP Alert List Panel -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-danger-custom-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-danger"><i class="fas fa-exclamation-triangle mr-1"></i> Barang Butuh Restock (Stok &le; ROP)</h6>
                    <span class="badge badge-danger">{{ $ropSukuCadangs->count() }} Item</span>
                </div>
                <div class="card-body p-0" style="max-height: 290px; overflow-y: auto;">
                    <div class="list-group list-group-flush">
                        @forelse($ropSukuCadangs as $rop)
                            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="font-weight-bold text-gray-900 mb-0">{{ $rop->suku_cadang_nama }}</div>
                                    <small class="text-muted">Kode: {{ $rop->suku_cadang_kode }} | Supplier: {{ $rop->supplier->supplier_nama ?? 'N/A' }}</small>
                                </div>
                                <div class="text-right">
                                    <span class="badge badge-danger text-md px-2 py-1">Stok: {{ $rop->suku_cadang_stok_total }} {{ $rop->suku_cadang_satuan }}</span>
                                    <div class="text-xs text-muted mt-1">Batas ROP: {{ $rop->suku_cadang_reorder_point }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-check-circle text-success fa-2x mb-2 d-block"></i>
                                Semua suku cadang dalam kondisi aman (di atas ROP).
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row: Log Aktivitas Terbaru -->
    <div class="row">
        
        <!-- Penerimaan Terbaru (Masuk) -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary-custom d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-arrow-down mr-1"></i> Penerimaan Stok Terbaru</h6>
                    <a href="{{ route('transaksi-masuk.index') }}" class="btn btn-sm btn-link text-primary font-weight-bold py-0">Lihat Semua &rarr;</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm small table-hover" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>No Dokumen</th>
                                    <th>Suku Cadang</th>
                                    <th class="text-right">Kuantitas</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentMasuk as $masuk)
                                    <tr>
                                        <td class="font-weight-bold text-gray-900">{{ $masuk->transaksi_masuk_no_dokumen }}</td>
                                        <td>{{ $masuk->sukuCadang->suku_cadang_nama ?? 'Dihapus' }}</td>
                                        <td class="text-right text-success font-weight-bold">+{{ $masuk->transaksi_masuk_jumlah }}</td>
                                        <td>{{ $masuk->transaksi_masuk_created_at ? $masuk->transaksi_masuk_created_at->format('d M H:i') : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-muted">Belum ada barang masuk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengeluaran Terbaru (Keluar) -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-danger-custom-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-danger"><i class="fas fa-arrow-up mr-1"></i> Pengeluaran Stok Terbaru (FIFO)</h6>
                    <a href="{{ route('transaksi-keluar.index') }}" class="btn btn-sm btn-link text-danger font-weight-bold py-0">Lihat Semua &rarr;</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm small table-hover" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>No Dokumen</th>
                                    <th>Suku Cadang</th>
                                    <th class="text-right">Terpenuhi</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentKeluar as $keluar)
                                    <tr>
                                        <td class="font-weight-bold text-gray-900">{{ $keluar->no_dokumen }}</td>
                                        <td>{{ $keluar->sukuCadang->suku_cadang_nama ?? 'Dihapus' }}</td>
                                        <td class="text-right text-danger font-weight-bold">-{{ $keluar->jumlah_terpenuhi }}</td>
                                        <td>{{ $keluar->created_at ? $keluar->created_at->format('d M H:i') : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-muted">Belum ada barang keluar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .bg-danger-custom {
                background-color: rgba(231, 74, 59, 0.05);
            }
            .bg-danger-custom-header {
                background-color: rgba(231, 74, 59, 0.03);
            }
            .bg-primary-custom {
                background-color: rgba(78, 115, 223, 0.03);
            }
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
                20%, 40%, 60%, 80% { transform: translateX(5px); }
            }
            .animated--shake {
                animation: shake 2.5s infinite;
            }
        </style>
    @endpush

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <script>
        // Set new default font family and font color to mimic Bootstrap's default styling
        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        // 1. Line Area Chart (Monthly Tren)
        var ctxArea = document.getElementById("myAreaChart");
        if (ctxArea) {
            new Chart(ctxArea, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: "Barang Masuk (Pcs)",
                        lineTension: 0.3,
                        backgroundColor: "rgba(78, 115, 223, 0.05)",
                        borderColor: "rgba(78, 115, 223, 1)",
                        pointRadius: 3,
                        pointBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointBorderColor: "rgba(78, 115, 223, 1)",
                        pointHoverRadius: 4,
                        pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        data: {!! json_encode($chartMasukData) !!},
                    }, {
                        label: "Barang Keluar (Pcs)",
                        lineTension: 0.3,
                        backgroundColor: "rgba(231, 74, 59, 0.05)",
                        borderColor: "rgba(231, 74, 59, 1)",
                        pointRadius: 3,
                        pointBackgroundColor: "rgba(231, 74, 59, 1)",
                        pointBorderColor: "rgba(231, 74, 59, 1)",
                        pointHoverRadius: 4,
                        pointHoverBackgroundColor: "rgba(231, 74, 59, 1)",
                        pointHoverBorderColor: "rgba(231, 74, 59, 1)",
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        data: {!! json_encode($chartKeluarData) !!},
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 10,
                            right: 25,
                            top: 25,
                            bottom: 0
                        }
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                maxTicksLimit: 7
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                maxTicksLimit: 5,
                                padding: 10,
                            },
                            gridLines: {
                                color: "rgb(234, 236, 244)",
                                zeroLineColor: "rgb(234, 236, 244)",
                                drawBorder: false,
                                borderDash: [2],
                                zeroLineBorderDash: [2]
                            }
                        }],
                    },
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            boxWidth: 15,
                            padding: 10
                        }
                    },
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        titleMarginBottom: 10,
                        titleFontColor: '#6e707e',
                        titleFontSize: 14,
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: true,
                        intersect: false,
                        mode: 'index',
                        caretPadding: 10,
                    }
                }
            });
        }

        // 2. Pie Chart (Sebaran Kategori)
        var ctxPie = document.getElementById("myPieChart");
        if (ctxPie) {
            new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($pieLabels) !!},
                    datasets: [{
                        data: {!! json_encode($pieData) !!},
                        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                        hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#fda814', '#be2617'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    },
                    legend: {
                        display: false
                    },
                    cutoutPercentage: 80,
                },
            });
        }

        // 3. Bar Chart (Top 5 Outgoing Spare Parts)
        var ctxBar = document.getElementById("myBarChart");
        if (ctxBar) {
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($topLabels) !!},
                    datasets: [{
                        label: "Total Keluar (Pcs)",
                        backgroundColor: "#4e73df",
                        hoverBackgroundColor: "#2e59d9",
                        borderColor: "#4e73df",
                        data: {!! json_encode($topData) !!},
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 10,
                            right: 25,
                            top: 25,
                            bottom: 0
                        }
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                maxTicksLimit: 6
                            },
                            maxBarThickness: 25,
                        }],
                        yAxes: [{
                            ticks: {
                                min: 0,
                                maxTicksLimit: 5,
                                padding: 10,
                            },
                            gridLines: {
                                color: "rgb(234, 236, 244)",
                                zeroLineColor: "rgb(234, 236, 244)",
                                drawBorder: false,
                                borderDash: [2],
                                zeroLineBorderDash: [2]
                            }
                        }],
                    },
                    legend: {
                        display: false
                    },
                    tooltips: {
                        titleMarginBottom: 10,
                        titleFontColor: '#6e707e',
                        titleFontSize: 14,
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    },
                }
            });
        }
    </script>
@endpush
